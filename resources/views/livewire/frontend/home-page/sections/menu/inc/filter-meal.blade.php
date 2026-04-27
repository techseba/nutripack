<section>
    <h2 class="font-bold text-slate-700 my-4 text-xl">Choose your Meal</h2>

    <div class="bg-white border border-slate-300 rounded-md text-center">

        <button @click="meal_type = 'all'"
            :class="meal_type === 'all' ? 'bg-emerald-500 text-white' : 'text-slate-600 hover:bg-emerald-500'"
            class="px-3 py-1.5 rounded text-xs md:text-sm border border-slate-50 font-medium transition-all cursor-pointer">All Meals</button>

        @foreach ($this->mealTypes as $mealType)
            <button @click="meal_type = '{{ strtolower($mealType->name) }}'"
                :class="meal_type === '{{ strtolower($mealType->name) }}' ? 'bg-emerald-500 text-white' :
                    'text-slate-600 hover:bg-slate-100'"
                class="px-3 py-1.5 rounded text-xs md:text-sm border border-slate-50 font-medium transition-all cursor-pointer">{{ $mealType->name }}</button>
        @endforeach

    </div>
</section>
