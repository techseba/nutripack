<?php

namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use App\Models\AdditionalMeal;
use App\Models\MealType;

trait AdditionalMeals
{
    public $hasBreakfast;
    public $breakfastMaxQuantity;
    public $breakfastUnitPrice;
    public $breakfastQuantity = 0;
    public $breakfastTotalPrice;


    public $hasLunch;
    public $lunchMaxQuantity;
    public $lunchUnitPrice;
    public $lunchQuantity = 0;
    public $lunchTotalPrice;


    public $hasDinner;
    public $dinnerMaxQuantity;
    public $dinnerUnitPrice;
    public $dinnerQuantity = 0;
    public $dinnerTotalPrice;


    public $hasSalad;
    public $saladMaxQuantity;
    public $saladUnitPrice;
    public $saladQuantity = 0;
    public $saladTotalPrice;


    public $hasSnacks;
    public $snacksMaxQuantity;
    public $snacksUnitPrice;
    public $snacksQuantity = 0;
    public $snacksTotalPrice;

    public $mealTypes = [];

    public $totalAdditionalPrice;

    public function loadAdditionalMealTypes(): void
    {
        // DB থেকে active meals কে name দিয়ে key করে অ্যারে হিসেবে নিন
        $additionalMealTypes = AdditionalMeal::where('status', 'active')
            ->orderBy('name')
            ->get()
            ->keyBy('name')
            ->toArray();

        // defaults / reset
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

        $this->mealTypes = collect($this->mealTypes ?? []);

        if ($this->breakfastQuantity > 0) {
            $this->mealTypes = $this->mealTypes->merge(
                MealType::where('name', 'Breakfast')->pluck('id')
            );
        }

        if ($this->lunchQuantity > 0) {
            $this->mealTypes = $this->mealTypes->merge(
                MealType::where('name', 'Lunch')->pluck('id')
            );
        }

        if ($this->dinnerQuantity > 0) {
            $this->mealTypes = $this->mealTypes->merge(
                MealType::where('name', 'Dinner')->pluck('id')
            );
        }

        if ($this->saladQuantity > 0) {
            $this->mealTypes = $this->mealTypes->merge(
                MealType::where('name', 'Salad')->pluck('id')
            );
        }

        if ($this->snacksQuantity > 0) {
            $this->mealTypes = $this->mealTypes->merge(
                MealType::where('name', 'Snacks')->pluck('id')
            );
        }

        $this->mealTypes = $this->mealTypes->unique()->values()->all();

        // Breakfast সেট করা (DB থেকে)
        if (isset($additionalMealTypes['Breakfast'])) {
            $this->hasBreakfast = true;
            $this->breakfastMaxQuantity = (int) ($additionalMealTypes['Breakfast']['max_quantity'] ?? 0);
            $this->breakfastUnitPrice = (float) ($additionalMealTypes['Breakfast']['unit_price'] ?? 0);
            // breakfastQuantity আগেই mount() এ ইনিশিয়ালাইজ করা আছে ধরে নিচ্ছি
            $this->breakfastTotalPrice = $this->breakfastUnitPrice * (int) $this->breakfastQuantity;
        }

        // Lunch সেট করা (DB থেকে)
        if (isset($additionalMealTypes['Lunch'])) {
            $this->hasLunch = true;
            $this->lunchMaxQuantity = (int) ($additionalMealTypes['Lunch']['max_quantity'] ?? 0);
            $this->lunchUnitPrice = (float) ($additionalMealTypes['Lunch']['unit_price'] ?? 0);
            $this->lunchTotalPrice = $this->lunchUnitPrice * (int) $this->lunchQuantity;
        }

        // Dinner সেট করা (DB থেকে)
        if (isset($additionalMealTypes['Dinner'])) {
            $this->hasDinner = true;
            $this->dinnerMaxQuantity = (int) ($additionalMealTypes['Dinner']['max_quantity'] ?? 0);
            $this->dinnerUnitPrice = (float) ($additionalMealTypes['Dinner']['unit_price'] ?? 0);
            $this->dinnerTotalPrice = $this->dinnerUnitPrice * (int) $this->dinnerQuantity;
        }

        // Salad সেট করা (DB থেকে)
        if (isset($additionalMealTypes['Salad'])) {
            $this->hasSalad = true;
            $this->saladMaxQuantity = (int) ($additionalMealTypes['Salad']['max_quantity'] ?? 0);
            $this->saladUnitPrice = (float) ($additionalMealTypes['Salad']['unit_price'] ?? 0);
            $this->saladTotalPrice = $this->saladUnitPrice * (int) $this->saladQuantity;
        }

        // Snacks সেট করা (DB থেকে)
        if (isset($additionalMealTypes['Snacks'])) {
            $this->hasSnacks = true;
            $this->snacksMaxQuantity = (int) ($additionalMealTypes['Snacks']['max_quantity'] ?? 0);
            $this->snacksUnitPrice = (float) ($additionalMealTypes['Snacks']['unit_price'] ?? 0);
            $this->snacksTotalPrice = $this->snacksUnitPrice * (int) $this->snacksQuantity;
        }

        // মোট আপডেট
        $this->totalAdditionalPrice = $this->breakfastTotalPrice + $this->lunchTotalPrice + $this->dinnerTotalPrice + $this->saladTotalPrice + $this->snacksTotalPrice;
    }
}
