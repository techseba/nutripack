<div>

    {{-- TOP ROW --}}
    <div class="flex flex-col lg:flex-row items-end justify-between mb-4 gap-4 lg:gap-0">

        {{-- search box --}}
        <x-widget.search type="date" />

        {{-- button group --}}
        <div class="flex gap-2">

            {{-- bulk delete modal --}}
            <x-action.bulk-delete />

            {{-- select per page paginate --}}
            <x-form.per-page-select />

            {{-- export button --}}
            <x-ui.button wire:click="exportKitchenPdf">Download PDF</x-ui.button>

        </div>

    </div>

    {{-- SECOND ROW --}}
    <div class="p-4 border border-zinc-700 rounded-lg">

        {{-- header and modal --}}
        <div class="flex items-center justify-between mb-4">

            {{-- header --}}
            <x-widget.table-header />

            {{-- modal --}}
            {{-- @include('admin.kitchen-report-management.inc.form') --}}
        </div>

        {{-- table --}}
        @include('admin.kitchen-report-management.inc.table')

        {{-- pagination --}}
        <div class="flex justify-end">
            {{-- {{ $this->rows->links('livewire::pagination') }} --}}
        </div>

    </div>


    {{-- SECOND ROW Additional meals --}}
    <div class="p-4 border border-zinc-700 rounded-lg mt-5">

        {{-- header and modal --}}
        <div class="flex items-center justify-between mb-4">

            {{-- header --}}
            <div>
                <h4 class="dark:text-zinc-200 capitalize">Additional Meals {{ Illuminate\Support\Str::plural($this->subject) }}</h4>
                <p class="text-sm text-zinc-500">Here is the list of all available
                    {{ Illuminate\Support\Str::plural($this->subject) }}.
                </p>
            </div>

            {{-- modal --}}
            {{-- @include('admin.kitchen-report-management.inc.form') --}}
        </div>

        {{-- table --}}
        @include('admin.kitchen-report-management.inc.additional-meal-table')

        {{-- pagination --}}
        <div class="flex justify-end">
            {{-- {{ $this->rows->links('livewire::pagination') }} --}}
        </div>

    </div>

    <x-modal.delete-confirmation />

</div>
