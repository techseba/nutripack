<?php

use App\Models\DietPlan;
use App\Models\Plan;
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
        Schema::create('subscribers', function (Blueprint $table) {
        $table->id();

        // relation with plan
        $table->foreignIdFor(Plan::class)->constrained()->cascadeOnDelete();

        // relation with user
        $table->foreignIdFor(User::class)->constrained()->cascadeOnDelete();

        // subscription selection
        $table->unsignedInteger('days_of_week_selected')->default(0);
        $table->string('subscription_days')->nullable(); // e.g. "Mon,Wed,Fri" or JSON

        // dates and times
        $table->date('starting_date');
        $table->date('expires_date')->nullable();
        $table->string('delivery_time')->nullable();

        // pricing
        $table->decimal('subtotal', 10, 2)->default(0);
        $table->decimal('discount_amount', 10, 2)->default(0);
        $table->decimal('total', 10, 2)->default(0);

        // promo relation and readable code
        $table->foreignId('promo_code_id')->nullable()->constrained('promo_codes')->nullOnDelete();
        $table->string('promo_code')->nullable();

        // contact & address
        $table->string('phone', 32);
        $table->string('house')->nullable();
        $table->string('road')->nullable();
        $table->string('block')->nullable();
        $table->string('area')->nullable();
        $table->string('additional_direction')->nullable();

        $table->json('allergens')->nullable();

        // payment & status
        $table->enum('payment_status', ['paid', 'pending', 'unpaid'])->default('unpaid');
        $table->enum('status', ['active', 'inactive'])->default('active');

        $table->foreignId('updater_id')->nullable()->constrained('users')->nullOnDelete();
        // optional: payment meta
        $table->string('payment_method')->nullable();
        $table->string('transaction_id')->nullable();

        // audit & soft delete
        $table->ipAddress('created_by_ip')->nullable();
        $table->softDeletes();
        $table->timestamps();

        // indexes
        $table->index(['user_id', 'plan_id', 'promo_code_id']);
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
