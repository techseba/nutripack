<?php

namespace App\Livewire\Admin\MealManagement;


use App\Livewire\Admin\MealManagement\Traits\MealBulkActions;
use App\Livewire\Admin\MealManagement\Traits\MealDelete;
use App\Livewire\Admin\MealManagement\Traits\MealEdit;
use App\Livewire\Admin\MealManagement\Traits\MealHelpers;
use App\Livewire\Admin\MealManagement\Traits\MealImport;
use App\Livewire\Admin\MealManagement\Traits\MealSave;
use App\Livewire\Admin\MealManagement\Traits\SingleView;
use App\Models\DietPlan;
use App\Models\Ingredient;
use App\Models\Meal;
use App\Models\MealType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title('Meals Management')]
class MealIndex extends Component
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


    use SingleView;
    use MealEdit;
    use MealSave;
    use MealImport;
    use MealDelete;
    use MealBulkActions;
    use MealHelpers;

    /*
    |--------------------------------------------------------------------------
    | 2. Public State Properties
    |--------------------------------------------------------------------------
    */

    // ===== Page Meta =====
    public string $subject = 'meal';

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
    public $calories;
    public $protein;
    public $carbs;
    public $fat;
    public $fiber;
    public $price;
    public $status;
    public $user_id;
    public $meal_type_id;

    public $mealDietPlans = [];
    public $mealIngredients = [];

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
        return Meal::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name','like',"%{$this->search}%")
                    ->orWhere('slug','like',"%{$this->search}%");
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

    // This function will
    #[Computed]
    public function ingredients()
    {
        return Ingredient::latest()->get();
    }

    public function render()
    {
        $this->authorize('meal.view');

        return view('livewire.admin.meal-management.meal-index');
    }
}



