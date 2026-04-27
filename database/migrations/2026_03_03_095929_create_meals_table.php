<?php

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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->string('image')->nullable();

            $table->decimal('calories', 8, 2)->nullable();
            $table->decimal('protein', 6, 2)->nullable();
            $table->decimal('carbs', 6, 2)->nullable();
            $table->decimal('fat', 6, 2)->nullable();
            $table->decimal('fiber', 6, 2)->nullable();

            $table->decimal('price', 10, 2)->nullable();

            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->boolean('is_guest_meal')->default(false);

            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(MealType::class)->constrained()->cascadeOnDelete();


            $table->unique(['slug', 'deleted_at']);
            $table->index('is_guest_meal');

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
