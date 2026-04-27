@props([
    'label' => null,
    'id' => null,
    'model' => null,
    'required' => null,
    'multiple' => null,
])

@php
    $inputId = $id ?? $model;

    $multipleClass = $multiple ? 'min-h-70' : null;

    $baseClass =
        'w-full border bg-white dark:bg-zinc-900 rounded-sm hover:shadow-md px-3 py-2 text-sm dark:text-zinc-200 dark:focus:bg-zinc-950 focus:outline-0 focus:ring transition-all';

    $errorClass = 'border-red-800 ring-red-500';
    $normalClass = 'border-zinc-700 ring-zinc-600';
@endphp

<div class="w-full">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm mb-1 dark:text-zinc-300 first-letter:uppercase">
            {{ $label }}
            @if ($required)
                <span class="text-red-600">*</span>
            @endif
        </label>
    @endif
    <textarea @if($multiple) multiple @endif id="{{ $inputId }}" @if ($model) wire:model.defer="{{ $model }}" @endif
        {{ $attributes->merge([
            'class' => $baseClass . ' ' . $multipleClass . ' ' . ($errors->has($model) ? $errorClass : $normalClass),
        ]) }} cols="30" rows="5">{{ $slot }}</textarea>

    @error($model)
        <p class="flex gap-1 text-sm text-red-500 mt-1 italic">
            <x-icons.error size="15" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
