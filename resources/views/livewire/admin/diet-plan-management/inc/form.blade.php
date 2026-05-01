<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="lg">

    <div class="grid lg:grid-cols-2 gap-x-5">
        <div class="space-y-5">
            <x-form.field-input model="name" label="name" required />
            @if($isEdit)
                <x-form.field-input model="slug" label="slug" required />
            @endif

            <x-form.field-message model="description" label="description" />
        </div>
        <div class="space-y-5">
            <x-form.field-input type="file" model="image" label="image" />

            @if ($image)
                <img src="{{ $image->temporaryUrl() }}" class="h-20">
            @elseif ($existingImage)
                <img src="{{ asset('storage/'.$existingImage) }}" class="h-20">
            @endif

            <x-form.field-select model="diet_plan_type" label="diet plan type" required>
                <option value="" disabled>Choose...</option>
                <option value="Balanced">Balanced</option>
                <option value="Low Carb">Low Carb</option>
                <option value="Low fat">Low fat</option>
                <option value="High Protein">High Protein</option>
            </x-form.field-select>

            <x-form.field-input type="color" model="color" label="color" class="h-10" />

            @if ($isEdit)
                <x-form.status-select label="Status" model="status" />
            @endif

        </div>
    </div>

</x-modal.form>
