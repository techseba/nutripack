@props([
    'label' => null,
    'id' => null,
    'model' => null,
    'required' => false,
    'multiple' => false,
    'placeholder' => null,
    'live' => false,
])

@php
    $inputId = $id ?? $model;

    $baseClass =
        'w-full border bg-white dark:bg-zinc-900 rounded-sm hover:shadow-md px-3 py-2 text-sm dark:text-zinc-200 dark:focus:bg-zinc-950 focus:outline-none focus:ring transition-all';

    $multipleClass = $multiple ? 'min-h-20' : '';

    $errorClass = 'border-red-800 ring-red-500';
    $normalClass = 'border-zinc-700 ring-zinc-600';

    $hasError = $model ? $errors->has($model) : false;
@endphp


<div class="w-full">

    {{-- Label --}}
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm mb-1 dark:text-zinc-300 first-letter:uppercase">

            {{ $label }}

            @if ($required)
                <span class="text-red-600">*</span>
            @endif

        </label>
    @endif


    @if ($live)
        {{-- Select --}}
        <select id="{{ $inputId }}" @if ($multiple) multiple @endif
            @if ($model) wire:model.live.debounce.150ms="{{ $model }}" @endif
            {{ $attributes->merge([
                'class' => $baseClass . ' ' . $multipleClass . ' ' . ($hasError ? $errorClass : $normalClass),
            ]) }}>

            @if ($placeholder && !$multiple)
                <option value="">
                    {{ $placeholder }}
                </option>
            @endif

            {{ $slot }}

        </select>
    @else
        {{-- Select --}}
        <select id="{{ $inputId }}" @if ($multiple) multiple @endif
            @if ($model) wire:model.defer="{{ $model }}" @endif
            {{ $attributes->merge([
                'class' => $baseClass . ' ' . $multipleClass . ' ' . ($hasError ? $errorClass : $normalClass),
            ]) }}>

            @if ($placeholder && !$multiple)
                <option value="">
                    {{ $placeholder }}
                </option>
            @endif

            {{ $slot }}

        </select>
    @endif


    {{-- Error --}}
    @if ($model)
        @error($model)
            <p class="flex gap-1 text-sm text-red-500 mt-1 italic">

                <x-icons.error size="15" />

                <span>{{ $message }}</span>

            </p>
        @enderror
    @endif

</div>
