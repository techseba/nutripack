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

        @if ($selectedPlan)
            @php
                $startDate = Carbon\Carbon::parse($this->starting_date);
                $expiresDate = Carbon\Carbon::parse($this->starting_date)
                    ->addDays($selectedPlan->planCategory->days_of_plan)
                    ->subDays(1);

                $totalAdditionalMealPrice = $this->totalAdditionalPrice * $this->planDays;

            @endphp

            @if (!empty($this->starting_date))
                <div
                    class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-300 rounded-lg py-2.5 px-3 mt-3">
                    <label class="col-span-2 font-medium">Starting Date</label>
                    <span id="additional-total" class="col-span-1 font-bold text-right">
                        {{ $startDate->format('j M Y') }}</span>
                </div>

                <div
                    class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-300 rounded-lg py-2.5 px-3 mt-3">
                    <label class="col-span-2 font-medium">Expires Date</label>
                    <span id="additional-total" class="col-span-1 font-bold text-right">
                        {{ $expiresDate->format('j M Y') }}</span>
                </div>
            @endif

            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-300 rounded-lg py-2.5 px-3 mt-3">
                <label class="col-span-2 font-medium">Plan Days</label>
                <span id="additional-total" class="col-span-1 font-bold text-right">
                    {{ $this->planDays }} Days</span>
            </div>

            <div
                class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-300 rounded-lg py-2.5 px-3 mt-3">
                <label class="col-span-2 font-medium">Total Additional Price</label>
                <span id="additional-total" class="col-span-1 font-bold text-right">BHD
                    {{ number_format($totalAdditionalMealPrice, 2) }}</span>
            </div>
        @endif


        <div
            class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
            <label class="col-span-1 font-medium">Plan Price</label>
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

        <div
            class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
            <label class="col-span-1 font-medium">Total Price</label>
            <span class="col-span-2 focus:outline-0 font-bold text-right">
                @if ($selectedPlan)
                    @php
                        $totalPrice = (float) $selectedPlan->price + $totalAdditionalMealPrice;
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

        <div class="flex items-center gap-x-1 my-2 text-sm">
            <label>
                <input type="checkbox" wire:model.prevent="termsAndConditions">
            </label>
            <a href="{{ route('terms-and-conditions') }}" class="font-medium hover:text-blue-600 hover:underline" wire:navigate>Terms & Conditions</a>
        </div>
        @error('termsAndConditions')
            <div class="text-red-600 text-sm">{{ $message }}</div>
        @enderror
    </div>

    <input type="submit"
        class="bg-amber-400 rounded-lg capitalize border text-gray-800 font-medium hover:bg-amber-500 transition-colors cursor-pointer border-gray-400 py-2 px-4"
        value="order now">

</fieldset>
