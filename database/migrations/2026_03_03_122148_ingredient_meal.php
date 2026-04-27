<?php

use App\Models\Ingredient;
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
        Schema::create('ingredient_meal', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Meal::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(Ingredient::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->timestamps();

            // Optional: prevent duplicate combination
            $table->unique(['meal_id', 'ingredient_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredient_meal');
    }
};
