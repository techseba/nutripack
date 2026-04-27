<?php

use App\Models\Meal;
use App\Models\MealType;
use App\Models\Subscriber;
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
        Schema::create('subscriber_meal_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Subscriber::class)->constrained()->cascadeOnDelete();
            $table->date('date')->index();
            $table->foreignIdFor(MealType::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Meal::class)->constrained()->cascadeOnDelete();
            $table->timestamps();

            // Ensure one selection per subscriber per date per meal type
            $table->unique(['subscriber_id', 'date', 'meal_type_id'], 'subscriber_date_mealtype_unique');

            // Helpful indexes for reporting queries
            $table->index(['date', 'meal_id'], 'date_meal_idx');
            $table->index(['date', 'subscriber_id'], 'date_subscriber_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_meal_selections');
    }
};
