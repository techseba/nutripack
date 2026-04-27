<div>

    {{-- TOP ROW --}}
    <div class="flex flex-col lg:flex-row items-end justify-between mb-4 gap-4 lg:gap-0">

        {{-- search box --}}
        <x-widget.search type="date" />
        {{-- button group --}}
        <div class="flex gap-2">

            {{-- export button --}}
            <x-ui.button wire:click="exportPackingPdf">Download PDF</x-ui.button>

            {{-- <x-modal.upload-csv-form /> --}}

        </div>

    </div>

    {{-- SECOND ROW --}}
    <div class="p-4 border border-zinc-700 rounded-lg">

        {{-- header and modal --}}
        <div class="flex items-center justify-between mb-4">

            {{-- header --}}
            <x-widget.table-header />

            {{-- modal --}}
            {{-- @include('admin.packing-report-management.inc.form') --}}
        </div>

        {{-- table --}}
        @include('admin.packing-report-management.inc.table')

        {{-- pagination --}}
        <div class="flex justify-end">
            {{-- {{ $this->packingRows()->links('livewire::pagination') }} --}}
        </div>

    </div>

    <x-modal.delete-confirmation />

</div>
