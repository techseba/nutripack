<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="lg">

    <div class="grid lg:grid-cols-2 gap-x-5">
        <div class="space-y-5">
            <x-form.field-select label="Meal type" model="name" placeholder="Select meal type" required>

                @foreach ($this->meal_types as $meal_type)
                    <option value="{{ $meal_type->name }}">
                        {{ $meal_type->name }}
                    </option>
                @endforeach

            </x-form.field-select>

            <x-form.field-message model="description" label="description" />
        </div>
        <div class="space-y-5">
            <x-form.field-input type="number" model="unit_price" label="unit price" required />
            <x-form.field-input type="number" disabled max="1" model="max_quantity" label="max quantity" class="cursor-not-allowed" />

            @if ($isEdit)
                <x-form.status-select label="Status" model="status" />
            @endif

        </div>
    </div>

</x-modal.form>
