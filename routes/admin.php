<?php

use App\Livewire\Admin\AdditionalMealManagement\AdditionalMealIndex;
use App\Livewire\Admin\DashboardManagement\Overview;
use App\Livewire\Admin\DayWiseMealManagement\DayWiseMealIndex;
use App\Livewire\Admin\DeliveryReportManagement\DeliveryReportIndex;
use App\Livewire\Admin\DietPlanManagement\DietPlanIndex;
use App\Livewire\Admin\GuestMealManagement\GuestMealIndex;
use App\Livewire\Admin\IngredientManagement\IngredientIndex;
use App\Livewire\Admin\KitchenReportManagement\KitchenReportIndex;
use App\Livewire\Admin\MealManagement\MealIndex;
use App\Livewire\Admin\MealTypeManagement\MealTypeIndex;
use App\Livewire\Admin\PackingReportManagement\PackingReportIndex;
use App\Livewire\Admin\PermissionManagement\PermissionIndex;
use App\Livewire\Admin\PlanCategoryManagement\PlanCategoryIndex;
use App\Livewire\Admin\PlanManagement\PlanIndex;
use App\Livewire\Admin\PromoCodeManagement\PromoCodeIndex;
use App\Livewire\Admin\RoleManagement\RoleIndex;
use App\Livewire\Admin\SubscriberManagement\SubscriberIndex;
use App\Livewire\Admin\SubscriberMealsManagement\SubscriberMealsIndex;
use App\Livewire\Admin\UserManagement\UserIndex;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])->prefix('admin')->as('admin.')->group( function(){
    Route::get('dashboard', Overview::class)->name('dashboard');

    Route::get('permissions', PermissionIndex::class)->name('permissions');
    Route::get('roles', RoleIndex::class)->name('roles');
    Route::get('users', UserIndex::class)->name('users');

    Route::get('diet-plans', DietPlanIndex::class)->name('diet-plans');
    Route::get('meal-types', MealTypeIndex::class)->name('meal-types');
    Route::get('ingredients', IngredientIndex::class)->name('ingredients');
    Route::get('meals', MealIndex::class)->name('meals');
    Route::get('additional-meals', AdditionalMealIndex::class)->name('additional-meals');

    Route::get('guest-meals', GuestMealIndex::class)->name('guest-meals');

    Route::get('day-wise-meals', DayWiseMealIndex::class)->name('day-wise-meals');

    Route::get('plan-categories', PlanCategoryIndex::class)->name('plan-categories');
    Route::get('plans', PlanIndex::class)->name('plans');

    Route::get('subscribers', SubscriberIndex::class)->name('subscribers');
    Route::get('subscriber-meals', SubscriberMealsIndex::class)->name('subscriber-meals');

    Route::get('promo-codes', PromoCodeIndex::class)->name('promo-codes');

    Route::get('kitchen-report', KitchenReportIndex::class)->name('kitchen-report');
    Route::get('packing-report', PackingReportIndex::class)->name('packing-report');
    Route::get('delivery-report', DeliveryReportIndex::class)->name('delivery-report');
});
