<fieldset
    class="flex flex-col gap-y-4 bg-white border border-slate-400 ring-10 ring-white shadow-lg shadow-gray-400 p-2 rounded-lg mb-8">
    <legend class="font-medium text-md">Select your allergen Ingredients</legend>

    <div class="grid grid-cols-2 gap-2 max-h-50 overflow-y-scroll">
        @foreach ($ingredients ?? [] as $ingredient)
            <label class="bg-gray-100 border border-dotted border-gray-400 p-1 text-sm rounded-md">
                <input type="checkbox" wire:model="allergens" value="{{ $ingredient->name }}">
                {{ $ingredient->name }}
            </label>
        @endforeach
    </div>
    @error('allergens')
        <div class="text-red-600 text-right text-sm -mt-3 mr-2">{{ $message }}</div>
    @enderror
</fieldset>
