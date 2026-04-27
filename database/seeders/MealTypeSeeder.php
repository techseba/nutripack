<?php

namespace Database\Seeders;

use App\Models\MealType;
use Illuminate\Database\Seeder;

class MealTypeSeeder extends Seeder
{
    public function run(): void
    {
        MealType::insert([
            [
                'user_id' => 1,
                'name' => 'Breakfast',
                'slug' => 'breakfast',
                'description' => 'Protein-rich start to your day',
            ],
            [
                'user_id' => 1,
                'name' => 'Lunch',
                'slug' => 'lunch',
                'description' => 'Energizing midday nutrition',
            ],
            [
                'user_id' => 1,
                'name' => 'Dinner',
                'slug' => 'dinner',
                'description' => 'Light yet satisfying evening meal',
            ],
            [
                'user_id' => 1,
                'name' => 'Salad',
                'slug' => 'salad',
                'description' => 'Fruit & nut packs included',
            ],
            [
                'user_id' => 1,
                'name' => 'Snacks',
                'slug' => 'snacks',
                'description' => 'Fruit & nut packs included',
            ],
        ]);
    }
}
