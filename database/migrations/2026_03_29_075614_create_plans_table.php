<?php

use App\Models\DietPlan;
use App\Models\PlanCategory;
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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(DietPlan::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(PlanCategory::class)->constrained()->cascadeOnDelete();

            // Nutrition information
            $table->decimal('min_calories', 8, 2)->nullable();
            $table->decimal('max_calories', 8, 2)->nullable();
            $table->decimal('protein', 6, 2)->nullable();
            $table->decimal('carbs', 6, 2)->nullable();
            $table->decimal('fat', 6, 2)->nullable();
            $table->decimal('fiber', 6, 2)->nullable();

            // Duration information
            $table->tinyInteger('days_of_week')->default(5);

            $table->decimal('price', 10, 2)->nullable();

            // Relation with User
            $table->foreignIdFor(User::class)
                ->constrained()
                ->cascadeOnDelete();

            // Status
            $table->enum('status', ['active', 'inactive'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

