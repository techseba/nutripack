<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="full">

    <div class="block lg:grid lg:grid-cols-4 gap-x-5 space-y-5 lg:space-y-0">
        <div class="col-span-3 space-y-5">

            <div class="flex flex-col lg:flex-row gap-4">

                <x-form.field-select label="Diet Plan" model="diet_plan_id" live placeholder="Select diet plan" required>
                    @foreach ($this->diet_plans as $diet_plan)
                        <option value="{{ $diet_plan->id }}">
                            {{ $diet_plan->name }}
                        </option>
                    @endforeach
                </x-form.field-select>

                <x-form.field-select label="Plan Categories" model="plan_category_id" live
                    placeholder="Select plan category" required>
                    @foreach ($this->plan_categories as $plan_category)
                        <option value="{{ $plan_category->id }}">
                            {{ $plan_category->name }}
                        </option>
                    @endforeach
                </x-form.field-select>

                {{-- <x-form.checkbox-group label="Plan Categories" model="plan_category_id" :options="$this->plan_categories" required /> --}}

            </div>

            <div class="flex flex-col lg:flex-row gap-4">
                <x-form.field-input type="number" disabled model="min_calories" label="minimum calories" />
                <x-form.field-input type="number" disabled model="max_calories" label="maximum calories" />
                <x-form.field-input type="number" disabled model="protein" label="protein" />
                <x-form.field-input type="number" disabled model="carbs" label="carbs" />
                <x-form.field-input type="number" disabled model="fat" label="fat" />
                {{-- <x-form.field-input type="number" disabled model="fiber" label="fiber" /> --}}
            </div>
        </div>

        <div class="space-y-5">
            <x-form.days-select label="Days of Week" model="days_of_week" />
            <x-form.field-input type="number" model="price" label="Price" required />

            @if ($isEdit)
                <x-form.status-select label="Status" model="status" />
            @endif
        </div>
    </div>

</x-modal.form>
