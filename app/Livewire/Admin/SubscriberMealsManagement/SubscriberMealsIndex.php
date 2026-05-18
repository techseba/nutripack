<?php

namespace App\Livewire\Admin\SubscriberMealsManagement;

use App\Models\DietPlan;
use App\Models\Meal;
use App\Models\MealType;
use App\Models\SubscriberMealSelection;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title('Subscriber Meals Management')]
class SubscriberMealsIndex extends Component
{
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */

    // ===== For Livewire Pagination =====
    use WithPagination, WithoutUrlPagination;
    use AuthorizesRequests;

    /*
    |--------------------------------------------------------------------------
    | 2. Public State Properties
    |--------------------------------------------------------------------------
    */

    // ===== Page Meta =====
    public string $subject = 'subscriber meal';

    // ===== Filters =====
    public string $search = '';

    // ===== Table State =====
    public array $selected = [];
    public bool $selectAll = false;
    public int $perPage = 5;

    // ===== Form State =====
    public bool $isEdit = false;
    public ?int $editRow = null;

    public string $date = '';
    public $subscriberId;
    public $subscriberName;
    public $mealTypeId;
    public $mealId;


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
        return SubscriberMealSelection::query()
            ->with(['subscriber.user', 'mealType', 'meal'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('date','like',"%{$this->search}%");
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
    public function mealTypes()
    {
        return MealType::latest()->get(["id","name"]);
    }

    public function updatedMealTypeId($value)
    {
        $this->reset([
            'mealId',
        ]);
    }

    #[Computed]
    public function meals()
    {
        if ($this->mealTypeId) {
            return Meal::where('meal_type_id', $this->mealTypeId)->latest()->get(["id","name"]);
        }

        return collect();
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
        $selectedTableRow = SubscriberMealSelection::with(['subscriber.user', 'mealType', 'meal'])->findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->date = Carbon::parse($selectedTableRow->date)->format('Y-m-d');
        $this->subscriberId = $selectedTableRow->subscriber->id;
        $this->subscriberName = $selectedTableRow->subscriber->user->name;
        $this->mealTypeId = $selectedTableRow->mealType->id;
        $this->mealId = $selectedTableRow->meal->id;

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

            $SubscriberMealSelection = SubscriberMealSelection::findOrFail($this->editRow);

            $this->validate([
                'date'              => ['required','string','max:40'],
                'subscriberId'              => ['required','string','max:40'],
                'mealTypeId'              => ['required','string','max:40'],
                'mealId'              => ['required','string','max:40'],
            ]);

            $data = [
                'subscriber_id' => $this->subscriberId,
                'date'  => $this->date,
                'meal_type_id'  => $this->mealTypeId,
                'meal_id'  => $this->mealId,
            ];



            $SubscriberMealSelection->update($data);

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

    // This function is deleted a single data in a specific table
    public function delete(int $id)
    {
        $this->authorize('diet-plan.delete');

        SubscriberMealSelection::whereKey($id)->delete();

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

        SubscriberMealSelection::whereIn('id', $this->selected)->delete();

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
            'date',
            'subscriberId',
            'mealTypeId',
            'mealId',
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function reset all fields value
    public function resetFields()
    {
        $this->reset(['search','date','subscriberId','subscriberName','mealTypeId','mealId','isEdit','editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('diet-plan.view');

        return view('livewire.admin.subscriber-meals-management.subscriber-meals-index');
    }
}

