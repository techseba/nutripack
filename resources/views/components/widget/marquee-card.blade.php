<div>
    <!-- Row 1 -->
    <div class="w-full mx-auto max-w-7xl overflow-hidden relative">

        <div
            class="absolute left-0 top-0 h-full w-20 z-10 pointer-events-none bg-gradient-to-r from-white to-transparent">
        </div>

        <div class="marquee-inner flex transform-gpu min-w-[200%] pt-10 pb-5">

            {{$slot}}

        </div>

        <div
            class="absolute right-0 top-0 h-full w-20 md:w-40 z-10 pointer-events-none bg-gradient-to-l from-white to-transparent">
        </div>

    </div>

    <style>
        @keyframes marqueeScroll {
            0% {
                transform: translateX(0%);
            }

            100% {
                transform: translateX(-50%);
            }
        }

        .marquee-inner {
            animation: marqueeScroll 25s linear infinite;
        }

        .marquee-reverse {
            animation-direction: reverse;
        }
    </style>
</div>
