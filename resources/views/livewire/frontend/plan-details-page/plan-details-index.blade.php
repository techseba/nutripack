<main class="max-w-md mx-auto min-h-screen bg-lemon text-slate-900 pt-2 pb-16 px-4 overflow-hidden">

    {{-- Plan details header --}}
    <div class="flex justify-between items-center">
        <div class="flex items-center gap-x-2">
            <a href="{{ route('home') }}" wire:navigate>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 hover:text-app-yellow" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </a>
            <h1 class="font-bold text-slate-700 my-4 text-xl">Plan Details</h1>
        </div>

        {{-- clock --}}
        <x-widget.clock />

    </div>

    <form wire:submit.prevent="submit">

        {{-- selected plan --}}
        <fieldset
            class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
            <legend class="font-medium text-md">Selected Plan</legend>

            {{-- show diet plans --}}
            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                <label for="diet_plan_id" class="col-span-1 font-medium">Diet plan</label>
                <select wire:model.live="diet_plan_id" id="diet_plan_id"
                    class="col-span-2 focus:outline-0 font-bold text-center rounded-md border border-gray-300 py-1 px-2">
                    <option value="">-- Select Diet Plan --</option>

                    @foreach ($dietPlans as $dietPlan)
                        <option value="{{ $dietPlan->id }}">{{ $dietPlan->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('diet_plan_id')
                <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
            @enderror

            {{-- show plan categories --}}
            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                <label for="plan_category_id" class="col-span-1 font-medium">Plan category</label>
                <select wire:model.live="plan_category_id" wire:loading.attr="disabled" id="plan_category_id"
                    wire:loading.attr="disabled"
                    class="col-span-2 focus:outline-0 font-bold text-center rounded-md border border-gray-300 py-1 px-2"
                    @if (($planCategories ?? collect())->isEmpty()) disabled @endif>
                    <option value="">-- Select Category --</option>
                    @foreach ($planCategories ?? [] as $planCategory)
                        <option value="{{ $planCategory->id }}">{{ $planCategory->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('plan_category_id')
                <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
            @enderror


            {{-- show days of week --}}
            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                <label for="days_of_week_selected" class="col-span-1 font-medium">Days of Week</label>
                <select wire:model.live="days_of_week_selected" id="days_of_week_selected" wire:loading.attr="disabled"
                    class="col-span-2 focus:outline-0 font-bold text-center rounded-md border border-gray-300 py-1 px-2"
                    @if (($daysOptions ?? collect())->isEmpty()) disabled @endif>
                    <option value="">-- Select Days --</option>
                    @foreach ($daysOptions ?? [] as $days)
                        <option value="{{ $days }}">{{ $days }} Days</option>
                    @endforeach
                </select>
            </div>
            @error('days_of_week_selected')
                <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
            @enderror

            {{-- selected plan details --}}
            @if ($selectedPlan)
                <div
                    class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="col-span-1 font-medium">No of Meals</label>
                    <span
                        class="col-span-2 focus:outline-0 font-bold text-right">{{ count($selectedPlan->planCategory->mealTypes) }}
                        Meals</span>
                </div>

                <div
                    class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="col-span-1 font-medium">Total Meals</label>

                    <div class="flex items-center justify-end gap-2 col-span-2">
                        @foreach ($selectedPlan->planCategory->mealTypes as $meal)
                            <span class="text-xs font-medium text-emerald-600">{{ $meal->name }}</span>
                        @endforeach
                    </div>
                </div>

                <div
                    class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="col-span-1 font-medium">Plan Price</label>
                    <span class="col-span-2 focus:outline-0 font-bold text-right">{{ $selectedPlan->price }} BHD</span>
                </div>

                <div
                    class="flex justify-between items-center text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="font-medium">Additional Breakfast</label>
                    <button class="focus:outline-0 bg-white border border-slate-400 rounded-md py-1 px-2 font-bold text-right">Add+</button>
                </div>

                <div
                    class="flex justify-between items-center text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="font-medium">Additional Lunch</label>
                    <button class="focus:outline-0 bg-white border border-slate-400 rounded-md py-1 px-2 font-bold text-right">Add+</button>
                </div>

                <div
                    class="flex justify-between items-center text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="font-medium">Additional Dinner</label>
                    <button class="focus:outline-0 bg-white border border-slate-400 rounded-md py-1 px-2 font-bold text-right">Add+</button>
                </div>

                <div
                    class="flex justify-between items-center text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="font-medium">Additional Salad</label>
                    <button class="focus:outline-0 bg-white border border-slate-400 rounded-md py-1 px-2 font-bold text-right">Add+</button>
                </div>

                <div
                    class="flex justify-between items-center text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="font-medium">Additional Snacks</label>
                    <button class="focus:outline-0 bg-white border border-slate-400 rounded-md py-1 px-2 font-bold text-right">Add+</button>
                </div>

                <div
                    class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="col-span-1 font-medium">Additional Price</label>
                    <span class="col-span-2 focus:outline-0 font-bold text-right">{{ $selectedPlan->price }} BHD</span>
                </div>
            @else
                <div class="text-sm text-gray-500 text-center">No plan selected.</div>
            @endif
        </fieldset>

        {{-- allergen  ingredients --}}
        <fieldset
            class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
            <legend class="font-medium text-md">Select your allergen Ingredients</legend>

            <div class="grid grid-cols-2 gap-2">
                @foreach ($ingredients ?? [] as $ingredient)
                    <label class=" bg-gray-100 border border-dotted border-gray-400 p-1 text-sm rounded-md">
                        <input type="checkbox" wire:model="allergens" value="{{ $ingredient->name }}">
                        {{ $ingredient->name }}
                    </label>
                @endforeach
            </div>
            @error('allergens')
                <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
            @enderror
        </fieldset>

        {{-- delivery information --}}
        <fieldset
            class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
            <legend class="font-medium text-md">Delivery Information</legend>

            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                <label for="subscription_days" class="col-span-1 font-medium">Subscription days</label>
                <select wire:model.live="subscription_days" id="subscription_days"
                    class="col-span-2 focus:outline-0 font-bold text-center rounded-md border border-gray-300 py-1 px-2">
                    <option value="">-- Select Subscription days --</option>
                    @if ($days_of_week_selected == 5)
                        <option value="sat-wed">SAT - WED</option>
                        <option value="sun-thu">SUN - THU</option>
                    @elseif($days_of_week_selected == 6)
                        <option value="sat-thu">SAT - THU</option>
                    @elseif($days_of_week_selected == 7)
                        <option value="sat-fri">SAT - FRI</option>
                    @endif

                </select>
            </div>
            @error('subscription_days')
                <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
            @enderror

            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                <label for="delivery_time" class="col-span-1 font-medium">Delivery Time</label>
                <select wire:model.live="delivery_time" id="delivery_time"
                    class="col-span-2 focus:outline-0 font-bold text-center rounded-md border border-gray-300 py-1 px-2">
                    <option value="">-- Select Delivery Time --</option>
                    <option value="Morning 7am - 1pm">Morning 7am - 1pm</option>
                    <option value="Afternoon 5pm - 8pm">Afternoon 5pm - 8pm</option>
                </select>
            </div>
            @error('delivery_time')
                <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
            @enderror

            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                <label for="starting_date" class="col-span-1 font-medium">Starting Date</label>
                <input type="date" id="starting_date" wire:model.prevent="starting_date" {{-- min="{{ now()->addDay()->toDateString() }}" --}}
                    min="{{ $this->minStartingDate }}"
                    class="col-span-2 focus:outline-0 font-bold text-center rounded-md border border-gray-300 py-1 px-2">
            </div>
            @error('starting_date')
                <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
            @else
                <p class="text-xs text-slate-400 text-center -mt-2 italic">* We need at least 24 hours to prepare your first
                    batch.</p>
            @enderror

        </fieldset>

        {{-- delivery address --}}
        <fieldset
            class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
            <legend class="font-medium text-md">Delivery Address</legend>

            <div class="space-y-3">
                <input type="tel" placeholder="Enter your phone" wire:model.prevent="phone"
                    class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
                @error('phone')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <input type="number" placeholder="House number" wire:model.prevent="house"
                    class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
                @error('house')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <input type="number" placeholder="Road number" wire:model.prevent="road"
                    class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
                @error('road')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <input type="text" placeholder="Block" wire:model.prevent="block"
                    class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
                @error('block')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <input type="text" placeholder="Area" wire:model.prevent="area"
                    class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400">
                @error('area')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

                <textarea cols="30" rows="5" placeholder="Additional direction" wire:model.prevent="additional_direction"
                    class="bg-gray-100 py-1.5 px-2 rounded-md w-full text-sm font-medium focus:outline-0 border border-dotted border-gray-400"></textarea>
                @error('additional_direction')
                    <div class="text-red-600 text-sm">{{ $message }}</div>
                @enderror

            </div>
        </fieldset>

        {{-- summery --}}
        <fieldset
            class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
            <legend class="font-medium text-md">Summary</legend>

            <div class="space-y-3">

                <div x-data="{ open: false }" class="space-y-2">
                    <label class="inline-flex items-center">
                        <input type="checkbox" x-model="open" class="mr-2">
                        <span>Have a promo code?</span>
                    </label>

                    <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform -translate-y-2 scale-95"
                        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
                        x-transition:leave="transition ease-in duration-200"
                        x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
                        x-transition:leave-end="opacity-0 transform -translate-y-2 scale-95"
                        class="mt-2 grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                        <label class="col-span-1 font-medium" for="promo_code">Promo Code</label>
                        <div class="col-span-2 flex gap-2 items-center justify-between">
                            <input type="text" wire:model.live="promo_code"
                                class="border w-2/3 border-gray-400 py-1.5 px-2 rounded-md focus:outline-0">
                            <button type="button" wire:click="apply"
                                class="bg-emerald-500 rounded-md capitalize border border-emerald-700 text-white text-sm hover:bg-emerald-600 transition-colors cursor-pointer w-1/3 py-1.5 px-2">apply</button>
                            <button type="button" wire:click="remove" class="text-red-600">❌</button>
                        </div>
                    </div>
                    @error('promo_code')
                        <div class="text-red-600 text-sm text-right">{{ $message }}</div>
                    @enderror
                </div>

                <div
                    class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
                    <label class="col-span-1 font-medium">Total Price</label>
                    <span class="col-span-2 focus:outline-0 font-bold text-right">
                        @if ($selectedPlan)
                            @php
                                $totalPrice = (float) $selectedPlan->price;
                                // ensure promoItem is array-like and get safe values
                                $promoType = is_array($promoItem) ? $promoItem['type'] ?? null : null;
                                $promoValue = is_array($promoItem) ? $promoItem['value'] ?? 0 : 0;
                                $promoValue = (float) $promoValue;
                            @endphp

                            @if (!empty($promoType))
                                @if ($promoType === 'fixed')
                                    @php $totalPrice = max(0, $totalPrice - $promoValue); @endphp
                                @else
                                    @php $totalPrice = max(0, $totalPrice - $totalPrice * ($promoValue / 100)); @endphp
                                @endif
                            @endif

                            {{ number_format($totalPrice, 2, '.', '') }} BHD
                        @else
                            0.00 BHD
                        @endif
                    </span>
                </div>
            </div>

            <input type="submit"
                class="bg-amber-400 rounded-lg capitalize border text-gray-800 font-medium hover:bg-amber-500 transition-colors cursor-pointer border-gray-400 py-2 px-4"
                value="order now">

        </fieldset>

        {{-- <pre>{{ json_encode($selectedPlan, JSON_PRETTY_PRINT) }}</pre> --}}
    </form>
</main>
