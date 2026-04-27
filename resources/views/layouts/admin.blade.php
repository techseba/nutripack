<x-layouts::admin.sidebar :title="$title ?? null">

    <div class="hidden lg:flex items-center justify-between py-2 md:py-4 px-4 border-b border-slate-300 dark:border-zinc-700">

        {{-- Breadcrumb --}}
        <div class="flex gap-2">
            <a href="{{ route('home') }}" wire:navigate><x-icons.folder-kanban size="18" /></a>
            <h1 class="text-sm dark:text-zinc-200 capitalize">{{ $title ?? 'title' }}</h1>
        </div>

        <div class="flex items-center gap-2">
            <a href="#"
                class="dark:text-zinc-200 dark:hover:text-zinc-100 dark:bg-zinc-800 p-1 rounded-md transition-colors duration-300 dark:hover:bg-zinc-700">
                <x-icons.message size="18" />
            </a>
            <a href="#"
                class="dark:text-zinc-200 dark:hover:text-zinc-100 dark:bg-zinc-800 p-1 rounded-md transition-colors duration-300 dark:hover:bg-zinc-700">
                <x-icons.notification size="18" />
            </a>
        </div>

    </div>

    <flux:main>
        {{ $slot }}
    </flux:main>
</x-layouts::admin.sidebar>
