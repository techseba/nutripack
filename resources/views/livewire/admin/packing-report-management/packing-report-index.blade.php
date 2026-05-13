<div>

    {{-- TOP ROW --}}
    <div class="flex flex-col lg:flex-row items-end justify-between mb-4 gap-4 lg:gap-0">

        {{-- search box --}}
        <x-widget.search type="date" />
        {{-- button group --}}
        <div class="flex gap-2">

            {{-- export button --}}
            <x-ui.button wire:click="exportPackingPdf">Download PDF</x-ui.button>

        </div>

    </div>

    {{-- SECOND ROW --}}
    <div class="p-4 border border-zinc-700 rounded-lg">

        {{-- header and modal --}}
        <div class="flex items-center justify-between mb-4">

            {{-- header --}}
            <x-widget.table-header />

        </div>

        {{-- table --}}
        @include('admin.packing-report-management.inc.table')

    </div>

</div>
