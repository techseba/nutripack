<?php

namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use App\Models\AdditionalMeal;

trait AdditionalMeals
{
    public $hasBreakfast = false;
    public $breakfastMaxQuantity = 0;
    public $breakfastUnitPrice = 0;
    public $breakfastQuantity = 0;
    public $breakfastTotalPrice = 0;

    public $hasSalad = false;
    public $saladMaxQuantity = 0;
    public $saladUnitPrice = 0;
    public $saladQuantity = 0;
    public $saladTotalPrice = 0;

    public $totalAdditionalPrice = 0;

    public function loadAdditionalMealTypes(): void
    {
        $additionalMealTypes = AdditionalMeal::where('status','active')->orderBy('name')->pluck('name')->toArray();

        if (in_array('Breakfast', $additionalMealTypes)) {
            $this->hasBreakfast = true;

            $this->breakfastMaxQuantity = 5;

            $this->breakfastUnitPrice = 5;

            $this->breakfastTotalPrice = $this->breakfastUnitPrice * $this->breakfastQuantity;
        }

        if (in_array('Salad', $additionalMealTypes)) {
            $this->hasSalad = true;

            $this->saladMaxQuantity = 2;

            $this->saladUnitPrice = 50;

            $this->saladTotalPrice = $this->saladUnitPrice * $this->saladQuantity;
        }

        $this->totalAdditionalPrice = $this->breakfastTotalPrice + $this->saladTotalPrice;
    }
}
