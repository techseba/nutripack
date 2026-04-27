<x-modal.form isEdit="{{ $this->isEdit }}" size="lg">
    <x-form.field-input model="name" label="role name" required />

    {{-- <x-form.field-select multiple model="selectedPermissions" label="Role Permissions" required>
        <option disabled>Select</option>
        @foreach ($this->permissions as $permission)
            <option wire:key="permission-{{ $permission->id }}" value="{{ $permission->name }}">
                {{ $permission->name }}
            </option>
        @endforeach
    </x-form.field-select> --}}

    <x-form.role-checkbox label="Role Permissions" model="selectedPermissions" :options="$this->permissions" required />
</x-modal.form>
