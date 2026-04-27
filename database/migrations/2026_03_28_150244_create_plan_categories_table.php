<?php

use App\Models\DietPlan;
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
        Schema::create('plan_categories', function (Blueprint $table) {
            $table->id();

            // Basic info
            $table->string('name');
            $table->string('slug');

            // Relation with DietPlan
            $table->foreignIdFor(DietPlan::class)
                ->constrained()
                ->cascadeOnDelete();

            // Duration information
            $table->integer('days_of_plan')->default(0);

            // Nutrition information
            $table->decimal('min_calories', 8, 2)->nullable();
            $table->decimal('max_calories', 8, 2)->nullable();
            $table->decimal('protein', 6, 2)->nullable();
            $table->decimal('carbs', 6, 2)->nullable();
            $table->decimal('fat', 6, 2)->nullable();
            $table->decimal('fiber', 6, 2)->nullable();

            // Photo
            $table->text('image')->nullable();

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
        Schema::dropIfExists('plan_categories');
    }
};
