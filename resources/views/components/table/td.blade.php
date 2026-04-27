@props([])

@php
    $base = '
        py-1 sm:py-1.5 md:py-2 px-2 sm:px-3 md:px-4 border border-slate-300 dark:border-zinc-700 font-normal whitespace-nowrap
    ';

    $normal = '';

    $activeClass = '';

    $classes = $base . ' ' . $normal;
@endphp

<td {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</td>
