<?php

namespace Database\Factories;

use App\Models\MealType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MealFactory extends Factory
{
    protected $model = \App\Models\Meal::class;

    public function definition(): array
    {
        $name = $this->faker->words(2, true);
        return [
            'user_id' => 1,
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => '',
            'meal_type_id' => MealType::inRandomOrder()->first()->id ?? 1,
            'calories' => $this->faker->numberBetween(100, 800),
            'protein' => $this->faker->numberBetween(5, 50),
            'carbs' => $this->faker->numberBetween(0, 100),
            'fat' => $this->faker->numberBetween(0, 50),
            'fiber' => $this->faker->numberBetween(0, 20),
            'price' => $this->faker->numberBetween(50, 500),
            'status' => 'active',
        ];
    }
}
