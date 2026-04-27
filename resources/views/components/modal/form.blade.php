@props([
    'isEdit' => false,
    'isFileUpload' => false,
    'size' => 'md', // sm, md, lg, xl, full
    'addNewButton' => 'yes',
])
<div x-data="{ formModal: false }" x-on:open-modal.window="formModal = true"
    x-on:close-modal.window="formModal = false; $wire.resetFields();" class="relative">

    {{-- modal trigger --}}
    @if ($addNewButton == 'yes')
        <x-ui.button @click="formModal = true">add new</x-ui.button>
    @endif

    {{-- modal overlay --}}
    <div x-show="formModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop
        class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs backdrop-saturate-150 z-40">

        {{-- modal opacity --}}
        <div x-show="formModal" x-transition.opacity class="fixed inset-0 z-40"></div>

        @php
            $sizeClass = match ($size) {
                'sm' => 'w-sm max-w-sm',
                'md' => 'w-sm md:w-lg max-w-lg',
                'lg' => 'w-md md:w-2xl max-w-2xl',
                'xl' => 'w-lg md:w-4xl max-w-4xl',
                'full' => 'w-[95vw] h-[90vh]',
                default => 'w-sm md:w-lg max-w-lg',
            };
        @endphp

        {{-- modal panel --}}
        <div x-show="formModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-0" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-0" @click.stop
            class="fixed overflow-y-auto z-50 top-1/2 left-1/2 {{ $sizeClass }} -translate-x-1/2 -translate-y-1/2 rounded-lg border border-zinc-700 bg-white/80 backdrop-blur-xs dark:bg-zinc-900/50 px-8 py-10 shadow-xl transition-all duration-300">

            {{-- modal header --}}
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg items-start dark:text-zinc-200 mb-1">{{ $isEdit ? 'Update ' : 'Write a New ' }}<span
                            class="capitalize">{{ $this->subject ?? null }}</span></h3>
                    <p class="text-sm dark:text-zinc-400">{{ $isEdit ? 'Update' : 'Write' }} your
                        {{ $this->subject ?? null }} content below.</p>
                </div>
                <button @click="formModal = false; $wire.resetFields();"
                    class="text-zinc-400 hover:text-zinc-100 text-2xl leading-0">&times;</button>
            </div>

            <form wire:submit.prevent="save" class="space-y-4"
                {{ $isFileUpload ? 'enctype=multipart/form-data' : null }}>

                {{-- from field --}}
                {{ $slot }}

                {{-- from actions --}}
                <div class="flex justify-end gap-2">
                    <div @click="formModal = false; $wire.resetFields();"
                        class="bg-red-500 dark:bg-red-700 py-1 md:py-1.5 px-2 md:px-3 rounded-md border border-white/60 text-white dark:text-white/80 text-sm transition-colors cursor-pointer duration-300 hover:bg-red-600 dark:hover:bg-red-600 hover:border-white/80 hover:text-white shadow-md dark:shadow-zinc-900">
                        Cancel</div>
                    <button type="submit"
                        class="backdrop-blur-sm
                                disabled:opacity-50
                                disabled:cursor-not-allowed bg-white/90 py-1 md:py-1.5 px-2 md:px-3 rounded-md border border-black/80 text-black/90 text-sm transition-colors cursor-pointer duration-300 hover:bg-white hover:border-black/80 hover:text-black dark:shadow-md shadow-zinc-900">
                        {{ $isEdit ? 'Update' : 'Save' }}
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>
