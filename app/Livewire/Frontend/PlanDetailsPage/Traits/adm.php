<?php
$this->mealTypes = collect($this->mealTypes ?? []);

        $breakfastTypeId = MealType::where('name', 'Breakfast')->value('id');

        if ($this->breakfastQuantity > 0) {
            // add if not exists
            if (! $this->mealTypes->contains($breakfastTypeId)) {
                $this->mealTypes = $this->mealTypes->push($breakfastTypeId)->unique()->values();
            }
        } else {
            // remove when quantity is zero
            $this->mealTypes = $this->mealTypes->reject(fn ($id) => $id == $breakfastTypeId)->values();
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
