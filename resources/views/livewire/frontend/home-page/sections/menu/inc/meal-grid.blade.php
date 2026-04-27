<section class="mt-4">
    <div class="grid grid-cols-1 gap-4">
        @foreach ($this->meals as $key => $menu)
            <div x-show="meal_type === 'all' || meal_type === '{{ strtolower($menu->mealType->name) }}'"
                x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-90"
                x-transition:enter-end="opacity-100 scale-100"
                class="card-hover bg-white border border-emerald-500/50 rounded-3xl overflow-hidden flex flex-col group cursor-pointer"
                wire:click.prevent="show({{ $menu->id }})">

                {{-- item --}}
                <div class="grid grid-cols-2">

                    <!-- Image Container -->
                    <div class="meal-image-zoom relative h-48">
                        @if ($menu->image)
                            <img src="{{ asset('storage/' . $menu->image) }}" alt="{{ $menu->name }}"
                                class="w-full h-full object-cover" referrerPolicy="no-referrer" loading="lazy">
                        @else
                            <img src="{{ asset('assets/logo.jpg') }}" alt="{{ $menu->name }}"
                                class="w-full h-full object-cover" referrerPolicy="no-referrer" loading="lazy">
                        @endif

                        <div class="absolute top-4 left-4">

                            @php
                                $gradients = [
                                    'bg-gradient-to-r from-orange-400 to-pink-500',
                                    'bg-gradient-to-r from-emerald-400 to-teal-500',
                                    'bg-gradient-to-r from-blue-400 to-indigo-500',
                                    'bg-gradient-to-r from-purple-400 to-pink-500',
                                    'bg-gradient-to-r from-yellow-400 to-orange-500',
                                    'bg-gradient-to-r from-cyan-400 to-blue-500',
                                ];

                                $gradient = $gradients[$loop->index % count($gradients)];
                            @endphp

                            <span
                                class="{{ $gradient }} px-3 py-1 text-white text-[10px] uppercase font-bold tracking-wider rounded-full shadow-md hover:scale-105 transition">
                                {{ $menu->mealType->name }}
                            </span>

                        </div>
                    </div>

                    <!-- Content -->
                    <div class="p-6 grow flex flex-col">
                        <div class="flex justify-between items-start mb-2">
                            <h3
                                class="font-bold text-slate-800 capitalize group-hover:text-emerald-500 transition-colors">
                                {{ $menu->name }}</h3>
                        </div>

                        <button
                            class="bg-amber-400 hover:bg-amber-500 transition-colors cursor-pointer text-slate-800 py-1.5 px-2 w-30 uppercase text-xs font-medium rounded-sm shadow-md">more
                            details</button>

                        <!-- Description -->
                        <p class="text-slate-500 text-xs mt-3">{{ $menu->description }}</p>

                        <!-- Nutrition Badges -->
                        <div class="mt-auto flex items-center justify-between">

                            <div class="text-slate-500">
                                <span class="text-lg">💪</span>
                                <span class="text-xs">{{ round($menu->protein) }} gm</span>
                            </div>

                            <div class="text-slate-500">
                                <span class="text-lg">🔥</span>
                                <span class="text-xs">{{ round($menu->calories) }} kcal</span>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        @endforeach

    </div>

    <!-- Infinite Scroll Trigger -->
    <div wire:intersect="loadMore" class="flex justify-center items-center py-10">
        <span wire:loading class="text-emerald-500">
            Loading more meals...
        </span>
    </div>
</section>
