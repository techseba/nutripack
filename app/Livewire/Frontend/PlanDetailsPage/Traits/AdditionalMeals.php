<?php

namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use App\Models\AdditionalMeal;

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

    public $hasSalad;
    public $saladMaxQuantity;
    public $saladUnitPrice;
    public $saladQuantity = 0;
    public $saladTotalPrice;

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

        $this->hasSalad = false;
        $this->saladMaxQuantity = 0;
        $this->saladUnitPrice = 0;
        $this->saladTotalPrice = 0;

        $this->totalAdditionalPrice = 0;

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
            // breakfastQuantity আগেই mount() এ ইনিশিয়ালাইজ করা আছে ধরে নিচ্ছি
            $this->lunchTotalPrice = $this->lunchUnitPrice * (int) $this->lunchQuantity;
        }

        // Salad সেট করা (DB থেকে)
        if (isset($additionalMealTypes['Salad'])) {
            $this->hasSalad = true;
            $this->saladMaxQuantity = (int) ($additionalMealTypes['Salad']['max_quantity'] ?? 0);
            $this->saladUnitPrice = (float) ($additionalMealTypes['Salad']['unit_price'] ?? 0);
            $this->saladTotalPrice = $this->saladUnitPrice * (int) $this->saladQuantity;
        }

        // মোট আপডেট
        $this->totalAdditionalPrice = $this->breakfastTotalPrice + $this->lunchTotalPrice + $this->saladTotalPrice;
    }
}
