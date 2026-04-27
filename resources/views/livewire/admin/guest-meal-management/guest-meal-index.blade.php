<div>

    {{-- TOP ROW --}}
    <div class="flex flex-col lg:flex-row items-end justify-between mb-4 gap-4 lg:gap-0">

        {{-- search box --}}
        <x-widget.search />

        {{-- button group --}}
        <div class="flex gap-2">

            {{-- select per page paginate --}}
            <x-form.per-page-select />

        </div>

    </div>

    {{-- SECOND ROW --}}
    <div class="p-4 border border-zinc-700 rounded-lg">

        {{-- header and modal --}}
        <div class="flex items-center justify-between mb-4">

            {{-- header --}}
            <x-widget.table-header />

        </div>

        {{-- table --}}
        <div class="grid grid-cols-6 gap-4 max-lg:grid-cols-3 max-md:grid-cols-2">

            @forelse ($meals as $meal)
                <label wire:key="meal-{{ $meal->id }}"
                    class="relative cursor-pointer rounded-xl border p-3 transition hover:bg-white/10 hover:shadow-md
                {{ $meal->is_guest_meal ? 'border-green-500/70 bg-white/10' : 'border-zinc-700' }}">

                    <input type="checkbox" class="hidden" wire:click="toggleMeal({{ $meal->id }})"
                        {{ $meal->is_guest_meal ? 'checked' : '' }}>

                    <div class="flex flex-col items-center gap-2 text-center">

                        @if ($meal->image)
                            <img loading="lazy" class="w-20 h-20 object-cover rounded-lg"
                                src="{{ asset('storage/' . $meal->image) }}" alt="{{ $meal->name }}">
                        @else
                            {{-- <img loading="lazy" class="w-24 h-24 object-cover rounded-lg"
                                src="{{ asset('assets/admin/meal.png') }}" alt="{{ $meal->name }}"> --}}
                                <div class="text-7xl">🍛</div>
                        @endif

                        <div class="text-sm first-letter:uppercase">
                            {!! highlight($meal->name, $this->search) !!}
                        </div>

                        @if ($meal->is_guest_meal)
                            <span
                                class="absolute top-2 right-2 text-xs px-2 py-1 rounded bg-green-500/20 text-white border border-green-500/50">
                                Guest Meal
                            </span>
                        @endif

                    </div>

                </label>

            @empty

                <div class="col-span-6 text-center text-gray-500">
                    Not found item.
                </div>
            @endforelse

        </div>

        {{-- pagination --}}
        <div class="flex justify-end">
            {{ $meals->links('livewire::pagination') }}
        </div>

    </div>

</div>
