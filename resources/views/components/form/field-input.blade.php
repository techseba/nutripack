@props([
    'label' => null,
    'type' => 'text',
    'id' => null,
    'model' => null,
    'live' => false,
    'required' => null,
    'disabled' => false,
])

@php
    $inputId = $id ?? $model;

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

    @if ($type == 'password')
        <div class="relative" x-data="{ eye: false }">
            <input id="{{ $inputId }}" :type="eye ? 'text' : 'password'"
                @if ($model) wire:model.defer="{{ $model }}" @endif
                {{ $attributes->merge([
                    'class' => $baseClass . ' ' . ($errors->has($model) ? $errorClass : $normalClass),
                ]) }} />
            <!-- Eye Button -->
            <button type="button" x-on:click="eye = ! eye"
                class="absolute right-3 top-1/2 -translate-y-1/2 transition cursor-pointer">

                <!-- Eye Open -->
                <svg x-show="eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-200" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5
                                 c4.477 0 8.268 2.943 9.542 7
                                 -1.274 4.057-5.065 7-9.542 7
                                 -4.477 0-8.268-2.943-9.542-7z" />
                </svg>

                <!-- Eye Off -->
                <svg x-show="!eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-zinc-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19
                                 c-4.477 0-8.268-2.943-9.542-7
                                 a9.956 9.956 0 012.223-3.592M6.223 6.223
                                 A9.956 9.956 0 0112 5c4.477 0 8.268 2.943
                                 9.542 7a9.956 9.956 0 01-4.132 5.411M3 3
                                 l18 18" />
                </svg>
            </button>
        </div>
    @else
        @if ($live)
            <input type="{{ $type }}" step="any" id="{{ $inputId }}"
                @if ($model) wire:model.live.debounce.50ms="{{ $model }}" @endif
                @if ($disabled) disabled @endif
                {{ $attributes->merge([
                    'class' => $baseClass . ' ' . ($errors->has($model) ? $errorClass : $normalClass),
                ]) }}
                aria-label="{{ $label }}" />
        @else
            <input type="{{ $type }}" step="any" id="{{ $inputId }}"
                @if ($model) wire:model.defer="{{ $model }}" @endif
                @if ($disabled) disabled @endif
                {{ $attributes->merge([
                    'class' => $baseClass . ' ' . ($errors->has($model) ? $errorClass : $normalClass),
                ]) }}
                aria-label="{{ $label }}" />
        @endif
    @endif

    @error($model)
        <p class="flex gap-1 text-sm text-red-500 mt-1 italic">
            <x-icons.error size="15" />
            <span>{{ $message }}</span>
        </p>
    @enderror
</div>
