<?php

namespace App\Livewire\Admin\AdditionalMealManagement;

use App\Models\AdditionalMeal;
use App\Models\DietPlan;
use App\Models\MealType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title('Additional Meals Management')]
class AdditionalMealIndex extends Component
{
    use AuthorizesRequests;
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */

    // ===== For Livewire Pagination =====
    use WithoutUrlPagination, WithPagination;

    /*
    |--------------------------------------------------------------------------
    | 2. Public State Properties
    |--------------------------------------------------------------------------
    */

    // ===== Page Meta =====
    public string $subject = 'additional meal';

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

    public string $description = '';

    public string $unit_price;

    public int $max_quantity = 2;

    public $status;

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
        return AdditionalMeal::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', "%{$this->search}%")
                        ->orWhere('created_at', 'like', "%{$this->search}%");
                });
            })->latest();
    }

    // This function will
    #[Computed]
    public function rows()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    #[Computed]
    public function meal_types()
    {
        return MealType::latest()->get();
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
        $additionalMeal = AdditionalMeal::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill($additionalMeal->only([
            'name',
            'description',
            'unit_price',
            'max_quantity',
            'status',
        ]));

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

        if ($this->isEdit) {

            $this->authorize('diet-plan.edit');

            $additionalMeal = AdditionalMeal::findOrFail($this->editRow);

            $data = $this->validate([
                'name' => [
                    'required',
                    'string',
                    'max:40',
                    Rule::unique('additional_meals', 'name')->whereNull('deleted_at')->ignore($additionalMeal->id),
                ],
                'description' => ['nullable', 'string'],
                'unit_price' => ['required', 'decimal:0,2'],
                'max_quantity' => ['required', 'numeric'],
                'status' => ['required', 'in:active,inactive'],
            ]);

            $additionalMeal->update($data);

            $this->dispatch('toast', message: ucfirst($this->subject).' updated successfully', type: 'success');

        } else {

            $this->authorize('diet-plan.create');

            // Checking form validation
            $data = $this->validate([
                'name' => ['required', 'string', 'max:40', Rule::unique('additional_meals', 'name')],
                'description' => ['nullable', 'string'],
                'unit_price' => ['required', 'decimal:0,2'],
                'max_quantity' => ['required', 'numeric'],
            ]);

            $data['user_id'] = auth()->id();

            // Inserting a row into the database
            AdditionalMeal::create($data);

            // Notifying that a row has been successfully inserted into the database
            $this->dispatch('toast', message: ucfirst($this->subject).' created successfully', type: 'success');
        }

        // Resetting form fields
        $this->resetFields();

        // Refreshing the table
        $this->refreshTable();

        // Closing the form modal
        $this->dispatch('close-modal');
    }

    // This function is deleted a single data in a specific table
    public function delete(int $id)
    {
        $this->authorize('diet-plan.delete');

        $row = AdditionalMeal::findOrFail($id);

        $row->whereKey($id)->withTrashed()->forceDelete();
        // DietPlan::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject).' deleted successfully', type: 'success');

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

        $rows = AdditionalMeal::withTrashed()->whereIn('id', $this->selected)->get();

        foreach ($rows as $row) {
            $row->forceDelete();
        }

        // DietPlan::whereIn('id', $this->selected)->withTrashed()->forceDelete();
        // DietPlan::whereIn('id', $this->selected)->delete();

        $this->dispatch('toast', message: count($this->selected).' '.ucfirst($this->subject).' deleted successfully!', type: 'success');

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
        $this->reset(['search', 'name', 'description', 'unit_price', 'max_quantity', 'status', 'isEdit', 'editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('diet-plan.view');

        return view('livewire.admin.additional-meal-management.additional-meal-index');
    }
}
