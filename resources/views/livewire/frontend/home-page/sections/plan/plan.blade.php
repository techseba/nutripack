<section>

    <div class="flex items-center justify-between">
        {{-- Plan header --}}
        <h2 class="font-bold text-slate-700 my-4 text-xl">Take your Plan</h2>

        {{-- clock --}}
        <x-widget.clock />
    </div>

    <!-- Slider main container -->
    <div class="swiper">
        <!-- Additional required wrapper -->
        <div class="swiper-wrapper">

            @forelse ($this->planCategories as $planCategory)
                <!-- Slides -->
                <div class="swiper-slide">
                    <div class="bg-emerald-500 py-4 px-6 border border-slate-500 rounded-xl">

                        <!-- Title -->
                        <h2 class="text-center text-2xl font-bold text-slate-800 mb-4" data-swiper-parallax="-100"
                            data-swiper-parallax-duration="1000">
                            {{ $planCategory->name }}
                        </h2>

                        <!-- Grid Layout -->
                        @include('frontend.home-page.sections.plan.inc.plan-card')

                        <button wire:click="showPlan"
                            class="text-center mt-4 mb-3 w-full bg-amber-400 text-black cursor-pointer uppercase text-md font-medium py-1.5 rounded-md hover:ring-1 hover:ring-slate-500">Get
                            Started</button>

                    </div>
                </div>
            @empty
                <!-- Slides -->
                <div class="swiper-slide">
                    <h1>Not Found</h1>
                </div>
            @endforelse

        </div>
        <!-- If we need pagination -->
        <div class="swiper-pagination"></div>
    </div>

</section>

<!-- Swiper Initialization Script -->
<script>
    function initPlanSwiper() {
        const plan = new Swiper('.swiper', {
            loop: true,
            autoplay: true,
            spaceBetween: 20,
            effect: "slide",
            parallax: true,
            slideShadows: true,
            limitRotation: true,
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    }

    // প্রথমবার লোডের সময় চালান
    document.addEventListener('DOMContentLoaded', initPlanSwiper);

    // wire:navigate এর পর আবার চালান
    document.addEventListener('livewire:navigated', initPlanSwiper);
</script>
