<div class="overflow-x-auto overflow-y-hidden scrollbar-custom max-w-full">
    <table class="w-full">
        <thead>
            <x-table.tr thead>
                <x-table.th class="w-20">date</x-table.th>
                <x-table.th class="w-15">S_ID</x-table.th>
                <x-table.th>subscriber</x-table.th>
                <x-table.th>phone</x-table.th>
                <x-table.th>meals to pack</x-table.th>
                <x-table.th>allergens</x-table.th>
            </x-table.tr>
        </thead>
        <tbody>
            @forelse ($this->packingRows() as $row)
                <x-table.tr>
                    <x-table.td><x-widget.date :value="$row->date" /></x-table.td>
                    <x-table.td>{{ $row->subscriber_id }}</x-table.td>
                    <x-table.td>{{ $row->subscriber_name }}</x-table.td>
                    <x-table.td>{{ $row->subscriber_phone }}</x-table.td>
                    <x-table.td>
                        @foreach ($row->meal_names as $mealName)
                            <div class="text-sm">{{ $mealName }}</div>
                        @endforeach
                    </x-table.td>
                    <x-table.td>
                        @foreach ($row->subscriber_allergens as $allergen)
                            <div class="text-sm capitalize">{{ $allergen }}</div>
                        @endforeach
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="6" class="text-center">{{ ucfirst($subject) ?? 'Item' }} was not
                        found.</x-table.td>
                </x-table.tr>
            @endforelse
        </tbody>
    </table>
</div>
