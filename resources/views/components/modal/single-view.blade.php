@props([
    'size' => 'md', // sm, md, lg, xl, full
    'isEdit'    => null,
    'isFileUpload' => null,
])
<div x-data="{ singleViewModal: false }" x-on:open-view-modal.window="singleViewModal = true"
    x-on:close-view-modal.window="singleViewModal = false" class="relative">

    {{-- modal overlay --}}
    <div x-show="singleViewModal" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-500"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click.stop
        class="fixed inset-0 bg-zinc-950/40 backdrop-blur-xs backdrop-saturate-150 z-40" @click="singleViewModal = false">

        {{-- modal opacity --}}
        <div x-show="singleViewModal" x-transition.opacity class="fixed inset-0 z-40" @click="singleViewModal = false"></div>

        @php
            $sizeClass = match($size) {
                'sm' => 'w-sm max-w-sm',
                'md' => 'w-sm md:w-lg max-w-lg',
                'lg' => 'w-md md:w-2xl max-w-2xl',
                'xl' => 'w-lg md:w-4xl max-w-4xl',
                'full' => 'w-[95vw] h-[90vh]',
                default => 'w-sm md:w-lg max-w-lg',
            };
        @endphp

        {{-- modal panel --}}
        <div x-show="singleViewModal" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-0" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-300" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-0" @click.stop
            class="fixed overflow-y-auto z-50 top-1/2 left-1/2 {{ $sizeClass }} -translate-x-1/2 -translate-y-1/2 rounded-lg border border-zinc-700 bg-zinc-900/50 px-8 py-10 shadow-xl transition-all duration-300">

            {{-- modal header --}}
            <div class="flex justify-between mb-4">
                <div>
                    <h3 class="text-lg items-start text-zinc-200 mb-1"><span class="capitalize">{{ $this->subject ?? null }}</span> information</h3>
                    <p class="text-sm text-zinc-400">View your
                        {{ $this->subject ?? null }} content below.</p>
                </div>
                <button @click="singleViewModal = false; $wire.resetFields();"
                    class="text-zinc-400 hover:text-zinc-100 text-2xl leading-0">&times;</button>
            </div>

            <div>
                {{$slot}}
            </div>

        </div>
    </div>

</div>
