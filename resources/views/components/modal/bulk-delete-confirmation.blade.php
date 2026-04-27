@props([
    'subject' => 'items',
])

<div x-data="{ bulkDeleteConfirmation: false, itemId: null }"
    x-on:open-bulk-delete-modal.window="
        bulkDeleteConfirmation = true;
        itemId = $event.detail.id;
    "
    x-on:close-bulk-delete-modal.window="bulkDeleteConfirmation = false" x-cloak>

    <!-- Overlay -->
    <div x-show="bulkDeleteConfirmation" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs backdrop-saturate-150 z-40"
        @click="$dispatch('close-bulk-delete-modal')"></div>


    <!-- Modal Panel -->
    <div x-show="bulkDeleteConfirmation" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95" @click.stop
        class="fixed z-50 top-1/2 left-1/2 w-110 lg:w-full  max-w-lg mx-0 lg:mx-2 -translate-x-1/2 -translate-y-1/2
                bg-zinc-900 border border-zinc-700 rounded-2xl shadow-2xl p-6
                transition-all duration-300">
        <div class="flex flex-col space-y-4">
            <!-- Icon -->
            <div class="flex items-center gap-2">
                <div class="bg-red-500/20 p-2 rounded-full">
                    <x-icons.delete css="text-light-5" size="20" />
                </div>
                <h2 class="text-lg text-white">Confirm Multiple Deletion</h2>
            </div>


            <p class="text-sm text-zinc-400">
                Are you sure you want to delete selected {{ Str::plural($subject) }}? This action cannot be undone.
            </p>

            <!-- Buttons -->
            <div class="flex justify-end gap-3 pt-2">
                <button @click="$dispatch('close-bulk-delete-modal')"
                    class="cursor-pointer px-3 lg:px-4 py-1 lg:py-2 rounded-md bg-zinc-800 hover:bg-zinc-700 border border-zinc-600 text-zinc-300 transition">
                    Cancel
                </button>
                <button @click="$wire.deleteSelected();"
                    class="cursor-pointer px-3 lg:px-4 py-1 lg:py-2 rounded-md bg-red-600 hover:bg-red-700 border border-zinc-400 text-white font-medium transition">
                    Delete
                </button>
            </div>
        </div>
    </div>
</div>
