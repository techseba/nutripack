<x-modal.form isFileUpload isEdit="{{ $this->isEdit }}" size="xl" addNewButton="not">

    <div class="block lg:grid lg:grid-cols-2 gap-x-5 space-y-5 lg:space-y-0">
        @if ($isEdit)
            <div class="space-y-5">
                <x-form.field-input type="text" model="name" disabled label="Name" required />
                <x-form.field-input type="tel" model="phone" label="phone" required />

                <x-form.field-input type="date" model="starting_date" label="starting date" required />
                <x-form.field-input type="date" model="expires_date" label="expires date" required />

            </div>

            <div class="space-y-5">

                <x-form.field-select label="Payment status" model="payment_status" required>
                    <option value="">Select</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                </x-form.field-select>


                <x-form.status-select label="Status" model="status" />

            </div>
        @endif
    </div>

</x-modal.form>
