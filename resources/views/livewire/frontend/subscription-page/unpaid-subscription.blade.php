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

        <!-- Icon / Badge -->
        <div
            class="mx-auto flex items-center justify-center w-24 h-24 rounded-full bg-gradient-to-br from-amber-100 to-rose-100 text-amber-500 text-2xl font-extrabold mb-5 shadow-inner animate-pulse">
            INFO
        </div>

        <!-- Title -->
        <h1
            class="text-3xl sm:text-4xl font-extrabold bg-gradient-to-r from-amber-500 to-rose-500 text-transparent bg-clip-text mb-3">
            Unpaid User Notice
        </h1>

        <!-- Description -->
        <p class="text-slate-600 mb-5 px-3 leading-relaxed">
            You are currently registered as an unpaid user. We have received your request and our team is reviewing it.
            We will get in touch with you very soon.
        </p>

        <!-- Divider -->
        <div class="w-16 h-1 bg-gradient-to-r from-amber-400 to-rose-400 rounded-full mb-5"></div>

        <!-- Extra Info -->
        <p class="text-sm text-slate-500 mb-7">
            Thank you for your patience and for being with us.
        </p>

        <!-- Action Button -->
        <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank"
            class="inline-flex items-center gap-2 px-7 py-3 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-semibold shadow-lg hover:scale-105 hover:shadow-xl hover:from-emerald-600 hover:to-teal-600 transition-all duration-300">
            Contact Support
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3" />
            </svg>
        </a>

    </div>

</main>
