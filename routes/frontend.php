<?php

use App\Livewire\Frontend\HelpPage\HelpIndex;
use App\Livewire\Frontend\HomePage\HomeIndex;
use App\Livewire\Frontend\MealPreviewPage\MealPreviewIndex;
use App\Livewire\Frontend\PlanDetailsPage\PlanDetailsIndex;
use App\Livewire\Frontend\PrivacyPolicyPage\PrivacyPolicyIndex;
use App\Livewire\Frontend\SubscriptionPage\SubscriptionIndex;
use App\Livewire\Frontend\TermsAndConditionsPage\TermsAndConditionsIndex;
use Illuminate\Support\Facades\Route;


Route::get('/', HomeIndex::class)->name('home')->middleware('redirect.if.subscribed');

Route::get('/meal/{id}', MealPreviewIndex::class)->name('meal.preview');

Route::get('/plan', PlanDetailsIndex::class)->middleware('auth')->name('plan');

Route::get('/subscription', SubscriptionIndex::class)->middleware('auth')->name('subscription');

Route::get('/terms-and-conditions', TermsAndConditionsIndex::class)->name('terms-and-conditions');
Route::get('/privacy-policy', PrivacyPolicyIndex::class)->name('privacy-policy');

Route::get('/help', HelpIndex::class)->name('help');

Route::get('/ping', function () {
    return response()->json(['ok' => true]);
});
