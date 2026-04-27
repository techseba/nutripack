@can('user.create')
    <x-modal.form>
        <x-form.field-input label="name" model="name" required />
        <x-form.field-input label="email" model="email" required />
        <x-form.field-input type="password" label="password" model="password" required />
        <x-form.field-input type="password" label="confirm password" model="password_confirmation" required />

        @if ($this->isEdit)
            <x-form.field-select label="Role" model="user_role">
                <option value="" disabled>Select</option>
                @foreach ($this->roles as $role)
                    <option wire:key="{{ $role->id }}" value="{{ $role->name }}">
                        {{ $role->name }}</option>
                @endforeach
            </x-form.field-select>
        @endif

        @if ($isEdit)
            <x-form.status-select label="Status" model="status" />
        @endif
    </x-modal.form>
@endcan
