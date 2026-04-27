<?php

namespace Database\Seeders;

use App\Models\Ingredient;
use Illuminate\Database\Seeder;

class IngredientSeeder extends Seeder
{
    public function run(): void
    {
        Ingredient::insert([
            [
                'user_id' => 1,
                'name' => 'Chicken Breast',
                'slug' => 'chicken-breast',
                'description' => 'Healthy meal healthy calories',
            ],
            [
                'user_id' => 1,
                'name' => 'Eggs',
                'slug' => 'eggs',
                'description' => 'Healthy meal healthy calories',
            ],
            [
                'user_id' => 1,
                'name' => 'Rice',
                'slug' => 'rice',
                'description' => 'Healthy meal healthy calories',
            ],
            [
                'user_id' => 1,
                'name' => 'Milk',
                'slug' => 'milk',
                'description' => 'Healthy meal healthy calories',
            ],
            [
                'user_id' => 1,
                'name' => 'Almonds',
                'slug' => 'almonds',
                'description' => '',
            ],
            [
                'user_id' => 1,
                'name' => 'Spinach',
                'slug' => 'spinach',
                'description' => 'Healthy meal healthy calories',
            ],
            [
                'user_id' => 1,
                'name' => 'Salmon',
                'slug' => 'salmon',
                'description' => 'Healthy meal healthy calories',
            ],
            [
                'user_id' => 1,
                'name' => 'Oats',
                'slug' => 'oats',
                'description' => '',
            ],
            [
                'user_id' => 1,
                'name' => 'Broccoli',
                'slug' => 'broccoli',
                'description' => '',
            ],
            [
                'user_id' => 1,
                'name' => 'Quinoa',
                'slug' => 'quinoa',
                'description' => '',
            ],
        ]);
    }
}
