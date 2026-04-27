<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class MealSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call([
            DietPlanSeeder::class,
            MealTypeSeeder::class,
            IngredientSeeder::class,
            MealDataSeeder::class,
        ]);
    }
}
