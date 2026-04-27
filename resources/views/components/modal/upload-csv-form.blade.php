<div x-data="{ CSVModal: false }" x-on:open-import-modal.window="CSVModal = true"
    x-on:close-import-modal.window="CSVModal = false; $wire.resetFields();" class="relative">

    {{-- modal trigger --}}
    <x-ui.button @click="CSVModal = true">import</x-ui.button>

    {{-- modal overlay --}}
    <div x-show="CSVModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop
        class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs backdrop-saturate-150 z-40">

        {{-- modal opacity --}}
        <div x-show="CSVModal" x-transition.opacity class="fixed inset-0 z-40"></div>

        {{-- modal panel --}}
        <div x-show="CSVModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-0" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-0" @click.stop
            class="fixed z-50 top-1/2 left-1/2 w-sm md:w-lg max-w-lg -translate-x-1/2 -translate-y-1/2 rounded-lg border border-zinc-700 bg-zinc-900/50 px-8 py-10 shadow-xl transition-all duration-300">

            {{-- modal header --}}
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg items-start text-zinc-200 mb-1">
                        Import New
                        <span class="capitalize">{{ $this->subject ?? null }}s</span>
                    </h3>
                    <p class="text-sm text-zinc-400">
                        Import your {{ $this->subject ?? null }}s content below.
                    </p>
                </div>
                <button @click="CSVModal = false; $wire.resetFields();"
                    class="text-zinc-400 hover:text-zinc-100 text-2xl leading-0">&times;</button>
            </div>

            <div class="space-y-4">

                {{-- from field --}}
                <x-form.field-input type="file" label="Upload CSV File" model="csv" required />

                {{-- from actions --}}
                <div class="flex justify-end gap-2">
                    <div @click="CSVModal = false; $wire.resetFields();" wire:loading.remove wire:target="csv"
                        class="bg-red-700 py-1 md:py-1.5 px-2 md:px-3 rounded-md border border-white/60 text-white/80 text-sm transition-colors cursor-pointer duration-300 hover:bg-red-600 hover:border-white/80 hover:text-white shadow-md shadow-zinc-900">
                        Cancel</div>

                    <x-ui.button type="submit" wire:loading wire:target="csv" loading>Uploading</x-ui.button>
                    <x-ui.button type="submit" wire:click="importCSV" wire:loading.remove wire:target="csv" variant="submit">Upload</x-ui.button>
                </div>
            </div>

        </div>
    </div>

</div>
