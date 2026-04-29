<div x-show="modalOpen" x-on:open-modal.window="modalOpen = true" x-on:close-modal.window="modalOpen = false;" x-cloak
    class="fixed inset-0 z-100 flex items-center justify-center px-6" @keydown.escape.window="modalOpen = false">

    <!-- Backdrop -->
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false; $wire.resetPage();"></div>

    <!-- Modal Content -->
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-10"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-10"
        class="relative w-full max-w-md h-screen bg-white rounded-[2.5rem] shadow-2xl overflow-hidden">

        <button @click="modalOpen = false; $wire.resetPage();"
            class="absolute top-6 right-6 p-2 bg-slate-100 rounded-full text-slate-500 hover:bg-slate-200 transition-colors z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </button>

        <div class="">
            <div class="h-64 md:h-full">

                @if ($this->image)
                    <img src="{{ asset('storage/' . $this->image) }}" alt="{{ $this->name }}"
                        class="w-full h-full object-cover" referrerPolicy="no-referrer" loading="lazy">
                @else
                    <img src="{{ asset('assets/logo.jpg') }}" alt="{{ $this->name }}"
                        class="w-full h-full object-cover" referrerPolicy="no-referrer" loading="lazy">
                @endif

            </div>
            <div class="p-6 space-y-2">
                <div>
                    <span
                        class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full mb-2 inline-block">{{ $this->mealDietPlans }}</span>
                    <h2 class="text-2xl font-bold text-slate-900 first-letter:capitalize">{{ $this->name }}</h2>
                </div>
                <p class="text-slate-600 leading-relaxed">{{ $this->description }}</p>

                <div class="grid grid-cols-4 gap-2">

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">calories</p>
                        <p class="text-lg font-bold text-slate-800">{{ $this->calories ?? '-' }}</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Protein</p>
                        <p class="text-lg font-bold text-slate-800">{{ $this->protein ?? '-' }}g</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Carbs</p>
                        <p class="text-lg font-bold text-slate-800">{{ $this->carbs ?? '-' }}g</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Fat</p>
                        <p class="text-lg font-bold text-slate-800">{{ $this->fat ?? '-' }}g</p>
                    </div>

                </div>

                <div>
                    <h2 class="font-medium text-lg mb-1">Ingredients</h2>
                    <div class="grid grid-cols-4 gap-2">

                        @forelse ($this->ingredients ?? [] as $ingredient)
                            <div class="text-center">
                                @if ($ingredient->image)
                                    <img class="w-10 h-10 m-auto bg-white border border-slate-400 rounded-full object-cover"
                                        src="{{ asset('storage/' . $ingredient->image) }}"
                                        alt="{{ $ingredient->name }}">
                                @else
                                    <div
                                        class="inline-flex justify-center items-center w-10 h-10 m-auto bg-white border border-slate-400 rounded-full text-2xl">
                                        🦐</div>
                                @endif
                                <p class="text-[10px] font-black text-slate-800 uppercase mb-1">{{ $ingredient->name }}
                                </p>
                            </div>
                        @empty
                            <div class="p-4 bg-slate-50 rounded-2xl">
                                <p class="text-lg font-bold text-slate-800">No contains</p>
                            </div>
                        @endforelse

                    </div>
                </div>

            </div>
        </div>
    </div>
</div>
