<?php

namespace App\Livewire\Admin\MealTypeManagement;

use App\Models\MealType;
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

#[Title('Meal Types Management')]
class MealTypeIndex extends Component
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
    public string $subject = 'meal type';

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
    public $image; // new upload
    public $existingImage; // old image path
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
        return MealType::query()
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
        $mealType = MealType::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill($mealType->only([
            'name',
            'slug',
            'description',
            'status',
        ]));

        $this->existingImage = $mealType->image;
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

            $this->authorize('meal-type.edit');

            $mealType = MealType::findOrFail($this->editRow);

            // ✅ Always slugify
            $this->slug = Str::slug($this->slug);

            $data = $this->validate([
                'name'              => ['required','string','max:40'],
                'slug'              => [
                    'required',
                    'string',
                    'max:60',
                    Rule::unique('meal_types','slug')->ignore($mealType->id),
                ],
                'description'       => ['nullable','string'],
                'image'             => ['nullable','image','mimes:jpg,jpeg,png','max:256'],
                'status'            =>  ['required','in:active,inactive'],
            ]);

            // ✅ if new image uploaded
            if ($this->image) {

                if ($mealType->image && Storage::disk('public')->exists($mealType->image)) {
                    Storage::disk('public')->delete($mealType->image);
                }

                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();

                $data['image'] = $this->image->storeAs('meal-types', $filename, 'public');
            }else{
                unset($data['image']);
            }

            $mealType->update($data);

            $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');

        } else {

            $this->authorize('meal-type.create');

            // Checking form validation
            $data = $this->validate([
                'name'              =>  ['required','string','max:40'],
                'description'       =>  ['nullable','string'],
                'image'             =>  ['nullable','file','mimes:jpg,jpeg,png','max:256'],
            ]);

            $slug = Str::slug($this->name);
            $originalSlug = $slug;
            $count = 1;

            while (MealType::where('slug', $slug)->exists()) {
                $slug = $originalSlug . '-' . $count++;
            }
            $data['slug'] = $slug;

            $data['user_id'] = auth()->id();

            // Store image
            if ($this->image) {
                $filename = Str::slug($this->name) . '-' . time() . '.' . $this->image->extension();
                $data['image'] = $this->image->storeAs('meal-types', $filename, 'public');
            }

            // Inserting a row into the database
            MealType::create($data);

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
            if (!MealType::where('name', $data['name'])->exists()) {
                MealType::firstOrCreate([
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
        $this->authorize('meal-type.delete');

        $row = MealType::findOrFail($id);

        // ✅ old image delete
        if ($row->image && Storage::disk('public')->exists($row->image)) {
            Storage::disk('public')->delete($row->image);
        }

        $row->whereKey($id)->withTrashed()->forceDelete();
        // MealType::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('meal-type.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        $rows = MealType::withTrashed()->whereIn('id', $this->selected)->get();

        foreach($rows as $row) {
            if ($row->image && Storage::disk('public')->exists($row->image)) {
                Storage::disk('public')->delete($row->image);
            }
            $row->forceDelete();
        }

        // MealType::whereIn('id', $this->selected)->withTrashed()->forceDelete();
        // MealType::whereIn('id', $this->selected)->delete();

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
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function reset all fields value
    public function resetFields()
    {
        $this->reset(['search','name','slug','description','image','existingImage','status','csv','isEdit','editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('meal-type.view');

        return view('livewire.admin.meal-type-management.meal-type-index');
    }
}

