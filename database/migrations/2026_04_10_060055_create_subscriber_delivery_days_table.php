<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('subscriber_delivery_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscriber_id')->constrained('subscribers')->cascadeOnDelete();
            $table->date('delivery_date');
            $table->tinyInteger('day_of_week'); // 0=Sun ... 6=Sat
            $table->json('items')->nullable(); // user selected items for that date
            $table->enum('status', ['scheduled', 'skipped', 'delivered'])->default('scheduled');
            $table->timestamps();
            $table->unique(['subscriber_id', 'delivery_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriber_delivery_days');
    }
};
