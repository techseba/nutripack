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
                <x-table.th>name</x-table.th>
                <x-table.th>description</x-table.th>
                <x-table.th>unit price</x-table.th>
                <x-table.th>max quantity</x-table.th>
                <x-table.th>creator</x-table.th>
                <x-table.th>status</x-table.th>
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
                    <x-table.td>{!! highlight($row->name, $this->search) !!}</x-table.td>
                    <x-table.td>{!! highlight($row->description, $this->search) !!}</x-table.td>
                    <x-table.td>{{ $row->unit_price }}</x-table.td>
                    <x-table.td>{{ $row->max_quantity }}</x-table.td>
                    <x-table.td>{!! highlight($row->user->name, $this->search) !!}</x-table.td><x-table.td>
                        <label class="relative inline-flex cursor-not-allowed items-center gap-3 text-gray-900">
                            <input disabled type="checkbox" class="peer sr-only"
                                {{ $row->status == 'active' ? 'checked' : '' }} />
                            <div
                                class="peer h-4 w-9 rounded-full bg-red-700 ring-offset-1 transition-colors duration-200 peer-checked:bg-zinc-700 peer-focus:ring-2 peer-focus:ring-zinc-500">
                            </div>
                            <span
                                class="dot absolute top-1 left-1 h-2 w-2 rounded-full bg-white transition-transform duration-200 ease-in-out peer-checked:translate-x-5"></span>
                        </label>
                    </x-table.td>
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
