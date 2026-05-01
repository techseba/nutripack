<fieldset
    class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
    <legend class="font-medium text-md">Select additional meals</legend>

    @foreach ($additional_meals as $additional_meal)
        <div
            class="grid grid-cols-4 items-center text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
            <label class="col-span-3 font-medium">{{ $additional_meal->name }} ( BHD {{ $additional_meal->unit_price }} )</label>
            <input type="number" min="0" max="{{ $additional_meal->max_quantity }}" value="0"
                class="col-span-1 focus:outline-0 bg-white border border-slate-400 rounded-md py-1 px-2 font-bold" />
        </div>
    @endforeach

    <div
        class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 shadow-md rounded-lg py-2.5 px-3">
        <label class="col-span-1 font-medium">Additional Price</label>
        <span class="col-span-2 focus:outline-0 font-bold text-right">0 BHD</span>
    </div>

</fieldset>
