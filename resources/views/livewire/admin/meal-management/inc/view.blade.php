<x-modal.single-view size="md">
    <div class="overflow-x-auto overflow-y-hidden scrollbar-custom max-w-full">
        <table class="w-full">
            <x-table.tr>
                <x-table.th thead class="w-20">name</x-table.th>
                <x-table.td>{{ $this->name }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>description</x-table.th>
                <x-table.td>{{ $this->description }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>calories</x-table.th>
                <x-table.td>{{ $this->calories }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>protein</x-table.th>
                <x-table.td>{{ $this->protein }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>carbs</x-table.th>
                <x-table.td>{{ $this->carbs }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>fat</x-table.th>
                <x-table.td>{{ $this->fat }}</x-table.td>
            </x-table.tr>

            {{-- <x-table.tr>
                <x-table.th thead>fiber</x-table.th>
                <x-table.td>{{ $this->fiber }}</x-table.td>
            </x-table.tr>
             --}}
        </table>
    </div>
</x-modal.single-view>
