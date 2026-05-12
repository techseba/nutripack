<div x-show="modalOpen" x-on:open-modal.window="modalOpen = true" x-on:close-modal.window="modalOpen = false;" x-cloak
    class="fixed inset-0 z-100 flex items-center justify-center px-6" @keydown.escape.window="modalOpen = false">

    <!-- Backdrop -->
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" @click="modalOpen = false;"></div>

    <!-- Modal Content -->
    <div x-show="modalOpen" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-95 translate-y-10"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-95 translate-y-10"
        class="modal-content fixed top-0 left-0 right-0 mx-auto max-w-md bg-lemon backdrop-blur-sm min-h-screen max-h-screen overflow-y-auto">


        <div class="pb-10">
            <h1 class="text-xl font-bold text-center my-4 text-slate-700">{{ $selectedDate }}</h1>

            <!-- Example: modal body or inline section -->
            <div class="mx-2">
                @if (empty($groupedMeals))
                    <div class="w-full max-w-md mt-20 mx-auto bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-slate-200 p-6 flex flex-col items-center text-center"
                        role="status" aria-live="polite">
                        <!-- Icon -->
                        <div
                            class="flex items-center justify-center w-20 h-20 rounded-full bg-emerald-50 text-emerald-600 mb-4 shadow-inner animate-[pulse_2.5s_ease-in-out_infinite]">
                            <svg class="w-10 h-10" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                <path d="M12 8v4" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 16h.01" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z"
                                    stroke="currentColor" stroke-width="1.2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>

                        <!-- Title -->
                        <h2 class="text-2xl font-extrabold text-slate-800 mb-1">No meals found</h2>

                        <!-- Subtitle -->
                        <p class="text-sm text-slate-600 mb-4 px-2">
                            No matches were found for the selected date. Please go back or select a different date from
                            the options below.
                        </p>

                        <!-- Actions -->
                        <div class="flex gap-3 mt-2">
                            <button @click="modalOpen = false;"
                                class="inline-flex items-center gap-2 px-3 py-1.5 text-sm rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                Go back
                            </button>

                            <button @click="modalOpen = false;"
                                class="inline-flex items-center gap-2 px-4 py-2 text-sm rounded-lg border border-emerald-200 bg-white text-emerald-700 hover:bg-emerald-50 font-medium focus:outline-none focus:ring-2 focus:ring-emerald-100 transition">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                                    <path
                                        d="M8 7V3M16 7V3M3 11h18M5 21h14a2 2 0 0 0 2-2V8a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v11a2 2 0 0 0 2 2z"
                                        stroke="currentColor" stroke-width="1.2" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                                Choose another date
                            </button>
                        </div>

                        <!-- Helper text -->
                        <p class="mt-4 text-xs text-slate-400 px-4">
                            Tip: If you are using a specific diet or filter, try changing it temporarily.
                        </p>
                    </div>
                @else
                    @foreach ($groupedMeals as $mealTypeName => $meals)
                        <section class="mb-8">

                            <style>
                                .modal-content::-webkit-scrollbar {
                                    display: none;
                                }

                                @keyframes shine {
                                    0% {
                                        background-position: 0% 50%;
                                    }

                                    50% {
                                        background-position: 100% 50%;
                                    }

                                    100% {
                                        background-position: 0% 50%;
                                    }
                                }

                                .button-bg {
                                    background: conic-gradient(from 0deg, #00F5FF, #000, #000, #00F5FF, #000, #000, #000, #00F5FF);
                                    background-size: 300% 300%;
                                    animation: shine 6s ease-out infinite;
                                }
                            </style>
                            <div
                                class="button-bg rounded-full mb-3 p-0.5 hover:scale-105 transition duration-300 active:scale-100">
                                <button
                                    class="px-8 w-full text-sm py-2.5 text-white rounded-full font-medium bg-slate-800">
                                    {{ $mealTypeName }}
                                </button>
                            </div>

                            <ul class="grid grid-cols-2 gap-2">
                                @foreach ($meals as $meal)
                                    <li class="group bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between transition-transform transform hover:-translate-y-1 hover:shadow-lg"
                                        role="listitem" aria-labelledby="meal-{{ $meal['id'] }}-name">
                                        <!-- Image / Icon -->
                                        <div
                                            class="w-full h-40 bg-emerald-50 flex items-center justify-center overflow-hidden">
                                            @if ($meal['image'])
                                                <img src="{{ asset('storage') . '/' . $meal['image'] }}"
                                                    alt="{{ $meal['name'] }}" class="w-full h-full object-cover"
                                                    loading="lazy" />
                                            @else
                                                <img src="{{ asset('assets/logo.jpg') }}" alt="{{ $meal['name'] }}"
                                                    class="w-full h-full object-cover" loading="lazy" />
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="relative p-4 flex-1 flex flex-col">
                                            <div class="flex items-start justify-between gap-3">
                                                <h4 id="meal-{{ $meal['id'] }}-name"
                                                    class="text-sm font-semibold text-slate-800 mt-1">
                                                    {{ $meal['name'] }}
                                                </h4>
                                            </div>

                                            <div class="mt-4 flex items-center justify-between gap-y-3">

                                                <div class="flex items-center gap-2">

                                                    @php $mealTypeId = $meal['meal_type_id'] ?? $meal['mealType']['id'] ?? null; @endphp

                                                    <button
                                                        @click="$dispatch('open-select-modal', { id: {{ $meal['id'] }} })"
                                                        @if (isset($lockedMealTypes[$mealTypeId]) && $lockedMealTypes[$mealTypeId]) disabled class="opacity-90 cursor-not-allowed" @endif
                                                        aria-label="Select {{ $meal['name'] }}">

                                                        {{-- Select (only show when NOT selected and NOT locked) --}}
                                                        @if (
                                                            !(isset($selectedMeals[$mealTypeId]) && $selectedMeals[$mealTypeId] == $meal['id']) &&
                                                                !(isset($lockedMealTypes[$mealTypeId]) && $lockedMealTypes[$mealTypeId]))
                                                            <div
                                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-md shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 transition">
                                                                <x-icons.mouse size="16" />
                                                                Select
                                                            </div>
                                                        @endif

                                                        @if (isset($selectedMeals[$mealTypeId]) && $selectedMeals[$mealTypeId] == $meal['id'])
                                                            <div
                                                                class="inline-flex items-center gap-1 text-green-500 text-xs font-medium focus:outline-none">
                                                                <x-icons.alarm size="16" />
                                                                Selected
                                                            </div>
                                                        @endif

                                                        @if (isset($lockedMealTypes[$mealTypeId]) && $lockedMealTypes[$mealTypeId])
                                                            <div
                                                                class="absolute right-2 top-1 inline-flex items-center gap-1 text-red-500 text-xs font-medium">
                                                                <x-icons.lock size="13" />
                                                                Locked
                                                            </div>
                                                        @endif
                                                    </button>

                                                    <a href="{{ route('meal.preview', ['id' => $meal['id']]) }}"
                                                        wire:navigate
                                                        class="inline-flex items-center gap-2 px-3 py-1.5 border border-slate-200 bg-white text-slate-700 text-xs rounded-md hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-100 transition">
                                                        Preview
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endforeach
                @endif
            </div>

            @php

                $carbon = Carbon\Carbon::parse($selectedDate)->setTimezone(config('app.timezone'));
                $this->filterDate = $carbon->toDateString(); // canonical date used by loadSelectedMeals()
                $this->selectedDate = $carbon->format('d F Y'); // display-friendly

                // Load meals for UI
                $meals = App\Models\DayWiseMeal::with(['meal', 'mealType'])
                    ->whereDate('date', $carbon->toDateString())
                    ->get();

                $mealsByType = $meals->groupBy('meal_type_id');


                $subscriber = App\Models\Subscriber::with(['plan.planCategory.mealTypes', 'deliveryDays'])
                    ->where('user_id', $this->userId)
                    ->where('status', 'active')
                    ->where('expires_date', '>=', now())
                    ->orderBy('starting_date', 'desc')
                    ->first();

                $subscriberMealTypes = $subscriber?->plan?->planCategory?->mealTypes ?? collect();
                $mealTypes = $subscriber?->mealTypes ?? collect();

                // $mealTypes = App\Models\MealType::where('name', 'Breakfast')->orWhere('name','Salad')->get();

                // dd($mealTypes);

                $mealTypes = $mealTypes ?? collect();

                $grouped = collect();

                foreach ($mealTypes as $mealType) {
                    $items = collect($mealsByType->get($mealType->id, []))
                        ->map(function ($row) {
                            return [
                                'id' => $row->meal->id ?? null,
                                'name' => $row->meal->name ?? '-',
                                'image' => $row->meal->image ?? null,
                                'meal_type_id' => $row->meal_type_id ?? null,
                            ];
                        })
                        ->values()
                        ->toArray();

                    if (!empty($items)) {
                        $grouped[$mealType->name] = $items;
                    }
                }

                $groupedMeals = $grouped->toArray();
            @endphp

            <h2 class="text-xl font-bold text-center my-4 text-slate-700">Additional Meals</h2>

            <!-- Additional meal section -->
            <div class="mx-2">
                @if (empty($groupedMeals))
                    <div class="w-full max-w-md mt-5 mx-auto bg-white/80 backdrop-blur-sm rounded-2xl shadow-lg border border-slate-200 p-6 flex flex-col items-center text-center"
                        role="status" aria-live="polite">
                        <!-- Title -->
                        <h2 class="text-xl text-slate-500 mb-1">No additional meals found</h2>
                    </div>
                @else
                    @foreach ($groupedMeals as $mealTypeName => $meals)
                        <section class="mb-8">

                            <style>
                                .modal-content::-webkit-scrollbar {
                                    display: none;
                                }

                                @keyframes shine {
                                    0% {
                                        background-position: 0% 50%;
                                    }

                                    50% {
                                        background-position: 100% 50%;
                                    }

                                    100% {
                                        background-position: 0% 50%;
                                    }
                                }

                                .button-bg {
                                    background: conic-gradient(from 0deg, #00F5FF, #000, #000, #00F5FF, #000, #000, #000, #00F5FF);
                                    background-size: 300% 300%;
                                    animation: shine 6s ease-out infinite;
                                }
                            </style>
                            <div
                                class="button-bg rounded-full mb-3 p-0.5 hover:scale-105 transition duration-300 active:scale-100">
                                <button
                                    class="px-8 w-full text-sm py-2.5 text-white rounded-full font-medium bg-slate-800">
                                    {{ $mealTypeName }}
                                </button>
                            </div>

                            <ul class="grid grid-cols-2 gap-2">
                                @foreach ($meals as $meal)
                                    <li class="group bg-white rounded-xl border border-slate-100 shadow-sm overflow-hidden flex flex-col justify-between transition-transform transform hover:-translate-y-1 hover:shadow-lg"
                                        role="listitem" aria-labelledby="meal-{{ $meal['id'] }}-name">
                                        <!-- Image / Icon -->
                                        <div
                                            class="w-full h-40 bg-emerald-50 flex items-center justify-center overflow-hidden">
                                            @if ($meal['image'])
                                                <img src="{{ asset('storage') . '/' . $meal['image'] }}"
                                                    alt="{{ $meal['name'] }}" class="w-full h-full object-cover"
                                                    loading="lazy" />
                                            @else
                                                <img src="{{ asset('assets/logo.jpg') }}" alt="{{ $meal['name'] }}"
                                                    class="w-full h-full object-cover" loading="lazy" />
                                            @endif
                                        </div>

                                        <!-- Content -->
                                        <div class="relative p-4 flex-1 flex flex-col">
                                            <div class="flex items-start justify-between gap-3">
                                                <h4 id="meal-{{ $meal['id'] }}-name"
                                                    class="text-sm font-semibold text-slate-800 mt-1">
                                                    {{ $meal['name'] }}
                                                </h4>
                                            </div>

                                            <div class="mt-4 flex items-center justify-between gap-y-3">

                                                <div class="flex items-center gap-2">

                                                    @php $mealTypeId = $meal['meal_type_id'] ?? $meal['mealType']['id'] ?? null; @endphp

                                                    <button
                                                        @click="$dispatch('open-select-modal', { id: {{ $meal['id'] }} })"
                                                        @if (isset($lockedMealTypes[$mealTypeId]) && $lockedMealTypes[$mealTypeId]) disabled class="opacity-90 cursor-not-allowed" @endif
                                                        aria-label="Select {{ $meal['name'] }}">

                                                        {{-- Select (only show when NOT selected and NOT locked) --}}
                                                        @if (
                                                            !(isset($selectedMeals[$mealTypeId]) && $selectedMeals[$mealTypeId] == $meal['id']) &&
                                                                !(isset($lockedMealTypes[$mealTypeId]) && $lockedMealTypes[$mealTypeId]))
                                                            <div
                                                                class="inline-flex items-center gap-1 px-3 py-1.5 bg-emerald-600 text-white text-xs font-medium rounded-md shadow-sm hover:bg-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-300 transition">
                                                                <x-icons.mouse size="16" />
                                                                Select
                                                            </div>
                                                        @endif

                                                        @if (isset($selectedMeals[$mealTypeId]) && $selectedMeals[$mealTypeId] == $meal['id'])
                                                            <div
                                                                class="inline-flex items-center gap-1 text-green-500 text-xs font-medium focus:outline-none">
                                                                <x-icons.alarm size="16" />
                                                                Selected
                                                            </div>
                                                        @endif

                                                        @if (isset($lockedMealTypes[$mealTypeId]) && $lockedMealTypes[$mealTypeId])
                                                            <div
                                                                class="absolute right-2 top-1 inline-flex items-center gap-1 text-red-500 text-xs font-medium">
                                                                <x-icons.lock size="13" />
                                                                Locked
                                                            </div>
                                                        @endif
                                                    </button>

                                                    <a href="{{ route('meal.preview', ['id' => $meal['id']]) }}"
                                                        wire:navigate
                                                        class="inline-flex items-center gap-2 px-3 py-1.5 border border-slate-200 bg-white text-slate-700 text-xs rounded-md hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-amber-100 transition">
                                                        Preview
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>
                        </section>
                    @endforeach
                @endif
            </div>

        </div>

    </div>


    @include('frontend.subscription-page.modal.select-confirmation')
    <!-- Actions -->
    <div
        class="fixed bottom-0 left-0 right-0 mx-auto max-w-md bg-emerald-500/80 backdrop-blur-xs text-white py-2 shadow-inner flex justify-around space-x-10 rounded-ss-xl rounded-se-xl z-50">

        <a href="{{ route('subscription') }}" wire:navigate
            class="inline-flex items-center gap-2 px-4 py-2 rounded-lg bg-amber-500 hover:bg-amber-600 cursor-pointer font-medium shadow-sm focus:outline-none focus:ring-2 focus:ring-emerald-300 transition">
            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                <path d="M15 19l-7-7 7-7" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
            Back to Home
        </a>


    </div>

    <x-widget.toast-subscription-message-frontend />
</div>
