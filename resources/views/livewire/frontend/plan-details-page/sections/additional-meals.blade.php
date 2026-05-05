{{-- @if ($selectedPlan) --}}
<fieldset class="flex flex-col gap-y-4 bg-white border border-slate-400 shadow-lg p-3 rounded-lg mb-8">
    <legend class="font-medium text-md">Select additional meals</legend>


    @if ($this->hasBreakfast)
        <div
            class="meal-row grid grid-cols-4 items-center gap-2 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 rounded-lg py-2 px-3">
            <div class="col-span-2">
                <div class="font-medium">Breakfast</div>
                <div class="text-xs text-slate-500">BHD {{ number_format($this->breakfastUnitPrice, 2) }}</div>
            </div>

            <div class="col-span-1 text-sm text-right">
                <span class="text-slate-600">Max: {{ $this->breakfastMaxQuantity }}</span>
            </div>

            <div class="col-span-1">
                <input type="number" wire:model.live.debounce.150ms="breakfastQuantity" min="0" max="{{ $this->breakfastMaxQuantity }}"
                    step="1"
                    class="w-full focus:outline-0 bg-white border border-slate-300 rounded-md py-1 px-2 font-bold" />
            </div>


            <div class="col-span-4 text-right text-sm mt-1 pr-2">
                <span class="text-slate-600">Line total: </span>
                <span class="line-total font-bold">BHD {{ number_format($this->breakfastTotalPrice, 2) }}</span>
            </div>

        </div>
    @endif


    @if ($this->hasSalad)
        <div
            class="meal-row grid grid-cols-4 items-center gap-2 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-400 rounded-lg py-2 px-3">
            <div class="col-span-2">
                <div class="font-medium">Salad</div>
                <div class="text-xs text-slate-500">BHD {{ number_format($this->saladUnitPrice, 2) }}</div>
            </div>

            <div class="col-span-1 text-sm text-right">
                <span class="text-slate-600">Max: {{ $this->saladMaxQuantity }}</span>
            </div>

            <div class="col-span-1">
                <input type="number" wire:model.live.debounce.150ms="saladQuantity" min="0" max="{{ $this->saladMaxQuantity }}"
                    step="1"
                    class="w-full focus:outline-0 bg-white border border-slate-300 rounded-md py-1 px-2 font-bold" />
            </div>


            <div class="col-span-4 text-right text-sm mt-1 pr-2">
                <span class="text-slate-600">Line total: </span>
                <span class="line-total font-bold">BHD {{ number_format($this->saladTotalPrice, 2) }}</span>
            </div>

        </div>
    @endif


    <div
        class="grid grid-cols-3 text-sm bg-gray-100 text-slate-700 border border-dotted border-gray-300 rounded-lg py-2.5 px-3 mt-3">
        <label class="col-span-1 font-medium">Additional Price</label>
        <span id="additional-total" class="col-span-2 font-bold text-right">BHD {{ number_format($this->totalAdditionalPrice, 2) }}</span>
    </div>


    {{-- <pre>{{ json_encode($selectedPlan, JSON_PRETTY_PRINT) }}</pre> --}}

</fieldset>
{{-- @endif --}}
