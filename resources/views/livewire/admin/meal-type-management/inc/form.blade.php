<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="lg">

    <div class="grid grid-cols-2 gap-x-5">
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

            @if ($isEdit)
                <x-form.status-select label="Status" model="status" />
            @endif

        </div>
    </div>

</x-modal.form>
