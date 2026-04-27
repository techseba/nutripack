<select wire:model.live="perPage"
    class="dark:bg-zinc-900 border border-zinc-700 px-2 py-1 rounded-md text-sm focus:outline-0 dark:text-zinc-200">
    <option value="{{ $this->perPage }}">{{ $this->perPage }}</option>
    <option value="10">10</option>
    <option value="15">15</option>
    <option value="20">20</option>
</select>
