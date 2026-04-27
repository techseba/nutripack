<?php

namespace Database\Seeders;

use App\Models\Meal;
use Illuminate\Database\Seeder;

class MealDataSeeder extends Seeder
{
    public function run(): void
    {
        $mealCount = 1;
        Meal::factory()->count($mealCount)->create();
    }
}
