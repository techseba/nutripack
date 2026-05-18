<?php

namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use App\Models\AdditionalMeal;
use App\Models\MealType;
use Illuminate\Support\Collection;

trait AdditionalMeals
{
    // ===== state properties =====
    public $hasBreakfast;
    public $breakfastMaxQuantity;
    public $breakfastUnitPrice;
    public $breakfastQuantity = 0;
    public $breakfastTotalPrice = 0;

    public $hasLunch;
    public $lunchMaxQuantity;
    public $lunchUnitPrice;
    public $lunchQuantity = 0;
    public $lunchTotalPrice = 0;

    public $hasDinner;
    public $dinnerMaxQuantity;
    public $dinnerUnitPrice;
    public $dinnerQuantity = 0;
    public $dinnerTotalPrice = 0;

    public $hasSalad;
    public $saladMaxQuantity;
    public $saladUnitPrice;
    public $saladQuantity = 0;
    public $saladTotalPrice = 0;

    public $hasSnacks;
    public $snacksMaxQuantity;
    public $snacksUnitPrice;
    public $snacksQuantity = 0;
    public $snacksTotalPrice = 0;

    /** @var array|Collection ids of selected meal types */
    public $mealTypes = [];

    /** @var float */
    public $totalAdditionalPrice = 0;

    /** internal cache: ['Breakfast' => id, ...] */
    protected array $mealTypeIds = [];

    /**
     * Load additional meal types from DB and initialize properties.
     * Call this from your component's mount(): $this->loadAdditionalMealTypes();
     */
    public function loadAdditionalMealTypes(): void
    {
        // load active additional meals keyed by name
        $additionalMealTypes = AdditionalMeal::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->keyBy('name')
            ->toArray();

        // cache meal type ids for quick lookup (single DB call)
        $this->mealTypeIds = MealType::whereIn('name', ['Breakfast','Lunch','Dinner','Salad','Snacks'])
            ->pluck('id', 'name')
            ->toArray();

        // reset flags and totals (keep quantities as they may be prefilled)
        $this->hasBreakfast = false;
        $this->breakfastMaxQuantity = 0;
        $this->breakfastUnitPrice = 0;
        $this->breakfastTotalPrice = 0;

        $this->hasLunch = false;
        $this->lunchMaxQuantity = 0;
        $this->lunchUnitPrice = 0;
        $this->lunchTotalPrice = 0;

        $this->hasDinner = false;
        $this->dinnerMaxQuantity = 0;
        $this->dinnerUnitPrice = 0;
        $this->dinnerTotalPrice = 0;

        $this->hasSalad = false;
        $this->saladMaxQuantity = 0;
        $this->saladUnitPrice = 0;
        $this->saladTotalPrice = 0;

        $this->hasSnacks = false;
        $this->snacksMaxQuantity = 0;
        $this->snacksUnitPrice = 0;
        $this->snacksTotalPrice = 0;

        $this->totalAdditionalPrice = 0;

        // set flags & unit prices from DB if available
        if (isset($additionalMealTypes['Breakfast'])) {
            $this->hasBreakfast = true;
            $this->breakfastMaxQuantity = (int) ($additionalMealTypes['Breakfast']['max_quantity'] ?? 0);
            $this->breakfastUnitPrice = (float) ($additionalMealTypes['Breakfast']['unit_price'] ?? 0);
        }

        if (isset($additionalMealTypes['Lunch'])) {
            $this->hasLunch = true;
            $this->lunchMaxQuantity = (int) ($additionalMealTypes['Lunch']['max_quantity'] ?? 0);
            $this->lunchUnitPrice = (float) ($additionalMealTypes['Lunch']['unit_price'] ?? 0);
        }

        if (isset($additionalMealTypes['Dinner'])) {
            $this->hasDinner = true;
            $this->dinnerMaxQuantity = (int) ($additionalMealTypes['Dinner']['max_quantity'] ?? 0);
            $this->dinnerUnitPrice = (float) ($additionalMealTypes['Dinner']['unit_price'] ?? 0);
        }

        if (isset($additionalMealTypes['Salad'])) {
            $this->hasSalad = true;
            $this->saladMaxQuantity = (int) ($additionalMealTypes['Salad']['max_quantity'] ?? 0);
            $this->saladUnitPrice = (float) ($additionalMealTypes['Salad']['unit_price'] ?? 0);
        }

        if (isset($additionalMealTypes['Snacks'])) {
            $this->hasSnacks = true;
            $this->snacksMaxQuantity = (int) ($additionalMealTypes['Snacks']['max_quantity'] ?? 0);
            $this->snacksUnitPrice = (float) ($additionalMealTypes['Snacks']['unit_price'] ?? 0);
        }

        // ensure mealTypes is a collection for operations
        $this->mealTypes = collect($this->mealTypes ?? []);

        // sync mealTypes according to current quantities
        $this->syncAllMealTypes();

        // update totals
        $this->updateTotals();

        // finally keep mealTypes as plain array for Livewire serialization
        $this->mealTypes = collect($this->mealTypes)->unique()->values()->all();
    }

    /**
     * Sync all known meal types according to current quantity properties.
     */
    protected function syncAllMealTypes(): void
    {
        $map = [
            'breakfastQuantity' => 'Breakfast',
            'lunchQuantity'     => 'Lunch',
            'dinnerQuantity'    => 'Dinner',
            'saladQuantity'     => 'Salad',
            'snacksQuantity'    => 'Snacks',
        ];

        foreach ($map as $prop => $typeName) {
            $qty = (int) ($this->{$prop} ?? 0);
            $typeId = $this->mealTypeIds[$typeName] ?? null;
            if ($typeId) {
                $this->syncMealTypeById($typeId, $qty);
            }
        }
    }

    /**
     * Add or remove a meal type id based on quantity.
     */
    protected function syncMealTypeById(int $typeId, int $quantity): void
    {
        $this->mealTypes = collect($this->mealTypes ?? []);

        if ($quantity > 0) {
            if (! $this->mealTypes->contains($typeId)) {
                $this->mealTypes = $this->mealTypes->push($typeId)->unique()->values();
            }
        } else {
            $this->mealTypes = $this->mealTypes->reject(fn ($id) => $id == $typeId)->values();
        }

        // keep as collection until final conversion
        $this->mealTypes = $this->mealTypes->unique()->values();
    }

    /**
     * Update per-type totals and overall total.
     */
    protected function updateTotals(): void
    {
        $this->breakfastTotalPrice = $this->breakfastUnitPrice * (int) $this->breakfastQuantity;
        $this->lunchTotalPrice     = $this->lunchUnitPrice * (int) $this->lunchQuantity;
        $this->dinnerTotalPrice    = $this->dinnerUnitPrice * (int) $this->dinnerQuantity;
        $this->saladTotalPrice     = $this->saladUnitPrice * (int) $this->saladQuantity;
        $this->snacksTotalPrice    = $this->snacksUnitPrice * (int) $this->snacksQuantity;

        $this->totalAdditionalPrice = $this->breakfastTotalPrice
            + $this->lunchTotalPrice
            + $this->dinnerTotalPrice
            + $this->saladTotalPrice
            + $this->snacksTotalPrice;
    }

    // Livewire updated hooks — automatically called when the bound property changes
    public function updatedBreakfastQuantity($value)
    {
        $this->onQuantityUpdated('Breakfast', (int) $value);
    }

    public function updatedLunchQuantity($value)
    {
        $this->onQuantityUpdated('Lunch', (int) $value);
    }

    public function updatedDinnerQuantity($value)
    {
        $this->onQuantityUpdated('Dinner', (int) $value);
    }

    public function updatedSaladQuantity($value)
    {
        $this->onQuantityUpdated('Salad', (int) $value);
    }

    public function updatedSnacksQuantity($value)
    {
        $this->onQuantityUpdated('Snacks', (int) $value);
    }

    protected function onQuantityUpdated(string $typeName, int $qty): void
    {
        $typeId = $this->mealTypeIds[$typeName] ?? null;
        if ($typeId) {
            $this->syncMealTypeById($typeId, $qty);
        }

        $this->updateTotals();

        // ensure mealTypes stored as array for Livewire serialization
        $this->mealTypes = collect($this->mealTypes)->unique()->values()->all();
    }
}
