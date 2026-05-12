<?php

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
        Schema::create('subscriber_additional_meal_type', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Subscriber::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->foreignIdFor(MealType::class)
                ->constrained()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_additional_meal_type');
    }
};
