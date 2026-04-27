<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="lg">

    <x-form.field-input type="date" model="date" label="select date" required />

    <x-form.field-select label="meal type" model="mealTypeId" live placeholder="-- Choose --" required>
        @foreach ($this->mealTypes as $mealType)
            <option value="{{ $mealType->id }}">
                {{ $mealType->name }}
            </option>
        @endforeach
    </x-form.field-select>

    <div>
        @if ($this->meals->isNotEmpty())
            <fieldset class="mt-3 border border-zinc-700 ring ring-zinc-600 bg-zinc-950 rounded p-3 text-sm">
                <legend class="font-medium text-zinc-300">Choose meals <span class="text-red-600">*</span></legend>

                <div class="md:grid md:grid-cols-2 space-y-2">
                    @foreach ($this->meals as $meal)
                        <label class="flex items-center text-zinc-200 space-x-2" wire:key="meal-{{ $meal->id }}">
                            <input type="checkbox" wire:model.defer="selectedMeals" value="{{ $meal->id }}"
                                id="meal-{{ $meal->id }}" />
                            <span for="meal-{{ $meal->id }}">{{ $meal->name }}</span>
                        </label>
                    @endforeach
                </div>
            </fieldset>
        @else
            <fieldset class="mt-3 border border-zinc-700 ring ring-zinc-900 bg-zinc-900 rounded p-3 text-sm">
                <legend class="font-medium text-zinc-300">Choose meals <span class="text-red-600">*</span></legend>

                <div class="space-y-2">

                    <label class="flex items-center text-zinc-200 space-x-2">

                        <span>No meals found.</span>
                    </label>

                </div>


            </fieldset>
        @endif
        @error('selectedMeals')
            <p class="flex gap-1 text-sm text-red-500 mt-1 italic">
                <x-icons.error size="15" />
                <span>{{ $message }}</span>
            </p>
        @enderror
    </div>


</x-modal.form>
