<?php

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
        Schema::create('additional_meals', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // remove unique for flexibility
            $table->decimal('unit_price', 10, 2)->default(0.00);
            $table->unsignedInteger('max_quantity')->default(1);
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->text('description')->nullable();
            $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();
            $table->softDeletes(); // optional but recommended
            $table->timestamps();

            $table->index('status');
            $table->unique(['name', 'status']); // optional: ensure unique active names, adjust as needed
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('additional_meals');
    }
};
