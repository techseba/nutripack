<main x-data="{
    meal_type: 'all',
    modalOpen: false,
}" class="max-w-md mx-auto min-h-screen bg-lemon text-slate-900 px-4 overflow-hidden">

    @if (env('MAINTENANCE_MODE'))
        <div class="mt-4">
            <x-widget.marquee />
        </div>
    @endif

    <div
        class="w-full max-w-xl mt-6 mx-auto bg-white/80 backdrop-blur-md rounded-3xl shadow-2xl border border-amber-100 p-10 flex flex-col items-center text-center transition-all duration-300 hover:shadow-amber-200/50">

        <!-- Title -->
        <h1
            class="text-3xl sm:text-4xl font-extrabold bg-linear-to-r from-amber-500 to-rose-500 text-transparent bg-clip-text mb-3">
            No Active Subscription
        </h1>

        <!-- Description -->
        <p class="text-slate-600 mb-5 px-3 leading-relaxed">
            You currently do not have any active subscription plan. To access our services, you can subscribe to one of
            our available plans based on your needs.
        </p>

        <!-- Extra Info -->
        <p class="text-sm text-slate-500 mb-7">
            Choose a suitable plan and get started today.
        </p>

        <!-- Action Button -->
        <a href="{{ route('plan') }}" wire:navigate
            class="inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-linear-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg hover:scale-105 hover:shadow-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300">
            View Subscription Plans
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>

    </div>

</main>
