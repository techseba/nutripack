<?php

namespace App\Livewire\Admin\DayWiseMealManagement;

use App\Models\DayWiseMeal;
use App\Models\Meal;
use App\Models\MealType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title('Daywise Meals Management')]
class DayWiseMealIndex extends Component
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
    public string $subject = 'day wise meal';

    // ===== Filters =====
    public string $search = '';

    // ===== Table State =====
    public array $selected = [];
    public bool $selectAll = false;
    public int $perPage = 5;

    // ===== Form State =====

    public string $date;
    public ?int $mealTypeId = null;
    public ?int $mealId = null;
    public array $selectedMeals = [];


    public bool $isEdit = false;
    public ?int $editRow = null;


    /*
    |--------------------------------------------------------------------------
    | 3. Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->date = Carbon::now()->toDateString();
        $this->selected = [];
        $this->selectedMeals = [];
    }

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
        return DayWiseMeal::query()->with(['mealType','meal'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('date', 'like', "%{$this->search}%");
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
    public function mealTypes()
    {
        return MealType::get(["id", "name"]);
    }

    public function updatedMealTypeId(): void
    {
        $this->reset(['mealId', 'selectedMeals']);
    }


    #[Computed]
    public function meals()
    {
        if ($this->mealTypeId) {
            return Meal::where('meal_type_id', $this->mealTypeId)->orderBy('name', 'asc')->get();
        }
        return collect();
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    // This function is storing and updating data in a specific table.
    public function save()
    {
        $this->authorize('day-wise-meal.create');

        $this->validate([
            'date' => 'required|date',
            'mealTypeId' => 'required|integer|exists:meal_types,id',
            'selectedMeals' => 'required|array|min:1',
            'selectedMeals.*' => 'integer|distinct',
        ]);

        // Normalize submitted ids
        $submitted = array_values(array_map('intval', $this->selectedMeals));

        // 1) Get existing meal_ids for this date + meal_type BEFORE insert
        $before = DB::table('day_wise_meals')
            ->where('date', $this->date)
            ->where('meal_type_id', $this->mealTypeId)
            ->pluck('meal_id')
            ->map(fn($v) => (int) $v)
            ->toArray();

        // 2) Prepare rows and insertOrIgnore
        $now = now();
        $rows = [];
        foreach ($submitted as $mealId) {
            if ($mealId <= 0)
                continue;
            $rows[] = [
                'date' => $this->date,
                'meal_type_id' => $this->mealTypeId,
                'meal_id' => (int) $mealId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($rows)) {
            $this->dispatch('toast', message: 'No valid meals selected', type: 'error');
            return;
        }

        DB::table('day_wise_meals')->insertOrIgnore($rows);

        // 3) Get existing meal_ids AFTER insert
        $after = DB::table('day_wise_meals')
            ->where('date', $this->date)
            ->where('meal_type_id', $this->mealTypeId)
            ->pluck('meal_id')
            ->map(fn($v) => (int) $v)
            ->toArray();

        // 4) Compute inserted and skipped
        $inserted = array_values(array_diff($after, $before)); // নতুন ইনসার্ট হওয়া আইডি
        $skipped = array_values(array_intersect($before, $submitted)); // আগে থেকেই ছিল

        // Alternatively skipped = array_values(array_diff($submitted, $inserted));

        // 5) Prepare user-friendly messages (meal names optional)
        $mealNames = Meal::whereIn('id', array_unique(array_merge($inserted, $skipped)))
            ->pluck('name', 'id')
            ->toArray();

        $insertedList = array_map(fn($id) => ($mealNames[$id] ?? $id), $inserted);
        $skippedList = array_map(fn($id) => ($mealNames[$id] ?? $id), $skipped);

        // 6) Dispatch toast with summary
        $messages = [];
        if (!empty($insertedList)) {
            $messages[] = 'Inserted: ' . implode(', ', $insertedList);
        }
        if (!empty($skippedList)) {
            $messages[] = 'Skipped (already existed): ' . implode(', ', $skippedList);
        }

        $this->dispatch('toast', message: implode(' | ', $messages), type: 'success');

        // Reset and UI updates
        $this->resetFields();
        $this->refreshTable();
        $this->dispatch('close-modal');
    }

    // This function is deleted a single data in a specific table
    public function delete(int $id): void
    {
        $this->authorize('day-wise-meal.delete');

        DayWiseMeal::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();
        $this->dispatch('close-delete-modal');
    }

    public function deleteSelected(): void
    {
        $this->authorize('day-wise-meal.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No items selected!', type: 'warning');
            return;
        }

        DayWiseMeal::whereIn('id', $this->selected)->delete();

        $this->dispatch('toast', message: count($this->selected) . ' ' . ucfirst($this->subject) . ' deleted successfully!', type: 'success');

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
    protected function resetSelection(): void
    {
        $this->reset(['selected', 'selectAll']);
    }

    protected function refreshTable(): void
    {
        unset($this->rows, $this->rowsQuery);
    }

    public function resetFields(): void
    {
        $this->reset([
            'search',
            'mealTypeId',
            'mealId',
            'selectedMeals',
            'isEdit',
            'editRow'
        ]);

        $this->resetValidation();
        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('day-wise-meal.view');
        return view('livewire.admin.day-wise-meal-management.day-wise-meal-index');
    }
}
