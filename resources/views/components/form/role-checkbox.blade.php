@props([
    'label' => null,
    'model' => null,
    'options' => [],
    'required' => false,
])

@php
    $baseClass = 'border border-zinc-700 rounded-md bg-zinc-900 p-3 min-h-9 max-h-56 overflow-y-auto';
@endphp

<div>
    <div class="w-full max-h-64 overflow-y-auto">

        {{-- Label --}}
        @if ($label)
            <label class="block text-sm mb-2 text-zinc-300 first-letter:uppercase">
                {{ $label }}

                @if ($required)
                    <span class="text-red-500">*</span>
                @endif

            </label>
        @endif


        {{-- Options --}}
        <div class="{{ $baseClass }}">

            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-3 gap-2">

                @foreach ($options as $option)
                    <label wire:key="checkbox-{{ $option->id }}"
                        class="flex items-center gap-2 text-sm text-zinc-200 hover:bg-zinc-800 p-1 rounded cursor-pointer">

                        <input type="checkbox" value="{{ $option->name }}" wire:model="{{ $model }}"
                            class="rounded border-zinc-600 bg-zinc-900 text-indigo-500 focus:ring-indigo-500" />

                        <span>{{ $option->name }}</span>

                    </label>
                @endforeach

            </div>

        </div>
    </div>
    <div>
        {{-- Error --}}
        @error($model)
            <p class="flex gap-1 text-sm text-red-500 mt-1 italic">

                <x-icons.error size="15" />

                <span>{{ $message }}</span>

            </p>
        @enderror
    </div>
</div>
