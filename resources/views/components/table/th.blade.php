@props([
    'thead' =>  false,
])

@php
    $base = 'py-1 sm:py-1.5 md:py-2 px-2 sm:px-3 md:px-4 border border-zinc-700 font-normal whitespace-nowrap';

    $theadClass = 'dark:bg-zinc-900 text-zinc-200 capitalize';

    $normal = '';

    $activeClass = '';

    $classes = $base . ' ' . $normal . ' ' . ($thead ? $theadClass : null);
@endphp

<th {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</th>
