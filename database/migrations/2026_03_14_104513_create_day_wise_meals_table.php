<?php

use App\Models\Meal;
use App\Models\MealType;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('day_wise_meals', function (Blueprint $table) {
            $table->id();

            $table->date('date')->index();

            $table->foreignIdFor(MealType::class)->constrained()->cascadeOnDelete();

            $table->foreignIdFor(Meal::class)->constrained()->cascadeOnDelete();

            $table->timestamps();


            // Prevent duplicate same date + meal_type + meal
            $table->unique(['date', 'meal_type_id', 'meal_id'], 'ux_date_mealtype_meal');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('day_wise_meals');
    }
};
