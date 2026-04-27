@props([
    'label' => null,
    'model' => null,
    'options' => [],
    'required' => false,
])

@php
    $baseClass = 'border border-zinc-700 rounded-md bg-white dark:bg-zinc-900 p-3 min-h-9 max-h-56 overflow-y-auto';
@endphp

<div class="w-full max-h-64 overflow-y-auto">

    {{-- Label --}}
    @if ($label)
        <label class="block text-sm mb-1 dark:text-zinc-300 first-letter:uppercase">
            {{ $label }}

            @if ($required)
                <span class="text-red-500">*</span>
            @endif

        </label>
    @endif


    {{-- Options --}}
    <div class="{{ $baseClass }}">

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">

            @foreach ($options as $option)
                <label wire:key="checkbox-{{ $option->id }}"
                    class="flex items-center gap-2 text-sm dark:text-zinc-200 dark:hover:bg-zinc-800 p-1 rounded cursor-pointer">

                    <input type="checkbox" value="{{ $option->id }}" wire:model.defer="{{ $model }}"
                        class="rounded border-zinc-600 bg-zinc-900 text-indigo-500 focus:ring-indigo-500" />

                    <span>{{ $option->name }}</span>

                </label>
            @endforeach

        </div>

    </div>


    {{-- Error --}}
    @error($model)
        <p class="flex gap-1 text-sm text-red-500 mt-1 italic">

            <x-icons.error size="15" />

            <span>{{ $message }}</span>

        </p>
    @enderror

</div>
