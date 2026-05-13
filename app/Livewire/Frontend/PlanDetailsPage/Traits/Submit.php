<?php

namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use App\Mail\NewSubscriptionMail;
use App\Mail\UserSubscribedMail;
use App\Models\AdditionalMeal;
use App\Models\Plan;
use App\Models\PromoCode;
use App\Models\Subscriber;
use App\Notifications\NewSubscriptionNotification;
use App\Services\SubscriptionService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

trait Submit
{
    public function submit()
    {
        $userId = auth()->id();

        // auth check
        if (! $userId) {
            $this->dispatch('toast', message: 'Please login to subscribe.', type: 'error');
            $this->addError('auth', 'Authentication required.');

            return;
        }

        // RATE LIMITER CODE START
        $userPart = $userId ? 'user:'.$userId : 'ip:'.request()->ip(); // 1. Normalize identity for key of Ratelimit
        // optional: include plan id to avoid cross-plan interference
        $planPart = isset($this->selected_plan_id) ? 'plan:'.(int) $this->selected_plan_id : 'plan:none';

        // normalize a short action key
        $key = "submit-attempts:{$userPart}:{$planPart}";

        $maxAttempts = 3;      // allowed tries
        $decaySeconds = 60;    // reset window in seconds

        // 3. Rate limit check AFTER validation (so typos don't count)
        // If you prefer to count every click, move this block before validate()
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            \Log::warning('Submit rate limited', [
                'key' => $key,
                'ip' => request()->ip(),
                'user_id' => $userId,
                'plan_id' => $this->selected_plan_id ?? null,
            ]);
            $this->dispatch('toast', message: "Too many attempts. Try again in {$seconds} seconds.", type: 'error');
            $this->addError('rate_limit', 'Too many attempts. Please wait and try again.');

            return;
        }

        // 4. Register this attempt
        RateLimiter::hit($key, $decaySeconds); // RATE LIMITER CODE END

        // THIS USER ALL READY HAVE SUBSCRIBER CHECK START
        if ($userId) {
            $today = now()->toDateString();
            $hasActiveDuringPeriod = Subscriber::where('user_id', $userId)
                ->whereDate('expires_date', '>', $today)
                ->orderBy('expires_date', 'desc')
                ->first();

            if ($hasActiveDuringPeriod) {
                $until = $hasActiveDuringPeriod->expires_date->toDateString();
                $this->dispatch('toast', message: "You already have an active subscription in its period. You cannot subscribe to another plan until {$until} it expires.", type: 'error');

                return;
            }
        } // THIS USER ALL READY HAVE SUBSCRIBER CHECK END

        // যদি কোনো একটি প্রয়োজনীয় ফিল্ড খালি থাকে -> ত্রুটি দেখাও
        if (
            ! $this->diet_plan_id ||
            ! $this->plan_category_id ||
            ! $this->selected_plan_id ||
            ! $this->subscription_days ||
            ! $this->delivery_time ||
            ! $this->starting_date
        ) {
            // একত্রিত করে ত্রুটি বার্তা তৈরি করা (প্রয়োজনে বাংলা/ইংরেজি কাস্টমাইজ করো)
            $messages = [];

            if (! $this->diet_plan_id) {
                $messages[] = 'Please select a diet plan.';
            }
            if (! $this->plan_category_id) {
                $messages[] = 'Please select a plan category.';
            }
            if (! $this->selected_plan_id) {
                $messages[] = 'Please select days to choose a plan.';
            }
            if (! $this->subscription_days) {
                $messages[] = 'Please select subscription days.';
            }
            if (! $this->delivery_time) {
                $messages[] = 'Please select delivery time.';
            }
            if (! $this->starting_date) {
                $messages[] = 'Please select starting date.';
            }

            // প্রতিটি মেসেজ আলাদা টোস্ট হিসেবে পাঠানো (তোমার ফ্রন্টএন্ড স্ট্যাকিং সাপোর্ট করে)
            foreach ($messages as $msg) {
                $this->dispatch('toast', message: $msg, type: 'error');
            }

            return;
        }

        // VALIDATE PUBLIC PROPERTY
        $this->validate([
            'diet_plan_id' => ['required'],
            'diet_plan_id.*' => ['exists:diet_plans,id'],

            'plan_category_id' => ['required'],
            'plan_category_id.*' => ['exists:plan_categories,id'],

            'selected_plan_id' => ['required', 'exists:plans,id'],

            'days_of_week_selected' => ['required'],

            // Additional meals rules
            'breakfastQuantity' => ['nullable', 'numeric'],
            'lunchQuantity' => ['nullable', 'numeric'],
            'dinnerQuantity' => ['nullable', 'numeric'],
            'saladQuantity' => ['nullable', 'numeric'],
            'snacksQuantity' => ['nullable', 'numeric'],

            'allergens' => ['nullable', 'array'],
            'allergens.*' => ['exists:ingredients,name'],

            // সাবস্ক্রিপশন ও তারিখ/সময়
            'subscription_days' => ['required', 'string'],

            'delivery_time' => ['required', 'in:Morning 7am - 1pm,Afternoon 5pm - 8pm'],
            // 'starting_date' => ['required', 'date'],
            // অন্যান্য রুল...
            'starting_date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    // delivery_time অনুযায়ী slot-এর earliest time নির্ধারণ
                    $slotStartMap = [
                        'Morning 7am - 1pm' => '07:00:00',
                        'Afternoon 5pm - 8pm' => '17:00:00',
                    ];

                    // যদি delivery_time না থাকে, তা আগে রুলে ধরা হবে; এখানে সেফটি হিসেবে ডিফল্ট
                    $slot = $this->delivery_time ?? null;
                    $time = $slotStartMap[$slot] ?? '09:00:00';

                    try {
                        // Combine date + slot start time
                        $selectedDateTime = Carbon::parse($value.' '.$time);
                    } catch (\Exception $e) {
                        return $fail('Invalid starting date or delivery time.');
                    }

                    // সার্ভারের বর্তমান সময় (config/app.php timezone অনুযায়ী)
                    $minAllowed = Carbon::now()->addDay(); // এখন + 24 ঘন্টা

                    if ($selectedDateTime->lt($minAllowed)) {
                        $fail('We need at least 24 hours to prepare your first batch.');
                    }
                },
            ],

            // যোগাযোগ ও ঠিকানা
            // 'phone' => ['required', 'regex:/^01[3-9][0-9]{8}$/', 'max:32', Rule::unique('subscribers', 'phone')->ignore($userId, 'user_id')],
            'phone' => [
                'required',
                'regex:/^01[3-9][0-9]{8}$/',
                'max:32',
                Rule::unique('subscribers', 'phone')->ignore($userId, 'user_id'),
            ],
            'house' => ['required', 'string', 'max:255'],
            'road' => ['required', 'string', 'max:255'],
            'block' => ['required', 'string', 'max:255'],
            'area' => ['required', 'string', 'max:255'],
            'additional_direction' => ['nullable', 'string', 'max:500'],

            // প্রোমো কোড
            'promo_code' => ['nullable', 'string', 'max:50'],
            'termsAndConditions' => ['required'],
        ]);

        // 3. load plan
        $plan = Plan::find($this->selected_plan_id);
        if (! $plan) {
            $this->addError('selected_plan_id', 'Selected plan not found.');
            $this->dispatch('toast', message: 'Selected plan not found.', type: 'error');

            return;
        }

        // নিশ্চিত করো $plan এবং relation আছে
        $planDuration = (int) (optional($plan->planCategory)->days_of_plan ?? 0);
        $startingDate = $this->starting_date; // e.g. '2026-04-10'

        // OPTIONAL CHECK CODE START
        $slotStartMap = [ // delivery slot start time যোগ করে parse করা (যদি delivery_time থাকে)
            'Morning 7am - 1pm' => '07:00:00',
            'Afternoon 5pm - 8pm' => '17:00:00',
        ];
        $slot = $this->delivery_time ?? null;
        $time = $slotStartMap[$slot] ?? '09:00:00';

        try {
            // $start is a Carbon instance (date + slot time)
            $start = Carbon::parse($startingDate.' '.$time);
        } catch (\Exception $e) {
            $this->addError('starting_date', 'Invalid starting date.');
            $this->dispatch('toast', message: 'Invalid starting date.', type: 'error');

            return;
        } // OPTIONAL CHECK CODE END

        // inclusive counting: expiry = start + (duration - 1) days
        $daysToAdd = max(0, $planDuration - 1);
        $expires = $start->copy()->addDays($daysToAdd);

        // DB-তে সেভ করার জন্য উপযুক্ত ফরম্যাট
        $this->starting_date = $start->toDateString();   // 'YYYY-MM-DD'
        $this->expires_date = $expires->toDateString(); // 'YYYY-MM-DD'

        if ($this->breakfastQuantity > 0) {
            $additionalMealBreakfast = AdditionalMeal::where('name', 'Breakfast')->first();
            $additionalMealBreakfastPrice = $additionalMealBreakfast->unit_price * $this->planDays;
        } else {
            $additionalMealBreakfastPrice = 0;
        }

        if ($this->lunchQuantity > 0) {
            $additionalMealLunch = AdditionalMeal::where('name', 'Lunch')->first();
            $additionalMealLunchPrice = $additionalMealLunch->unit_price * $this->planDays;
        } else {
            $additionalMealLunchPrice = 0;
        }

        if ($this->dinnerQuantity > 0) {
            $additionalMealDinner = AdditionalMeal::where('name', 'Dinner')->first();
            $additionalMealDinnerPrice = $additionalMealDinner->unit_price * $this->planDays;
        } else {
            $additionalMealDinnerPrice = 0;
        }

        if ($this->saladQuantity > 0) {
            $additionalMealSalad = AdditionalMeal::where('name', 'Salad')->first();
            $additionalMealSaladPrice = $additionalMealSalad->unit_price * $this->planDays;
        } else {
            $additionalMealSaladPrice = 0;
        }

        if ($this->snacksQuantity > 0) {
            $additionalMealSnacks = AdditionalMeal::where('name', 'Snacks')->first();
            $additionalMealSnacksPrice = $additionalMealSnacks->unit_price * $this->planDays;
        } else {
            $additionalMealSnacksPrice = 0;
        }

        $totalAdditionalMealPrice = $additionalMealBreakfastPrice + $additionalMealLunchPrice + $additionalMealDinnerPrice + $additionalMealSaladPrice + $additionalMealSnacksPrice;


        // 4. Promo handling and safe save inside transaction
        $promoCodeString = $this->promo_code ?: null;
        $subtotal = (float) $plan->price + $totalAdditionalMealPrice;
        $discount_amount = 0.00;
        $finalTotal = $subtotal;
        $promoId = null;
        $promoCodeReadable = null;

        try {
            DB::transaction(function () use ($promoCodeString, $subtotal, &$discount_amount, &$finalTotal, &$promoId, &$promoCodeReadable) {

                // lock any subscriber rows for this user to avoid race conditions
                $existing = Subscriber::where('user_id', auth()->id())
                    ->where('expires_date', '>=', now())
                    ->lockForUpdate()
                    ->first();

                if ($existing) {
                    throw new \Exception('You already have an active subscription that has not yet expired.');
                }

                $promo = null;
                if ($promoCodeString) {
                    // lock promo row to avoid race conditions (if exists)
                    $promo = PromoCode::where('promo_code', $promoCodeString)->lockForUpdate()->first();
                }

                if ($promo) {
                    // check expiry using model helper
                    if (method_exists($promo, 'isExpired') && $promo->isExpired()) {
                        throw new \Exception('This promo code has expired.');
                    }

                    // optional: check status if you have status/active column
                    if (isset($promo->status) && $promo->status !== 'active') {
                        throw new \Exception('This promo code is not active.');
                    }

                    // calculate discount via model helper
                    $discount_amount = $promo->calculateDiscount($subtotal);
                    $finalTotal = max(0, $subtotal - $discount_amount);

                    $promoId = $promo->id;
                    $promoCodeReadable = $promo->promo_code;

                    // increment uses if column exists
                    if (Schema::hasColumn($promo->getTable(), 'uses')) {
                        $promo->increment('uses');
                    }
                } else {
                    // no promo: totals remain
                    $discount_amount = 0.00;
                    $finalTotal = $subtotal;
                }

                // assign date variable
                $data = [
                    'plan_id' => $this->selected_plan_id,
                    'user_id' => auth()->id(),
                    'days_of_week_selected' => $this->days_of_week_selected,
                    'subscription_days' => $this->subscription_days,
                    'starting_date' => $this->starting_date,
                    'expires_date' => $this->expires_date, // adjust if you want different expiry
                    'delivery_time' => $this->delivery_time,
                    'subtotal' => round($subtotal, 2),
                    'discount_amount' => round($discount_amount, 2),
                    'total' => round($finalTotal, 2),
                    'promo_code_id' => $promoId,
                    'promo_code' => $promoCodeReadable,
                    'phone' => $this->phone,
                    'house' => $this->house,
                    'road' => $this->road,
                    'block' => $this->block,
                    'area' => $this->area,
                    'additional_direction' => $this->additional_direction,
                ];

                // ধরছি $this->allergens হচ্ছে mixed input (names as strings)
                $names = collect($this->allergens)
                    ->map(fn ($i) => is_string($i) ? trim($i) : (is_object($i) && isset($i->name) ? trim($i->name) : null))
                    ->filter()               // remove null/empty
                    ->map(fn ($n) => mb_strtolower($n)) // optional: lowercase normalization
                    ->unique()
                    ->values()
                    ->toArray();

                // create subscriber with ingredients json
                $subscriber = Subscriber::create(array_merge($data, [
                    'allergens' => $names,
                ]));

                // call service
                $service = app(SubscriptionService::class);
                $service->generateAndStoreDeliveryDays(
                    $subscriber,
                    $this->subscription_days,        // e.g. 'sat-wed'
                    (int) $this->days_of_week_selected, // 5|6|7
                    $this->starting_date,
                    $this->expires_date,
                    null // or pass integer limit
                );

                $subscriber->mealTypes()->sync($this->mealTypes);

                Notification::route('mail', env('ADMIN_EMAIL'))
                    ->notify(new NewSubscriptionNotification($subscriber, $subscriber->created_at));

                // Mail::to(env('ADMIN_EMAIL'))->send(new NewSubscriptionMail($subscriber));

            });
        } catch (\Exception $e) {
            \Log::error('Subscriber create failed', ['error' => $e->getMessage(), 'user_id' => $userId ?? null]);
            $msg = str_contains($e->getMessage(), 'promo') ? $e->getMessage() : 'Failed to submit. Please try again.';
            $this->dispatch('toast', message: $msg, type: 'error');
            if (str_contains($e->getMessage(), 'promo')) {
                $this->addError('promo_code', $msg);
            } else {
                $this->addError('submission', $msg);
            }

            return;
        }

        // 5. success
        $this->dispatch('toast', message: 'Successfully submitted', type: 'success');

        $user = auth()->user();

        activity()->causedBy($user)->withProperties(['describe' => request()->ip()])->log('Plan subscribed');

        $planName = $plan->planCategory->name;

        Mail::to($user->email)->send(new UserSubscribedMail($user, $planName));

        $this->resetValidation();
        RateLimiter::clear($key);
        $this->redirectRoute('home', navigate: true);
    }
}
