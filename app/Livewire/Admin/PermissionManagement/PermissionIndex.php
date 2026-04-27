<?php

namespace App\Livewire\Admin\PermissionManagement;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Title('Permissions Management')]
class PermissionIndex extends Component
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
    public string $subject = 'permission';

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
        return Permission::query()
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
        $selectedTableRow = Permission::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill(['name' => $selectedTableRow->name]);

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
        $this->name = str($this->name)->trim()->toString();

        if ($this->isEdit) {

            $this->authorize('permission.edit');

            // Checking form validation
            $data = $this->validate([
                'name'  =>  ['required','string',Rule::unique('permissions','name')->ignore($this->editRow)]
            ]);

            // Selecting specific table row with specific ID
            $selectedTableRow = Permission::findOrFail($this->editRow);

            // Updating row into the database
            $selectedTableRow->update($data);

            // Notifying that a row has been successfully updated into the database
            $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');

        } else {

            $this->authorize('permission.create');

            // Checking form validation
            $data = $this->validate([
                'name'  =>  ['required','string',Rule::unique('permissions','name')]
            ]);

            // Inserting a row into the database
            Permission::create($data);

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
            if (!Permission::where('name', $data['name'])->exists()) {
                Permission::firstOrCreate([
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
        $this->authorize('permission.delete');

        Permission::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('permission.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        Permission::whereIn('id', $this->selected)->delete();

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
        $this->reset(['search','name','csv','isEdit','editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('permission.view');

        return view('livewire.admin.permission-management.permission-index');
    }
}
