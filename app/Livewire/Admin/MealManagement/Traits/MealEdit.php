<?php
namespace App\Livewire\Admin\MealManagement\Traits;

use App\Models\Meal;

trait MealEdit
{
    public function edit(int $id)
    {
        $this->authorize('meal.edit');

        // Selecting specific table row with specific ID
        $meal = Meal::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill($meal->only([
            'name',
            'slug',
            'description',
            'calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
            'price',
            'status',
            'meal_type_id'
        ]));

        $this->existingImage = $meal->image;
        $this->image = null;

        $this->mealDietPlans = $meal
        ->dietPlans
        ->pluck('id')
        ->toArray();

        $this->mealIngredients = $meal
        ->ingredients
        ->pluck('id')
        ->toArray();

        // Changing value to isEdit property
        $this->isEdit = true;

        // Assigning value to edit Row
        $this->editRow = $id;

        // Opening the form modal
        $this->dispatch('open-modal');
    }
}
