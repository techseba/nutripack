<div x-data="{ selected: @entangle('selected') }" class="flex justify-between items-center gap-1 lg:gap-2">
    <div :disabled="selected.length === 0" x-show="selected.length > 0" x-transition.opacity.duration.1000ms
        class="transition-opacity">
        {{-- bulk delete button --}}
        <x-ui.button variant="danger" wire:loading.attr="disabled"
            @click="$dispatch('open-bulk-delete-modal', { total: selected.length })">
            delete
            selected <span x-text="selected.length"></span> <span class="capitalize"
                x-text="selected.length === 1 ? '{{ $this->subject }}' : '{{ $this->subject }}s'"></span>
        </x-ui.button>
    </div>

    {{-- Bulk delete confirmation modal --}}
    <x-modal.bulk-delete-confirmation subject="{{ $this->subject }}" />

</div>
