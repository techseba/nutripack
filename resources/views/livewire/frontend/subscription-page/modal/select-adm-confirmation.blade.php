<div x-data="{ selectConfirmModal: false, itemId: null }"
    x-on:open-select-adm-modal.window="
        selectConfirmModal = true;
        itemId = $event.detail.id;
    "
    x-on:close-select-modal.window="selectConfirmModal = false" x-cloak class="relative">

    {{-- modal overlay --}}
    <div x-show="selectConfirmModal" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-500" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0" @click.stop
        class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs backdrop-saturate-150 z-40"
        @click="$dispatch('close-select-modal')">

        {{-- modal opacity --}}
        <div x-show="selectConfirmModal" x-transition.opacity class="fixed inset-0 z-40"
            @click="$dispatch('close-select-modal')"></div>

        {{-- modal panel --}}
        <div x-show="selectConfirmModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95" @click.stop
            class="fixed z-50 top-1/2 left-1/2 w-full max-w-sm -translate-x-1/2 -translate-y-1/2
                bg-white/80 backdrop-blur-sm border-2 border-white rounded-2xl shadow-2xl p-6
                transition-all duration-300">
            <div class="text-center space-y-4">
                <!-- Icon -->
                <div class="flex justify-center">
                    <div class="p-3 bg-red-500/20 rounded-full">
                        <x-icons.select css="text-gray-500" size="28" />
                    </div>
                </div>

                <!-- Title -->
                <h2 class="text-lg font-semibold text-slate-700">Are you sure? ADM</h2>
                <p class="text-sm text-zinc-500">
                    <b>We’ll reserve this meal for you right away — it can’t be changed later.</b> If that sounds good, tap Confirm and we’ll lock in your choice.
                </p>

                <!-- Buttons -->
                <div class="flex justify-center gap-3 pt-2">
                    <x-ui.button variant="secondary" size="lg"
                        @click="$dispatch('close-select-modal')">Cancel</x-ui.button>
                    <x-ui.button variant="danger" size="lg" @click="$wire.selectADMMeal(itemId)">Confirm</x-ui.button>
                </div>
            </div>
        </div>

    </div>

</div>
