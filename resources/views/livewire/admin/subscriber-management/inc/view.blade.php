<x-modal.single-view size="md">
    <div class="overflow-x-auto overflow-y-hidden scrollbar-custom max-w-full">

        <table class="w-full">

            <x-table.tr>
                <x-table.th thead>name</x-table.th>
                <x-table.td>{{ $this->name }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>phone</x-table.th>
                <x-table.td>{{ $this->phone }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>email</x-table.th>
                <x-table.td>{{ $this->email }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>address</x-table.th>
                <x-table.td>{{ $this->address }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>days_of_week</x-table.th>
                <x-table.td>{{ $this->days_of_week }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>plan_price</x-table.th>
                <x-table.td>
                    {{ $this->plan_price }}
                    <sup>BHD</sup></x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>subtotal</x-table.th>
                <x-table.td>
                    {{ $this->subtotal }}
                    <sup>BHD</sup></x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>promo_code</x-table.th>
                <x-table.td>{{ $this->promo_code }}</x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>discount_amount</x-table.th>
                <x-table.td>
                    {{ $this->discount_amount }}
                    <sup>BHD</sup>
                </x-table.td>
            </x-table.tr>

            <x-table.tr>
                <x-table.th thead>total</x-table.th>
                <x-table.td>
                    {{ $this->total }}
                    <sup>BHD</sup>
                </x-table.td>
            </x-table.tr>

        </table>

        <div class="flex justify-between mt-4">
            <div class="text-sm text-zinc-200">Updater info</div>
            <div class="flex flex-col gap-2 text-xs text-zinc-400">
                <span>{{ $this->updaterEmail }}</span>
                <span>{{ $this->updaterPhone }}</span>
            </div>
        </div>
    </div>
</x-modal.single-view>
