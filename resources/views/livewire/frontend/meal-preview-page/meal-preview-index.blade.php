<main class="max-w-md mx-auto min-h-screen bg-lemon text-slate-900 overflow-hidden">

    <div class="relative w-full max-w-md min-h-screen bg-white overflow-hidden">

        <a href="{{ route('home') }}" wire:navigate
            class="absolute top-6 right-6 p-2 bg-slate-100 rounded-full text-slate-500 hover:bg-slate-200 transition-colors z-10">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                </path>
            </svg>
        </a>

        <div class="">
            <div class="h-64 md:h-full">

                @if ($meal->image)
                    <img src="{{ asset('storage/' . $meal->image) }}" alt="{{ $meal->name }}"
                        class="w-full h-full object-cover" referrerPolicy="no-referrer" loading="lazy">
                @else
                    <img src="{{ asset('assets/healthymeal.webp') }}" alt="{{ $meal->name }}"
                        class="w-full h-full object-cover" referrerPolicy="no-referrer" loading="lazy">
                @endif

            </div>
            <div class="p-6 pb-15 space-y-2">
                <div>
                    <span
                        class="px-3 py-1 bg-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-widest rounded-full mb-2 inline-block">{{ $meal->mealType->name }}</span>
                    <h2 class="text-2xl font-bold text-slate-900 first-letter:capitalize">{{ $meal->name }}</h2>
                </div>
                <p class="text-slate-600 leading-relaxed">{{ $meal->description ? $meal->description : 'Healthy meal Healthy calories.' }}</p>

                <div class="grid grid-cols-4 gap-2">

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">calories</p>
                        <p class="text-lg font-bold text-slate-800">{{ $meal->calories ?? '-' }}</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Protein</p>
                        <p class="text-lg font-bold text-slate-800">{{ $meal->protein ?? '-' }}g</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Carbs</p>
                        <p class="text-lg font-bold text-slate-800">{{ $meal->carbs ?? '-' }}g</p>
                    </div>

                    <div class="p-4 bg-slate-50 rounded-2xl">
                        <p class="text-[10px] font-black text-slate-400 uppercase mb-1">Fat</p>
                        <p class="text-lg font-bold text-slate-800">{{ $meal->fat ?? '-' }}g</p>
                    </div>

                </div>

                <div>
                    <h2 class="font-medium text-lg mb-1">Ingredients</h2>
                    <div class="grid grid-cols-4 gap-2">

                        @forelse ($meal->ingredients ?? [] as $ingredient)
                            <div class="text-center">
                                @if ($ingredient->image)
                                    <img class="w-10 h-10 m-auto bg-white border border-slate-400 rounded-full object-cover"
                                        src="{{ asset('storage/' . $ingredient->image) }}"
                                        alt="{{ $ingredient->name }}">
                                @else
                                    <div
                                        class="inline-flex justify-center items-center w-10 h-10 m-auto bg-white border border-slate-400 rounded-full text-2xl">
                                        💧</div>
                                @endif
                                <p class="text-[10px] font-black text-slate-800 uppercase mb-1">
                                    {{ $ingredient->name }}
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


</main>
