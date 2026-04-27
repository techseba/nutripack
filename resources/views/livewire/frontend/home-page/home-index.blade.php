<main x-data="{
    meal_type: 'all',
    modalOpen: false,
}" class="max-w-md mx-auto min-h-screen bg-lemon text-slate-900 px-4 overflow-hidden">

    @if (env('MAINTENANCE_MODE'))
        <div class="mt-4">
            <x-widget.marquee />
        </div>
    @endif
    <!-- plan -->
    @include('frontend.home-page.sections.plan.plan')

    <!-- menu -->
    @include('frontend.home-page.sections.menu.menu')

</main>
