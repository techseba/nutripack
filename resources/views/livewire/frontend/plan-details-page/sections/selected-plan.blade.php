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
    @else
        <div class="text-sm text-gray-500 text-center">No plan selected.</div>
    @endif
</fieldset>
