<?php
namespace App\Livewire\Frontend\HomePage\Traits;


use App\Models\Meal;
use App\Models\MealType;
use Livewire\Attributes\Computed;

trait Menus
{
    /*
    |--------------------------------------------------------------------------
    | Properties
    |--------------------------------------------------------------------------
    */

    public int $perPage = 4;
    public int $page = 1;

    public $meals = [];

    // Modal Data
    public $image;
    public $name;
    public $description;
    public $mealDietPlans;
    public $calories;
    public $protein;
    public $carbs;
    public $fat;
    public $fiber;
    public $ingredients = [];
    public $price;

    /*
    |--------------------------------------------------------------------------
    | Computed
    |--------------------------------------------------------------------------
    */

    #[Computed]
    public function mealTypes()
    {
        return MealType::get(['name']);
    }

    /*
    |--------------------------------------------------------------------------
    | Lifecycle
    |--------------------------------------------------------------------------
    */

    public function mount()
    {
        $this->loadInitial();
    }

    /*
    |--------------------------------------------------------------------------
    | Data Loading
    |--------------------------------------------------------------------------
    */

    public function loadInitial()
    {
        $meals = Meal::where('is_guest_meal', true)
            ->latest()
            ->paginate($this->perPage);

        $this->meals = $meals->items();
    }

    public function loadMore()
    {
        $this->page++;

        $meals = Meal::where('is_guest_meal', true)
            ->latest()
            ->paginate($this->perPage, ['*'], 'page', $this->page);

        $this->meals = array_merge($this->meals, $meals->items());
    }

    /*
    |--------------------------------------------------------------------------
    | Modal
    |--------------------------------------------------------------------------
    */

    public function show(int $id)
    {
        $menu = Meal::with('ingredients')->findOrFail($id);

        $this->image = $menu->image;
        $this->name = $menu->name;
        $this->mealDietPlans = $menu->dietPlans->pluck('name')->join(', ');
        $this->description = $menu->description;
        $this->calories = $menu->calories;
        $this->protein = $menu->protein;
        $this->carbs = $menu->carbs;
        $this->fat = $menu->fat;
        $this->fiber = $menu->fiber;
        $this->ingredients = $menu->ingredients;
        $this->price = $menu->price;

        $this->dispatch('open-modal');
    }
}
