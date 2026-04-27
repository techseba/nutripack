@props([
    'placeholder' => 'Search...',
    'type' => 'search',
])
<form
    class="
            group
            flex
            w-full
            lg:w-sm
            items-center
            gap-2
            dark:text-zinc-200
            border
            border-slate-400
            dark:border-zinc-700
            backdrop-blur-sm
            dark:bg-zinc-900
            dark:hover:bg-zinc-950
            px-3
            py-1.5
            rounded-md
            transition-all
            duration-300
            focus-within:border-zinc-600
            focus-within:shadow-md
            focus-within:shadow-zinc-700/40
        ">
    <x-icons.search size="16"
        class="dark:text-zinc-600 group-focus-within:text-zinc-500 transition-colors duration-300" />

    <input type="{{ $type }}" wire:model.live.debounce.500="search" placeholder="{{ $placeholder }}"
        class="
                md:w-64
                bg-transparent
                flex-1
                px-2
                text-sm
                focus:outline-none
                placeholder:text-zinc-500
                focus:placeholder:text-zinc-400
                transition-colors
                duration-300
            ">

    <kbd
        class="text-xs border border-zinc-600/60 px-1.5 py-0.5 rounded dark:bg-zinc-900 dark:group-hover:bg-zinc-950 transition-all duration-300 text-zinc-500">
        ⌘K
    </kbd>
</form>
