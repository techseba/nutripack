<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="full">

    <div class="block lg:grid lg:grid-cols-4 gap-x-5 space-y-5 lg:space-y-0">
        <div class="col-span-3 space-y-5">

            <div class="flex flex-col lg:flex-row gap-4">
                <x-form.field-input model="name" label="name" required />
                @if ($isEdit)
                    <x-form.field-input model="slug" label="slug" required />
                @endif

                <x-form.field-select label="Diet Plan" model="diet_plan_id" placeholder="Select diet plan" required>
                    @foreach ($this->diet_plans as $diet_plan)
                        <option value="{{ $diet_plan->id }}">
                            {{ $diet_plan->name }}
                        </option>
                    @endforeach
                </x-form.field-select>

                <x-form.field-input type="number" model="days_of_plan" label="Day of Plan" />
            </div>

            <div class="flex flex-col lg:flex-row gap-4">
                <x-form.field-input type="number" model="min_calories" label="minimum calories" />
                <x-form.field-input type="number" model="max_calories" label="maximum calories" />
                <x-form.field-input type="number" model="protein" label="protein" />
                <x-form.field-input type="number" model="carbs" label="carbs" />
                <x-form.field-input type="number" model="fat" label="fat" />
                <x-form.field-input type="number" model="fiber" label="fiber" />
            </div>
        </div>
        <div class="space-y-5">
            <x-form.field-input type="file" model="image" label="image" />

            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="h-20">
            @elseif ($existingImage)
                <img src="{{ asset('storage/' . $existingImage) }}" class="h-20">
            @endif

            <x-form.checkbox-group label="Meal Type" model="mealTypes" :options="$this->meal_types" required />

            @if ($isEdit)
                <x-form.status-select label="Status" model="status" />
            @endif

        </div>
    </div>

</x-modal.form>
