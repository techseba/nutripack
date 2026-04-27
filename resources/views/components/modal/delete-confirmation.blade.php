<div x-data="{ deleteConfirmModal: false, itemId: null }"
    x-on:open-delete-modal.window="
        deleteConfirmModal = true;
        itemId = $event.detail.id;
    "
    x-on:close-delete-modal.window="deleteConfirmModal = false" x-cloak class="relative">

    {{-- modal overlay --}}
    <div x-show="deleteConfirmModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click.stop
        class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs backdrop-saturate-150 z-40"
        @click="$dispatch('close-delete-modal')">

        {{-- modal opacity --}}
        <div x-show="deleteConfirmModal" x-transition.opacity class="fixed inset-0 z-40"
            @click="$dispatch('close-delete-modal')"></div>

        {{-- modal panel --}}
        <div x-show="deleteConfirmModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="fixed z-50 top-1/2 left-1/2 w-full max-w-sm -translate-x-1/2 -translate-y-1/2
                bg-zinc-900 border border-zinc-700 rounded-2xl shadow-2xl p-6
                transition-all duration-300">
            <div class="text-center space-y-4">
                <!-- Icon -->
                <div class="flex justify-center">
                    <div class="p-3 bg-red-500/20 rounded-full">
                        <x-icons.delete css="text-zinc-500" size="28" />
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-lg font-semibold text-white">Are you sure?</h2>
                <p class="text-sm text-zinc-400">
                    This action cannot be undone. The record will be permanently deleted.
                </p>

                <!-- Buttons -->
                <div class="flex justify-center gap-3 pt-2">
                    <x-ui.button variant="secondary" size="lg"
                        @click="$dispatch('close-delete-modal')">Cancel</x-ui.button>
                    <x-ui.button variant="danger" size="lg" @click="$wire.delete(itemId)">delete</x-ui.button>
                </div>
            </div>
        </div>

    </div>

</div>
