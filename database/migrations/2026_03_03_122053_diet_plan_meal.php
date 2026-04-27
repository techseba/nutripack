<?php

use App\Models\DietPlan;
use App\Models\Meal;
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
        Schema::create('diet_plan_meal', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(DietPlan::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Meal::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            // Optional: prevent duplicate combination
            $table->unique(['diet_plan_id', 'meal_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_plan_meal');
    }
};
