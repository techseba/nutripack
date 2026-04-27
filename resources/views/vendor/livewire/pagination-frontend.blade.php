@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination"
        class="flex flex-wrap items-center justify-center mt-4 gap-2 sm:gap-3 select-none px-2">

        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span
                class="flex items-center gap-1 px-3 py-1.5 text-sm text-zinc-50 bg-emerald-500 border border-emerald-600 rounded-md cursor-not-allowed">
                <x-icons.chevron-left size="14" /> Prev
            </span>
        @else
            <button wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                dusk="previousPage"
                class="flex items-center gap-1 px-3 py-1.5 text-sm border border-emerald-600 rounded-md text-zinc-300 bg-emerald-500 hover:bg-orange-500 hover:text-white transition">
                <x-icons.chevron-left size="14" /> Prev
            </button>
        @endif

        {{-- Pagination Elements --}}
        <div class="flex flex-wrap items-center justify-center gap-1 text-sm">
            @foreach ($elements as $element)
                {{-- "Three Dots" Separator --}}
                @if (is_string($element))
                    <span class="px-3 py-1.5 text-zinc-50">{{ $element }}</span>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span aria-current="page"
                                class="px-3 py-1.5 border border-orange-600 bg-orange-500 text-white rounded-md">
                                {{ $page }}
                            </span>
                        @else
                            <button wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')"
                                class="px-3 py-1.5 border border-orange-600 text-zinc-400 hover:text-white hover:bg-emerald-500 rounded-md transition">
                                {{ $page }}
                            </button>
                        @endif
                    @endforeach
                @endif
            @endforeach
        </div>

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <button wire:click="nextPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled"
                dusk="nextPage"
                class="flex items-center gap-1 px-3 py-1.5 text-sm border border-emerald-600 rounded-md text-zinc-300 bg-emerald-500 hover:bg-orange-500 hover:text-white transition">
                Next <x-icons.chevron-right size="14" />
            </button>
        @else
            <span
                class="flex items-center gap-1 px-3 py-1.5 text-sm text-zinc-50 bg-emerald-500 border border-emerald-600 rounded-md cursor-not-allowed">
                Next <x-icons.chevron-right size="14" />
            </span>
        @endif
    </nav>
@endif
