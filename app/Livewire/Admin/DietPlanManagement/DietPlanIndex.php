<?php

namespace App\Livewire\Admin\DietPlanManagement;

use App\Models\DietPlan;
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

#[Title('Diet Plans Management')]
class DietPlanIndex extends Component
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
    public string $subject = 'diet plan';

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
    public string $description = '';
    public string $diet_plan_type = '';
    public $image; // new upload
    public $existingImage; // old image path
    public string $color = '';
    public $status;

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
        return DietPlan::query()
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

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    // This function open the edit modal
    public function edit(int $id)
    {
        // Selecting specific table row with specific ID
        $dietPlan = DietPlan::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill($dietPlan->only([
            'name',
            'slug',
            'description',
            'diet_plan_type',
            'color',
            'status',
        ]));

        $this->existingImage = $dietPlan->image;
        $this->image = null;

        // Changing value to isEdit property
        $this->isEdit = true;

        // Assigning value to edit Row
        $this->editRow = $id;

        // Opening the form modal
        $this->dispatch('open-modal');
    }

    // This function is storing and updating data in a specific table.
    public function save()
    {
        // Sanitizing form data
        $this->sanitize();

        if ($this->isEdit) {

            $this->authorize('diet-plan.edit');

            $dietPlan = DietPlan::findOrFail($this->editRow);

            // ✅ Always slugify
            $this->slug = Str::slug($this->slug);

            $data = $this->validate([
                'name'              => ['required','string','max:40'],
                'slug'              => [
                    'required',
                    'string',
                    'max:60',
                    Rule::unique('diet_plans','slug')->whereNull('deleted_at')->ignore($dietPlan->id),
                ],
                'description'       => ['nullable','string'],
                'diet_plan_type'    => ['required','string','max:40'],
                'image'             => ['nullable','image','mimes:jpg,jpeg,png','max:256'],
                'color'             => ['nullable','string','max:20'],
                'status'            =>  ['required','in:active,inactive'],
            ]);

            // ✅ if new image uploaded
            if ($this->image) {

                if ($dietPlan->image && Storage::disk('public')->exists($dietPlan->image)) {
                    Storage::disk('public')->delete($dietPlan->image);
                }

                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();

                $data['image'] = $this->image->storeAs('diet-plans', $filename, 'public');
            }else{
                unset($data['image']);
            }

            $dietPlan->update($data);

            $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');

        } else {

            $this->authorize('diet-plan.create');

            // Checking form validation
            $data = $this->validate([
                'name'              =>  ['required','string','max:40'],
                'description'       =>  ['nullable','string'],
                'diet_plan_type'    =>  ['required','string','max:40'],
                'image'             =>  ['nullable','file','mimes:jpg,jpeg,png','max:256'],
                'color'             =>  ['nullable','string','max:20'],
            ]);

            $slug = Str::slug($this->name);
            $originalSlug = $slug;
            $count = 1;

            while (DietPlan::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;

            $data['user_id'] = auth()->id();

            // Store image
            if ($this->image) {
                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();
                $data['image'] = $this->image->storeAs('diet-plans', $filename, 'public');
            }

            // Inserting a row into the database
            DietPlan::create($data);

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
            if (!DietPlan::where('name', $data['name'])->exists()) {
                DietPlan::firstOrCreate([
                    'name' => $data['name'],
                    'slug' => $data['name'],
                    'diet_plan_type' => $data['name'],
                    'user_id' => 1,
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
        $this->authorize('diet-plan.delete');

        $row = DietPlan::findOrFail($id);

        // ✅ old image delete
        if ($row->image && Storage::disk('public')->exists($row->image)) {
            Storage::disk('public')->delete($row->image);
        }

        $row->whereKey($id)->withTrashed()->forceDelete();
        // DietPlan::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('diet-plan.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        $rows = DietPlan::withTrashed()->whereIn('id', $this->selected)->get();

        foreach($rows as $row) {
            if ($row->image && Storage::disk('public')->exists($row->image)) {
                Storage::disk('public')->delete($row->image);
            }
            $row->forceDelete();
        }

        // DietPlan::whereIn('id', $this->selected)->withTrashed()->forceDelete();
        // DietPlan::whereIn('id', $this->selected)->delete();

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

    protected function sanitize()
    {
        foreach ([
            'name',
            'slug',
            'description',
            'diet_plan_type',
            'color',
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function reset all fields value
    public function resetFields()
    {
        $this->reset(['search','name','slug','description','diet_plan_type','image','existingImage','color','status','csv','isEdit','editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('diet-plan.view');

        return view('livewire.admin.diet-plan-management.diet-plan-index');
    }
}
