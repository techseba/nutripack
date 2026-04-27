@props([
    'thead' =>  false,
])

@php
    $base = 'text-sm text-left transition-all duration-300';

    $theadClass = 'dark:bg-zinc-900 dark:text-zinc-200 capitalize';

    $tbodyClass = 'dark:bg-zinc-900/0 dark:text-zinc-400 dark:hover:bg-zinc-900 dark:hover:text-zinc-200';

    $classes = $base . ' ' . ($thead ? $theadClass : $tbodyClass);
@endphp

<tr {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</tr>
