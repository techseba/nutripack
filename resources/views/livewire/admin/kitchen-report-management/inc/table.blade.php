<div class="overflow-x-auto overflow-y-hidden scrollbar-custom max-w-full">
    <table class="w-full">
        <thead>
            <x-table.tr thead>
                <x-table.th>date</x-table.th>
                <x-table.th>meal name</x-table.th>
                <x-table.th>quantity</x-table.th>
                <x-table.th>meal type</x-table.th>
            </x-table.tr>
        </thead>
        <tbody>
            @forelse ($this->combinedRows as $row)
                <x-table.tr>
                    <x-table.td><x-widget.date :value="$row->date" /></x-table.td>
                    <x-table.td>{{ $row->meal_name }}</x-table.td>
                    <x-table.td>{{ $row->qty }}</x-table.td>
                    <x-table.td>{{ $row->meal_type_name }}</x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="4" class="text-center">{{ ucfirst($subject) ?? 'Item' }} was not
                        found.</x-table.td>
                </x-table.tr>
            @endforelse
        </tbody>
    </table>
</div>
