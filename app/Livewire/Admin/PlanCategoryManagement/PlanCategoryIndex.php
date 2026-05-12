<?php

namespace App\Livewire\Admin\PlanCategoryManagement;

use App\Models\DietPlan;
use App\Models\MealType;
use App\Models\PlanCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Title('Plan Categories Management')]
class PlanCategoryIndex extends Component
{
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */

    // ===== For Livewire Pagination =====
    use WithPagination, WithoutUrlPagination;
    use AuthorizesRequests;
    use WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | 2. Public State Properties
    |--------------------------------------------------------------------------
    */

    // ===== Page Meta =====
    public string $subject = 'plan category';

    // ===== Filters =====
    public string $search = '';

    // ===== Table State =====
    public array $selected = [];
    public bool $selectAll = false;
    public int $perPage = 5;

    // ===== Form State =====
    public bool $isEdit = false;
    public ?int $editRow = null;

    public string $name = '';
    public string $slug = '';
    public $diet_plan_id;
    public $days_of_plan;
    public $min_calories;
    public $max_calories;
    public $protein;
    public $carbs;
    public $fat;
    public $fiber;

    public $image; // new upload
    public $existingImage; // old image path
    public $user_id;
    public $status;

    public $mealTypes = [];

    public $csv;


    /*
    |--------------------------------------------------------------------------
    | 3. Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    // This function will reset the page once after searching.
    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    // this method will
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->rows
                ->pluck('id')
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $rowCount = $this->rows->count();

        $this->selectAll = $rowCount > 0 && count($this->selected) === $rowCount;
    }

    public function updatedPage()
    {
        $this->resetSelection();
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Computed Properties
    |--------------------------------------------------------------------------
    */

    // This function will
    #[Computed]
    public function rowsQuery(): Builder
    {
        return PlanCategory::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name','like',"%{$this->search}%")
                    ->orWhere('created_at','like',"%{$this->search}%");
                });
        })->latest();
    }

    // This function will
    #[Computed]
    public function rows()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    // This function will
    #[Computed]
    public function diet_plans()
    {
        return DietPlan::latest()->get();
    }

    // This function will
    #[Computed]
    public function meal_types()
    {
        return MealType::latest()->get();
    }

    public function singleView(int $id)
    {
        // Selecting specific table row with specific ID
        $meal = PlanCategory::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->name         = $meal->name;
        $this->min_calories     = $meal->min_calories;
        $this->protein      = $meal->protein;
        $this->carbs        = $meal->carbs;
        $this->fat          = $meal->fat;
        $this->fiber        = $meal->fiber;

        // Opening the form modal
        $this->dispatch('open-view-modal');
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    // This function open the edit modal
    public function edit(int $id)
    {
        // Selecting specific table row with specific ID
        $selectedTableRow = PlanCategory::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill($selectedTableRow->only([
            'name',
            'slug',
            'diet_plan_id',
            'days_of_plan',
            'min_calories',
            'max_calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
            'status',
        ]));

        $this->existingImage = $selectedTableRow->image;
        $this->image = null;

        $this->mealTypes = $selectedTableRow
        ->mealTypes
        ->pluck('id')
        ->toArray();

        // Changing value to isEdit property
        $this->isEdit = true;

        // Assigning value to edit Row
        $this->editRow = $id;

        // Opening the form modal
        $this->dispatch('open-modal');
    }

    protected function sanitize()
    {
        foreach ([
            'name',
            'slug',
            'diet_plan_id',
            'days_of_plan',
            'min_calories',
            'max_calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function is storing and updating data in a specific table.
    public function save()
    {
        // dd($this->mealTypes);
        // Sanitizing form data
        $this->sanitize();

        if ($this->isEdit) {

            $this->authorize('plan-category.edit');

            $planCategory = PlanCategory::findOrFail($this->editRow);

            // ✅ Always slugify
            $this->slug = Str::slug($this->slug);

            $data = $this->validate([
                'name'        => ['required','string','max:40'],
                'slug'              => [
                    'required',
                    'string',
                    'max:60',
                    Rule::unique('plan_categories','slug')->ignore($planCategory->id),
                ],
                'diet_plan_id'        => ['required','numeric'],
                'days_of_plan'        => ['required','numeric'],
                'min_calories'        => ['required','numeric'],
                'max_calories'        => ['required','numeric'],
                'protein'             => ['required','numeric'],
                'carbs'               => ['required','numeric'],
                'fat'                 => ['required','numeric'],
                'fiber'               => ['nullable','numeric'],

                'image' => ['nullable','image','mimes:jpg,jpeg,png','max:512'],

                'status'         =>  ['required','in:active,inactive'],

                'mealTypes'      => ['required','array','min:1'],
                'mealTypes.*'    => ['exists:meal_types,id'],
            ]);

            // ✅ if new image uploaded
            if ($this->image) {

                if ($planCategory->image && Storage::disk('public')->exists($planCategory->image)) {
                    Storage::disk('public')->delete($planCategory->image);
                }

                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();

                $data['image'] = $this->image->storeAs('meals', $filename, 'public');
            }else{
                unset($data['image']);
            }

            $planCategory['user_id'] = auth()->id();

            $planCategory->update($data);

            $planCategory->mealTypes()->sync($this->mealTypes);

            $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');

        } else {

            $this->authorize('plan-category.create');

            // Checking form validation
            $data = $this->validate([
                'name'                => ['required','string','max:40'],
                'diet_plan_id'        => ['required','numeric'],
                'days_of_plan'        => ['required','numeric'],
                'min_calories'        => ['required','numeric'],
                'max_calories'        => ['required','numeric'],
                'protein'             => ['required','numeric'],
                'carbs'               => ['required','numeric'],
                'fat'                 => ['required','numeric'],
                'fiber'               => ['nullable','numeric'],

                'image' => ['nullable','image','mimes:jpg,jpeg,png','max:512'],

                'mealTypes'      => ['required','array','min:1'],
                'mealTypes.*'    => ['exists:meal_types,id'],
            ]);

            $slug = Str::slug($this->name);
            $originalSlug = $slug;
            $count = 1;

            while (PlanCategory::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;

            $data['user_id'] = auth()->id();

            // Store image
            if ($this->image) {
                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();
                $data['image'] = $this->image->storeAs('plan_categories', $filename, 'public');
            }

            // Inserting a row into the database
            $meal = PlanCategory::create($data);

            $meal->mealTypes()->sync($this->mealTypes);

            // Notifying that a row has been successfully inserted into the database
            $this->dispatch('toast', message: ucfirst($this->subject) . ' created successfully', type: 'success');
        }

        // Resetting form fields
        $this->resetFields();

        // Refreshing the table
        $this->refreshTable();

        // Closing the form modal
        $this->dispatch('close-modal');
    }

    // for csv file upload
    public function importCSV()
    {
        $this->validate([
            'csv' => ['required','file','mimes:csv,txt','max:2048']
        ]);

        $path = $this->csv->getRealPath();

        $rows = array_map('str_getcsv', file($path));

        // header remove
        $header = array_shift($rows);

        foreach ($rows as $row) {

            $data = array_combine($header, $row);

            // database insert
            if (!PlanCategory::where('name', $data['name'])->exists()) {
                PlanCategory::firstOrCreate([
                    'name' => $data['name'],
                ]);
            }
        }

        $this->dispatch('toast', message: ucfirst($this->subject) . ' CSV imported successfully', type: 'success');

        // Resetting form fields
        $this->resetFields();

        // Refreshing the table
        $this->refreshTable();

        // Closing the import modal
        $this->dispatch('close-import-modal');
    }

    // This function is deleted a single data in a specific table
    public function delete(int $id)
    {
        $this->authorize('plan-category.delete');

        PlanCategory::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('plan-category.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        PlanCategory::whereIn('id', $this->selected)->delete();

        $this->dispatch('toast', message: count($this->selected) . ' ' . ucfirst($this->subject) . ' deleted successfully!', type: 'success');

        // Reset selection
        $this->resetSelection();
        $this->dispatch('clear-selection');
        $this->refreshTable();
        $this->dispatch('close-bulk-delete-modal');

    }

    /*
    |--------------------------------------------------------------------------
    | 7. Helper Methods
    |--------------------------------------------------------------------------
    */

    // This method reset bulk selected property
    protected function resetSelection()
    {
        $this->reset(['selected', 'selectAll']);
    }

    protected function refreshTable()
    {
        unset($this->rows, $this->rowsQuery);
    }

    // This function reset all fields value
    public function resetFields()
    {
        $this->reset([
            'search',

            'name',
            'slug',
            'diet_plan_id',
            'days_of_plan',
            'min_calories',
            'max_calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
            'mealTypes',

            'csv',
            'isEdit',
            'editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('plan-category.view');

        return view('livewire.admin.plan-category-management.plan-category-index');
    }
}



