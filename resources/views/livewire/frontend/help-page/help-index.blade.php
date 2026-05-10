<main class="max-w-md mx-auto min-h-screen bg-lemon text-slate-900 px-4 overflow-hidden">

    <section class="pt-8 pb-15">

        {{-- Header --}}
        <div
            class="relative overflow-hidden rounded-4xl bg-linear-to-br from-emerald-300 via-orange-100 to-orange-50 p-7 shadow-2xl">

            <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/30 rounded-full blur-2xl"></div>
            <div class="absolute bottom-0 left-0 w-28 h-28 bg-white/20 rounded-full blur-xl"></div>

            <div class="relative z-10">
                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/70 text-xs font-black uppercase tracking-widest text-slate-700">
                    💬 Customer Support
                </span>

                <h1 class="mt-5 text-4xl font-black leading-tight tracking-tight text-slate-900">
                    Help Center
                </h1>

                <p class="mt-4 text-[15px] leading-7 text-slate-700 max-w-sm">
                    Need help with your meal subscription, delivery, payment, or account?
                    We’re here to help you anytime.
                </p>

                <div class="mt-6 flex items-center gap-3">
                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank"
                        class="inline-flex items-center justify-center gap-2 px-5 py-3 rounded-2xl bg-slate-900 text-white font-semibold shadow-lg hover:scale-[1.02] active:scale-[0.98] transition-all duration-300">
                        📞 Contact Support
                    </a>
                </div>
            </div>

        </div>

        {{-- Quick Actions --}}
        <div class="grid grid-cols-2 gap-4 mt-6">

            <button
                class="group bg-white rounded-3xl p-5 shadow-lg border border-white/60 hover:-translate-y-1 transition-all duration-300 text-left">
                <div
                    class="w-14 h-14 rounded-2xl bg-emerald-100 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                    🚚
                </div>

                <h2 class="font-black text-slate-900 text-lg">
                    Track Order
                </h2>

                <p class="text-sm text-slate-500 leading-6 mt-1">
                    Check your delivery status easily.
                </p>
            </button>

            <button
                class="group bg-white rounded-3xl p-5 shadow-lg border border-white/60 hover:-translate-y-1 transition-all duration-300 text-left">
                <div
                    class="w-14 h-14 rounded-2xl bg-sky-100 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                    💳
                </div>

                <h2 class="font-black text-slate-900 text-lg">
                    Payments
                </h2>

                <p class="text-sm text-slate-500 leading-6 mt-1">
                    Billing, invoices & refund help.
                </p>
            </button>

            <button
                class="group bg-white rounded-3xl p-5 shadow-lg border border-white/60 hover:-translate-y-1 transition-all duration-300 text-left">
                <div
                    class="w-14 h-14 rounded-2xl bg-orange-100 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                    🥗
                </div>

                <h2 class="font-black text-slate-900 text-lg">
                    Meal Plans
                </h2>

                <p class="text-sm text-slate-500 leading-6 mt-1">
                    Manage your subscriptions & meals.
                </p>
            </button>

            <button
                class="group bg-white rounded-3xl p-5 shadow-lg border border-white/60 hover:-translate-y-1 transition-all duration-300 text-left">
                <div
                    class="w-14 h-14 rounded-2xl bg-violet-100 flex items-center justify-center text-2xl mb-4 group-hover:scale-110 transition-transform">
                    ⚙️
                </div>

                <h2 class="font-black text-slate-900 text-lg">
                    Account
                </h2>

                <p class="text-sm text-slate-500 leading-6 mt-1">
                    Profile, password & settings help.
                </p>
            </button>

        </div>

        {{-- FAQ --}}
        <div class="mt-8">

            <div class="flex items-center justify-between mb-4">
                <h2 class="text-2xl font-black text-slate-900">
                    Frequently Asked
                </h2>

                <span class="text-sm text-slate-500 font-semibold">
                    FAQ
                </span>
            </div>

            <div class="space-y-4">

                {{-- FAQ Item --}}
                <div class="bg-white rounded-3xl p-5 shadow-lg border border-white/60">

                    <div class="flex items-start gap-4">
                        <div
                            class="shrink-0 w-12 h-12 rounded-2xl bg-lime-100 flex items-center justify-center text-xl">
                            ❓
                        </div>

                        <div>
                            <h3 class="font-black text-slate-900 text-lg">
                                How do I change my meal plan?
                            </h3>

                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                You can update your meal subscription anytime from your account dashboard
                                before the next billing cycle starts.
                            </p>
                        </div>
                    </div>

                </div>

                {{-- FAQ Item --}}
                <div class="bg-white rounded-3xl p-5 shadow-lg border border-white/60">

                    <div class="flex items-start gap-4">
                        <div
                            class="shrink-0 w-12 h-12 rounded-2xl bg-sky-100 flex items-center justify-center text-xl">
                            🚚
                        </div>

                        <div>
                            <h3 class="font-black text-slate-900 text-lg">
                                What if my delivery is late?
                            </h3>

                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                Delivery delays can happen because of traffic or weather conditions.
                                Our support team will assist you with real-time updates.
                            </p>
                        </div>
                    </div>

                </div>

                {{-- FAQ Item --}}
                <div class="bg-white rounded-3xl p-5 shadow-lg border border-white/60">

                    <div class="flex items-start gap-4">
                        <div
                            class="shrink-0 w-12 h-12 rounded-2xl bg-orange-100 flex items-center justify-center text-xl">
                            💰
                        </div>

                        <div>
                            <h3 class="font-black text-slate-900 text-lg">
                                Can I request a refund?
                            </h3>

                            <p class="mt-2 text-sm leading-7 text-slate-600">
                                Refund requests are reviewed based on meal preparation and delivery status.
                                Please contact support for assistance.
                            </p>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        {{-- Contact --}}
        <div
            class="mt-8 relative overflow-hidden rounded-4xl bg-slate-900 text-white p-6 shadow-2xl">

            <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-2xl"></div>

            <div class="relative z-10">

                <span
                    class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 text-xs font-black uppercase tracking-widest text-white/80">
                    24/7 Support
                </span>

                <h2 class="mt-4 text-3xl font-black leading-tight">
                    Still Need Help?
                </h2>

                <p class="mt-3 text-sm text-white/70 leading-7">
                    Our support team is always ready to help you with subscriptions,
                    delivery issues, or account problems.
                </p>

                <div class="mt-6 space-y-3">

                    <a href="https://wa.me/{{ env('WHATSAPP_NUMBER') }}" target="_blank"
                        class="flex items-center gap-4 p-4 rounded-2xl bg-white/10 hover:bg-white/15 transition-colors">
                        <div
                            class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-xl">
                            📞
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-widest text-white/50 font-black">
                                Phone Support
                            </p>

                            <h3 class="font-bold text-lg">
                                {{ env('WHATSAPP_NUMBER') }}
                            </h3>
                        </div>
                    </a>

                    <a href="mailto:{{ env('ADMIN_EMAIL') }}" target="_blank"
                        class="flex items-center gap-4 p-4 rounded-2xl bg-white/10 hover:bg-white/15 transition-colors">
                        <div
                            class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center text-xl">
                            ✉️
                        </div>

                        <div>
                            <p class="text-xs uppercase tracking-widest text-white/50 font-black">
                                Email Support
                            </p>

                            <h3 class="font-bold text-lg">
                                {{ env('ADMIN_EMAIL') }}
                            </h3>
                        </div>
                    </a>

                </div>

            </div>

        </div>

    </section>

</main>
