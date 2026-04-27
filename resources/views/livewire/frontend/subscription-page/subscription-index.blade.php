<main x-data="{
    meal_type: 'all',
    modalOpen: false,
}" class="max-w-md mx-auto min-h-screen bg-lemon text-slate-900 px-4 overflow-hidden">

    @if (env('MAINTENANCE_MODE'))
        <div class="mt-4">
            <x-widget.marquee />
        </div>
    @endif

    {{-- Subscription container --}}
    <div class="pt-6 pb-20">

        {{-- Empty state --}}
        @if (empty($subscriber))
            <div class="bg-white/80 rounded-xl p-6 shadow-md border border-slate-200">
                <h2 class="text-2xl font-semibold mb-2">No active subscription</h2>
                <p class="text-sm text-slate-700 mb-4">
                    You don't have an active subscription yet. Subscribe now to get weekly fresh meals delivered to your
                    door.
                </p>
                <div class="flex gap-3">
                    <a href="{{ route('plan') }}"
                        class="inline-block px-4 py-2 bg-slate-900 text-white rounded-lg shadow hover:bg-slate-800">
                        Subscribe now
                    </a>
                    <a href=""
                        class="inline-block px-4 py-2 border border-slate-300 rounded-lg text-slate-800 hover:bg-slate-50">
                        Learn more
                    </a>
                </div>
            </div>
            {{-- stop rendering rest --}}
            @return
        @endif

        {{-- Summary card --}}
        <section class="mt-2 bg-white/90 rounded-2xl p-4 shadow-md border border-slate-200">

            <div class="flex justify-between">
                <h3 class="text-lg font-bold">{{ $subscriber->plan->planCategory->name ?? 'Plan' }}</h3>

                <div class="text-right space-y-0.5">
                    <p class="text-sm text-slate-500 whitespace-nowrap">Plan price</p>
                    <p class="text-sm font-semibold">{{ number_format($subscriber->total ?? 0, 2) }}</span>
                </div>
            </div>

            <div class="flex items-start justify-between gap-4">
                <div>
                    <p class="text-sm text-slate-600 mt-1">
                        <span class="font-medium">Starts:</span>
                        <span>{{ \Carbon\Carbon::parse($subscriber->starting_date)->format('d M, Y') }}</span>
                        <span class="mx-2 text-slate-300">•</span>
                        <span class="font-medium">Expires:</span>
                        <span>{{ \Carbon\Carbon::parse($subscriber->expires_date)->format('d M, Y') }}</span>
                    </p>

                    <p class="mt-3 inline-flex items-center gap-2 text-sm">
                        <span
                            class="px-2 py-1 rounded-full text-xs font-semibold
                            @if ($subscriber->payment_status === 'paid') bg-emerald-100 text-emerald-800
                            @else @endif">
                            {{ ucfirst($subscriber->payment_status ?? 'unpaid') }}
                        </span>
                        <span class="text-slate-500">|</span>
                        <span class="text-slate-500 text-xs">Delivery: <span
                                class="font-medium">{{ $subscriber->delivery_time ?? '—' }}</span></span>
                    </p>
                </div>
            </div>

            {{-- Actions --}}
            {{-- <div class="mt-4 flex flex-wrap gap-2">
                <button wire:click="cancel"
                    class="px-3 py-2 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700">Cancel</button>
                <button wire:click="pause"
                    class="px-3 py-2 bg-slate-800 text-white rounded-lg text-sm hover:bg-slate-900">Pause</button>
                <button wire:click="changeDays"
                    class="px-3 py-2 border border-slate-300 rounded-lg text-sm hover:bg-slate-50">Change delivery
                    days</button>
            </div> --}}
        </section>

        {{-- Delivery calendar header --}}
        <section class="mt-5">
            <div class="flex items-center justify-between mb-3">
                <h4 class="text-base font-semibold">Your subscriptions period</h4>
                <div class="text-sm text-slate-600">Today is
                    <span class="font-medium">{{ now()->format('d F Y') }}</span>
                </div>
            </div>


            {{-- @forelse ($subscriber->deliveryDays as $deliveryDay)
                {{ \Carbon\Carbon::parse($deliveryDay->delivery_date)->format('d M') }}
            @empty
                not
            @endforelse --}}

            {{-- Week grid --}}
            <div class="grid grid-cols-2 gap-3">

                @foreach ($subscriber->deliveryDays as $deliveryDay)
                    @php
                        $items = $perDay[$deliveryDay->delivery_date] ?? [];
                        $isToday = \Carbon\Carbon::parse($deliveryDay->delivery_date)->isToday();
                    @endphp

                    <div
                        class="bg-white/90 rounded-xl p-3 shadow-sm border border-emerald-200 hover:border-orange-300 group hover:bg-emerald-500 transition flex flex-col h-full">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <div class="text-xs text-slate-500 group-hover:text-white/80">
                                    {{ \Carbon\Carbon::parse($deliveryDay->delivery_date)->format('D') }}
                                </div>
                                <div class="text-sm font-medium group-hover:text-white">
                                    {{ \Carbon\Carbon::parse($deliveryDay->delivery_date)->format('d M Y') }}</div>
                            </div>
                            @if ($isToday)
                                <div
                                    class="text-xs px-2 py-1 bg-emerald-100 text-emerald-800 group-hover:text-black rounded-full">
                                    Today</div>
                            @endif
                        </div>

                        <div class="flex-1 overflow-auto">
                            @if (count($items) === 0)
                                <div class="text-xs text-slate-400 group-hover:text-white/80">No delivery</div>
                            @else
                                <ul class="space-y-2">
                                    @foreach ($items as $food)
                                        <li class="flex items-start gap-3">
                                            <div
                                                class="w-10 h-10 rounded-md bg-slate-100 flex items-center justify-center text-slate-700 text-sm font-semibold">
                                                {{ strtoupper(substr($food['name'] ?? ($food->name ?? 'Item'), 0, 1)) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium">
                                                    {{ $food['name'] ?? ($food->name ?? 'Item') }}</div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $food['quantity'] ?? ($food->pivot->quantity ?? '1') }} ×
                                                    {{ $food['size'] ?? '' }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="mt-3 text-right">
                            <button wire:click="showMeals('{{ $deliveryDay->delivery_date }}')"
                                class="text-xs text-slate-600 border py-1 px-2 rounded cursor-pointer border-emerald-400 font-medium bg-emerald-200 group-hover:border-orange-300 group-hover:bg-orange-400 group-hover:text-white">
                                Open
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>


            @include('frontend.subscription-page.modal.daywise-meal-modal')
            {{-- Week grid --}}
            {{-- <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                @php
                    // Ensure $perDay is an array keyed by date strings for the current week
                    $weekDays = collect(range(0, 6))->map(fn($i) => now()->startOfWeek()->addDays($i)->format('Y-m-d'));
                @endphp

                @foreach ($weekDays as $day)
                    @php
                        $items = $perDay[$day] ?? [];
                        $isToday = \Carbon\Carbon::parse($day)->isToday();
                    @endphp

                    <div class="bg-white/90 rounded-xl p-3 shadow-sm border border-slate-200 flex flex-col h-full">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <div class="text-xs text-slate-500">{{ \Carbon\Carbon::parse($day)->format('D') }}
                                </div>
                                <div class="text-sm font-medium">{{ \Carbon\Carbon::parse($day)->format('d M') }}</div>
                            </div>
                            @if ($isToday)
                                <div class="text-xs px-2 py-1 bg-emerald-100 text-emerald-800 rounded-full">Today</div>
                            @endif
                        </div>

                        <div class="flex-1 overflow-auto">
                            @if (count($items) === 0)
                                <div class="text-xs text-slate-400">No delivery</div>
                            @else
                                <ul class="space-y-2">
                                    @foreach ($items as $food)
                                        <li class="flex items-start gap-3">
                                            <div
                                                class="w-10 h-10 rounded-md bg-slate-100 flex items-center justify-center text-slate-700 text-sm font-semibold">
                                                {{ strtoupper(substr($food['name'] ?? ($food->name ?? 'Item'), 0, 1)) }}
                                            </div>
                                            <div class="flex-1">
                                                <div class="text-sm font-medium">
                                                    {{ $food['name'] ?? ($food->name ?? 'Item') }}</div>
                                                <div class="text-xs text-slate-500">
                                                    {{ $food['quantity'] ?? ($food->pivot->quantity ?? '1') }} ×
                                                    {{ $food['size'] ?? '' }}</div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="mt-3 text-right">
                            <button wire:click="skipDay('{{ $day }}')"
                                class="text-xs text-slate-600 hover:text-slate-800">Skip</button>
                        </div>
                    </div>
                @endforeach
            </div> --}}

        </section>

        {{-- Footer quick actions --}}
        <section class="mt-6">
            <div class="bg-white/90 rounded-xl p-4 shadow-sm border border-slate-200 flex items-center justify-between">
                <div>
                    <div class="text-sm text-slate-600">Need help?</div>
                    <div class="text-sm font-medium">Contact our support for changes</div>
                </div>
                <div>
                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank"
                        class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Contact</a>
                </div>
            </div>
        </section>

    </div>

</main>
