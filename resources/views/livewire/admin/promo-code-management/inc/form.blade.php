<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="md">

    <div class="space-y-5">
        <x-form.field-input type="text" model="promo_code" label="Promo Code" required />

        <x-form.field-select label="Type" model="type" required>
            <option value="fixed">Fixed</option>
            <option value="percentage">Percentage</option>
        </x-form.field-select>

        <x-form.field-input type="number" model="value" label="Value" required />

        <x-form.field-input type="date" model="expires_at" label="expires at" required />

        @if ($isEdit)
            <x-form.status-select label="Status" model="status" />
        @endif
    </div>

</x-modal.form>
