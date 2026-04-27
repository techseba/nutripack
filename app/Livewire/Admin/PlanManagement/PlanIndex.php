<?php

namespace App\Livewire\Admin\PlanManagement;

use App\Models\DietPlan;
use App\Models\Plan;
use App\Models\PlanCategory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title('Plans Management')]
class PlanIndex extends Component
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
    public string $subject = 'plan';

    // ===== Filters =====
    public string $search = '';

    // ===== Table State =====
    public array $selected = [];
    public bool $selectAll = false;
    public int $perPage = 5;

    // ===== Form State =====
    public bool $isEdit = false;
    public ?int $editRow = null;

    public $diet_plan_id;
    public $plan_category_id;

    public $min_calories;
    public $max_calories;
    public $protein;
    public $carbs;
    public $fat;
    public $fiber;
    public $days_of_week;
    public $price;

    public $user_id;
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
        return Plan::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('id','like',"%{$this->search}%")
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

    #[Computed]
    public function diet_plans()
    {
        return DietPlan::latest()->get();
    }

    public function updatedDietPlanId($value)
    {
        // DietPlan পরিবর্তন হলে আগের ভ্যালুগুলো reset করে দাও
        $this->reset([
            'plan_category_id',
            'min_calories',
            'max_calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
        ]);
    }

    #[Computed]
    public function plan_categories()
    {
        if ($this->diet_plan_id) {
            return PlanCategory::where('diet_plan_id', $this->diet_plan_id)->latest()->get();
        }

        return collect(); // খালি collection

        $this->updatePlanCategory();
    }

    // যখনই plan_category_id পরিবর্তন হবে
    public function updatedPlanCategoryId($value)
    {
        if ($value) {
            $category = PlanCategory::find($value);

            if ($category) {
                $this->min_calories = $category->min_calories;
                $this->max_calories = $category->max_calories;
                $this->protein      = $category->protein;
                $this->carbs        = $category->carbs;
                $this->fat          = $category->fat;
                $this->fiber        = $category->fiber;
            }
        }
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    public function singleView(int $id)
    {
        // Selecting specific table row with specific ID
        $plan = Plan::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->min_calories     = $plan->min_calories;
        $this->max_calories     = $plan->max_calories;
        $this->protein      = $plan->protein;
        $this->carbs        = $plan->fat;
        $this->fat          = $plan->fat;
        $this->fiber        = $plan->fiber;

        // Opening the form modal
        $this->dispatch('open-view-modal');
    }

    // This function open the edit modal
    public function edit(int $id)
    {
        // Selecting specific table row with specific ID
        $selectedTableRow = Plan::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill($selectedTableRow->only([
            'diet_plan_id',
            'plan_category_id',
            'min_calories',
            'max_calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
            'days_of_week',
            'price',
            'status',
        ]));

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
            'diet_plan_id',
            'plan_category_id',
            'min_calories',
            'max_calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
            'days_of_week',
            'price',
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function is storing and updating data in a specific table.
    public function save()
    {
        // Sanitizing form data
        $this->sanitize();

        if ($this->isEdit) {

            $this->authorize('plan.edit');

            $data = $this->validate([
                'diet_plan_id'        => ['required','numeric'],
                'plan_category_id'    => ['required','numeric'],
                'min_calories'        => ['required','numeric'],
                'max_calories'        => ['required','numeric'],
                'protein'             => ['required','numeric'],
                'carbs'               => ['required','numeric'],
                'fat'                 => ['required','numeric'],
                'fiber'               => ['nullable','numeric'],
                'days_of_week'        => ['nullable','numeric'],
                'price'               => ['required','numeric'],

                'status'         =>  ['required','in:active,inactive'],
            ]);

            $data['user_id'] = auth()->id();

            // Let $this->editRow be the ID of the edited record.
            $selected_plan = Plan::where('plan_category_id', $this->plan_category_id)
                ->where('days_of_week', $this->days_of_week)
                ->where('id', '!=', $this->editRow) // I'm breaking my own record.
                ->first();

            if ($selected_plan) {
                // The same combination was found in a unique record.
                $this->dispatch('toast', message: 'please select another ' . ucfirst($this->subject) . ' days', type: 'error');
            } else {

                $plan = Plan::findOrFail($this->editRow);
                $plan->update($data);
                $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');
            }

        } else {

            $this->authorize('plan.create');

            // Checking form validation
            $data = $this->validate([
                'diet_plan_id'        => ['required','numeric'],
                'plan_category_id'    => ['required','numeric'],
                'min_calories'        => ['required','numeric'],
                'max_calories'        => ['required','numeric'],
                'protein'             => ['required','numeric'],
                'carbs'               => ['required','numeric'],
                'fat'                 => ['required','numeric'],
                'fiber'               => ['nullable','numeric'],
                'days_of_week'        => ['nullable','numeric'],
                'price'               => ['required','numeric'],
            ]);

            $data['user_id'] = auth()->id();

            $selected_plan = Plan::where('plan_category_id', $this->plan_category_id)->where('days_of_week', $this->days_of_week)->get()->first();

            if($selected_plan){
                // Notifying that a row has been successfully inserted into the database
                $this->dispatch('toast', message: 'please select another ' . ucfirst($this->subject) . ' days', type: 'error');
            } else{
                // Inserting a row into the database
                Plan::create($data);

                // Notifying that a row has been successfully inserted into the database
                $this->dispatch('toast', message: ucfirst($this->subject) . ' created successfully', type: 'success');
            }
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
            if (!Plan::where('name', $data['name'])->exists()) {
                Plan::firstOrCreate([
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
        $this->authorize('plan.delete');

        Plan::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('plan.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        Plan::whereIn('id', $this->selected)->delete();

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
            'diet_plan_id',
            'plan_category_id',
            'min_calories',
            'max_calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
            'days_of_week',
            'price',
            'status',

            'csv',
            'isEdit',
            'editRow'
            ]);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('plan.view');

        return view('livewire.admin.plan-management.plan-index');
    }
}
