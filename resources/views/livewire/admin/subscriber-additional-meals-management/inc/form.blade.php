<x-modal.form isEdit="{{ $this->isEdit }}" size="xl">

    <div class="lg:grid grid-cols-2 gap-4">

        @if ($isEdit)
            <div class="space-y-5">
                <x-form.field-input type="date" model="date" label="date" disabled required />
                <x-form.field-input type="number" model="subscriberId" disabled label="subscriber ID" required />
                <x-form.field-input model="subscriberName" label="subscriber name" disabled required />
            </div>

            <div class="space-y-5">
                <x-form.field-select label="meal type" model="mealTypeId" live disabled placeholder="Select meal type"
                    required>
                    @foreach ($this->mealTypes as $mealType)
                        <option value="{{ $mealType->id }}">
                            {{ $mealType->name }}
                        </option>
                    @endforeach
                </x-form.field-select>

                <x-form.field-select label="meal" model="mealId" live placeholder="Select meal" required>
                    @foreach ($this->meals as $meal)
                        <option value="{{ $meal->id }}">
                            {{ $meal->name }}
                        </option>
                    @endforeach
                </x-form.field-select>
            </div>
        @endif

    </div>

</x-modal.form>
