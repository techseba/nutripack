<div class="overflow-x-auto overflow-y-hidden scrollbar-custom max-w-full">
    <table class="w-full">
        <thead>
            <x-table.tr thead>
                <x-table.th class="w-10">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" wire:model.live="selectAll" class="peer sr-only" />
                        <div
                            class="h-4 w-4 rounded-md border border-zinc-600 peer-checked:border-red-600
                                            peer-checked:bg-red-600 flex items-center justify-center
                                            transition-all duration-75 ease-in-out
                                            peer-hover:border-red-500">
                            <x-icons.check class="" size="10" />
                        </div>
                    </label>
                </x-table.th>
                <x-table.th class="w-15">ID</x-table.th>
                <x-table.th class="w-20">Date</x-table.th>
                <x-table.th class="w-15">S_ID</x-table.th>
                <x-table.th>subscriber name</x-table.th>
                <x-table.th>meal name</x-table.th>
                <x-table.th>meal type</x-table.th>
                <x-table.th>created at</x-table.th>
                <x-table.th class="w-20">actions</x-table.th>
            </x-table.tr>
        </thead>
        <tbody>
            @forelse ($this->rows as $row)
                <x-table.tr>
                    <x-table.td>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" wire:model.live="selected" value="{{ $row->id }}"
                                class="peer sr-only" />
                            <div
                                class="h-4 w-4 rounded-md border border-zinc-600 peer-checked:border-red-600
                                                    peer-checked:bg-red-600 flex items-center justify-center
                                                    transition-all duration-75 ease-in-out
                                                    peer-hover:border-red-500">
                                <x-icons.check class="" size="10" />
                            </div>
                        </label>
                    </x-table.td>
                    <x-table.td>{{ $row->id }}</x-table.td>
                    <x-table.td><x-widget.date :value="$row->date" /></x-table.td>
                    <x-table.td>{{ $row->subscriber->id }}</x-table.td>
                    <x-table.td>{{ $row->subscriber->user->name }}</x-table.td>
                    <x-table.td>{{ $row->meal->name }}</x-table.td>
                    <x-table.td>{{ $row->mealType->name }}</x-table.td>
                    <x-table.td><x-widget.date-time :value="$row->created_at" /></x-table.td>
                    <x-table.td>
                        <div class="flex justify-end gap-2">
                            <button wire:click.prevent="edit({{ $row->id }})"
                                class="text-green-500 hover:text-green-400 p-1 bg-zinc-800 hover:bg-zinc-700 rounded-md transition-colors duration-300 cursor-pointer">
                                <x-icons.edit size="16" />
                            </button>

                            <button @click="$dispatch('open-delete-modal', { id: {{ $row->id }} })"
                                class="text-red-600 hover:text-red-500 p-1 bg-zinc-800 hover:bg-zinc-700 rounded-md transition-colors duration-300 cursor-pointer">
                                <x-icons.delete size="16" />
                            </button>
                        </div>
                    </x-table.td>
                </x-table.tr>
            @empty
                <x-table.tr>
                    <x-table.td colspan="12" class="text-center">{{ ucfirst($subject) ?? 'Item' }} was not
                        found.</x-table.td>
                </x-table.tr>
            @endforelse
        </tbody>
    </table>
</div>
