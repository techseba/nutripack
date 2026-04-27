<div class="grid grid-cols-2 gap-2">
    <!-- Meals -->
    <div class="flex items-center" data-swiper-parallax="-2000" data-swiper-parallax-duration="400">
        <span class="text-3xl text-shadow-lg">🍛</span>
        <div>
            <p class="text-sm font-bold text-slate-800">
                {{ count($planCategory->mealTypes) }}</p>
            <p class="text-xs font-medium text-white">Meals</p>
        </div>
    </div>

    <!-- Snacks -->
    <div class="flex items-center" data-swiper-parallax="-3000" data-swiper-parallax-duration="500">
        <span class="text-3xl text-shadow-lg">🍕</span>
        <div>
            <p class="text-sm font-bold text-slate-800">1</p>
            <p class="text-xs font-medium text-white">Snacks</p>
        </div>
    </div>

    <!-- Calories -->
    <div class="flex items-center" data-swiper-parallax="-2000" data-swiper-parallax-duration="600">
        <span class="text-3xl text-shadow-lg">🔥</span>
        <div>
            <p class="text-sm font-bold text-slate-800">
                {{ round($planCategory->min_calories) . ' - ' . round($planCategory->max_calories) }}
            </p>
            <p class="text-xs font-medium text-white">Calories</p>
        </div>
    </div>

    <!-- Proteins -->
    <div class="flex items-center" data-swiper-parallax="-3000" data-swiper-parallax-duration="700">
        <span class="text-3xl text-shadow-lg">🥩</span>
        <div>
            <p class="text-sm font-bold text-slate-800">
                {{ round($planCategory->protein) }}%</p>
            <p class="text-xs font-medium text-white">Proteins</p>
        </div>
    </div>

    <!-- Carbs -->
    <div class="flex items-center" data-swiper-parallax="-2000" data-swiper-parallax-duration="800">
        <span class="text-3xl text-shadow-lg">🍞</span>
        <div>
            <p class="text-sm font-bold text-slate-800">
                {{ round($planCategory->carbs) }}%</p>
            <p class="text-xs font-medium text-white">Carbs</p>
        </div>
    </div>

    <!-- Fats -->
    <div class="flex items-center" data-swiper-parallax="-3000" data-swiper-parallax-duration="900">
        <span class="text-3xl text-shadow-lg">🫃🏻</span>
        <div>
            <p class="text-sm font-bold text-slate-800">
                {{ round($planCategory->fat) }}%
            </p>
            <p class="text-xs font-medium text-white">Fat</p>
        </div>
    </div>
</div>
