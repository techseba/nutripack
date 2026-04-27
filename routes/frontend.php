<?php

use App\Livewire\Frontend\HomePage\HomeIndex;
use App\Livewire\Frontend\PlanDetailsPage\PlanDetailsIndex;
use App\Livewire\Frontend\SubscriptionPage\SubscriptionIndex;
use Illuminate\Support\Facades\Route;


Route::get('/', HomeIndex::class)->name('home')->middleware('redirect.if.subscribed');

Route::get('/plan', PlanDetailsIndex::class)->middleware('auth')->name('plan');

Route::get('/subscription', SubscriptionIndex::class)->middleware('auth')->name('subscription');

