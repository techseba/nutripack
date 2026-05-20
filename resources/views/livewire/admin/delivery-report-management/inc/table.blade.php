<div class="overflow-x-auto overflow-y-hidden scrollbar-custom max-w-full">
    <table class="w-full">
        <thead>
            <x-table.tr thead>
                <x-table.th>date</x-table.th>
                <x-table.th class="w-15">S_ID</x-table.th>
                <x-table.th>subscriber</x-table.th>
                <x-table.th>phone</x-table.th>
                <x-table.th>address</x-table.th>
                <x-table.th>meals</x-table.th>
                {{-- <x-table.th>status</x-table.th> --}}
                <x-table.th>delivered at</x-table.th>
            </x-table.tr>
        </thead>
        <tbody>
            @forelse ($this->deliveryRows() as $row)
                <x-table.tr>
                    <x-table.td><x-widget.date :value="$row->date" /></x-table.td>
                    <x-table.td>{{ $row->subscriber_id }}</x-table.td>
                    <x-table.td>{{ $row->subscriber_name }}</x-table.td>
                    <x-table.td>{{ $row->subscriber_phone }}</x-table.td>
                    <x-table.td>{{ $row->subscriber_address }}</x-table.td>
                    <x-table.td>
                        @foreach ($row->meal_names as $meal)
                            <div class="text-sm">
                                {{ ucfirst(str_replace('-', '_', $meal)) }}
                            </div>
                        @endforeach
                    </x-table.td>
                    {{-- <x-table.td>
                        <span class="{{ $row->delivery_status === 'Delivered' ? 'text-green-600' : 'text-yellow-600' }}">
                            {{ $row->delivery_status }}
                        </span>
                    </x-table.td> --}}
                    <x-table.td>{{ $row->delivered_at ? $row->delivered_at : '—' }}</x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="8" class="text-center">{{ ucfirst($subject) ?? 'Item' }} was not
                        found.</x-table.td>
                </x-table.tr>
            @endforelse
        </tbody>
    </table>
</div>
