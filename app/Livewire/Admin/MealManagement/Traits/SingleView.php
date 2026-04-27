<?php
namespace App\Livewire\Admin\MealManagement\Traits;

use App\Models\Meal;

trait SingleView
{
    public function singleView(int $id)
    {
        // Selecting specific table row with specific ID
        $meal = Meal::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->name         = $meal->name;
        $this->description  = $meal->description;
        $this->calories     = $meal->calories;
        $this->protein      = $meal->protein;
        $this->carbs        = $meal->fat;
        $this->fat          = $meal->fat;
        $this->fiber        = $meal->fiber;

        // Opening the form modal
        $this->dispatch('open-view-modal');
    }
}
