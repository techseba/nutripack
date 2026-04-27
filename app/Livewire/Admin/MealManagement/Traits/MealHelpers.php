<?php

namespace App\Livewire\Admin\MealManagement\Traits;

trait MealHelpers
{
    // This method reset bulk selected property
    protected function resetSelection()
    {
        $this->reset(['selected', 'selectAll']);
    }

    protected function refreshTable()
    {
        unset($this->rows, $this->rowsQuery);
    }

    protected function sanitize()
    {
        foreach ([
            'name',
            'slug',
            'description',
            'calories',
            'protein',
            'carbs',
            'fat',
            'fiber',
            'price'
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function reset all fields value
    public function resetFields()
    {
        $this->reset([
                'search',
                'perPage',
                'isEdit',
                'editRow',
                'name',
                'slug',
                'description',
                'image',
                'existingImage',
                'calories',
                'protein',
                'carbs',
                'fat',
                'fiber',
                'price',
                'status',
                'user_id',
                'meal_type_id',
                'mealDietPlans',
                'mealIngredients',
                'csv',
            ]);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }
}
