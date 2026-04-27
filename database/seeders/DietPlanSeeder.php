<?php

namespace Database\Seeders;

use App\Models\DietPlan;
use Illuminate\Database\Seeder;

class DietPlanSeeder extends Seeder
{
    public function run(): void
    {
        DietPlan::insert([
            [
                'user_id' => 1,
                'name' => 'Balanced',
                'slug' => 'balanced',
                'description' => 'A perfect mix of all nutrients for general health and energy.',
                'diet_plan_type' => 'balanced',
                'color' => '#00bc7d',
            ],
            [
                'user_id' => 1,
                'name' => 'Low carb',
                'slug' => 'low-carb',
                'description' => 'Low-calorie, high-fiber meals designed for steady fat loss.',
                'diet_plan_type' => 'low_carb',
                'color' => '#00bc7d',
            ],
            [
                'user_id' => 1,
                'name' => 'Keto Friendly',
                'slug' => 'keto-friendly',
                'description' => 'Ultra-low carb, high healthy fats to maintain ketosis.',
                'diet_plan_type' => 'keto_friendly',
                'color' => '#00bc7d',
            ],
            [
                'user_id' => 1,
                'name' => 'Muscle Gain',
                'slug' => 'muscle-gain',
                'description' => 'High-protein, complex carb meals to fuel growth and recovery.',
                'diet_plan_type' => 'muscle_gain',
                'color' => '#00bc7d',
            ],
        ]);
    }
}
