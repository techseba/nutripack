<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="full">

    <div class="block lg:grid lg:grid-cols-4 gap-x-5 space-y-5 lg:space-y-0">
        <div class="col-span-3 space-y-5">
            <x-form.field-input live model="name" label="name" required />

            <div class="flex gap-4 text-sm text-zinc-200">
                @foreach ($this->diet_plans as $diet_plan)
                    <label>
                        <input type="radio" wire:model.live="dietPlanForSlug" value="{{ $diet_plan->name }}">
                        {{ $diet_plan->name }}
                    </label>
                @endforeach
            </div>

            <x-form.field-input model="slug" label="slug" required />


            <x-form.field-message model="description" label="description" />
            <div class="flex flex-col lg:flex-row gap-4">
                <x-form.field-input type="number" model="calories" label="calories" />
                <x-form.field-input type="number" model="protein" label="protein" />
                <x-form.field-input type="number" model="carbs" label="carbs" />
                <x-form.field-input type="number" model="fat" label="fat" />
                {{-- <x-form.field-input type="number" model="fiber" label="fiber" /> --}}
                <x-form.field-input type="number" model="price" label="price" />
            </div>
        </div>
        <div class="space-y-5">
            <x-form.field-input type="file" model="image" label="image" />

            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="h-20">
            @elseif ($existingImage)
                <img src="{{ asset('storage/' . $existingImage) }}" class="h-20">
            @endif

            <x-form.field-select label="Meal type" model="meal_type_id" placeholder="Select meal type" required>

                @foreach ($this->meal_types as $meal_type)
                    <option value="{{ $meal_type->id }}">
                        {{ $meal_type->name }}
                    </option>
                @endforeach

            </x-form.field-select>

            <x-form.checkbox-group label="Diet plans" model="mealDietPlans" :options="$this->diet_plans" required />
            <x-form.checkbox-group label="Ingredients" model="mealIngredients" :options="$this->ingredients" required />

            @if ($isEdit)
                <x-form.status-select label="Status" model="status" />
            @endif

        </div>
    </div>

</x-modal.form>
