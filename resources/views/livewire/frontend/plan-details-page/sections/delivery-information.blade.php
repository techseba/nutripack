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
        <p class="text-xs text-slate-400 text-center -mt-2 italic">* We need at least 24 hours to prepare your
            first
            batch.</p>
    @enderror

</fieldset>
