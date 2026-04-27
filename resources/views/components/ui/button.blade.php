@props([
    'variant' => 'primary', // primary, secondary, outline, danger
    'size' => 'md', // sm, md, lg
    'href' => null, // যদি a tag হয়
    'loading' => false, // livewire এর জন্য
])

@php
    $base =
        'relative inline-flex whitespace-nowrap capitalize items-center justify-center gap-2 font-medium rounded-md transition-colors duration-200 focus:outline-none focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed';

    $sizes = [
        'sm' => 'px-3 py-1 text-xs',
        'md' => 'px-3 py-1.5 text-sm',
        'lg' => 'px-4 py-2 text-md',
    ];

    $variants = [
        'primary' =>
            'text-xs sm:text-sm md:text-md text-zinc-4 hover:text-zinc-200 dark:bg-zinc-900 border border-zinc-700 hover:bg-zinc-950 focus:ring-zinc-900 cursor-pointer',
        'secondary' =>
            'text-zinc-300 bg-zinc-800 hover:bg-zinc-700 cursor-pointer focus:ring-zinc-600',
        'outline' =>
            'text-xs sm:text-sm md:text-md text-zinc-600 border border-zinc-900 hover:bg-zinc-950 hover:text-white focus:ring-dark cursor-pointer',
        'danger' =>
            'text-white bg-red-600 hover:bg-red-700 cursor-pointer focus:ring-red-500',
        'submit' =>
            'text-xs sm:text-sm md:text-md text-zinc-900 hover:text-zinc-950 bg-white/90 border border-black hover:bg-white focus:ring-zinc-600 cursor-pointer',
    ];

    $classes = "$base {$sizes[$size]} {$variants[$variant]}";
@endphp

@if ($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if ($loading)
            <span class="absolute inset-0 flex items-center justify-start left-1">
                <svg class="animate-spin h-4 w-4 text-current"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </span>
            <span class="ml-4">{{ $slot }}</span>
        @else
            {{ $slot }}
        @endif
    </a>
@else
    <button {{ $attributes->merge(['class' => $classes, 'disabled' => $loading]) }}>
        @if ($loading)
            <span class="absolute inset-0 flex items-center justify-start left-1">
                <svg class="animate-spin h-4 w-4 text-current"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10"
                            stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8v8z"></path>
                </svg>
            </span>
            <span class="ml-4">{{ $slot }}</span>
        @else
            {{ $slot }}
        @endif
    </button>
@endif
