<?php

namespace App\Livewire\Frontend\PlanDetailsPage;

use App\Livewire\Frontend\PlanDetailsPage\Traits\PromoApply;
use App\Livewire\Frontend\PlanDetailsPage\Traits\Submit;
use App\Models\DietPlan;
use App\Models\Ingredient;
use App\Models\Plan;
use App\Models\PlanCategory;
use App\Models\PromoCode;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Plan')]

#[Layout('layouts::frontend')]
class PlanDetailsIndex extends Component
{
    public $dietPlans;
    public $ingredients;

    public $diet_plan_id;
    public $plan_category_id;
    public $days_of_week_selected;
    public $selected_plan_id;


    public $allergens = [];
    public $subscription_days;
    public $delivery_time;
    public $starting_date;
    public $expires_date;
    public $phone = "";
    public $house = "";
    public $road = "";
    public $block = "";
    public $area = "";
    public $additional_direction = "";

    public $promo_code = '';
    public $promoItem = null;
    public $originalPrice = 0.00;
    public $discountAmount = 0.00;
    public $finalPrice = 0.00;

    public $timezone;

    public function mount($price = 0)
    {
        $this->timezone = auth()->user()->timezone ?? 'UTC';
        $this->dietPlans = DietPlan::orderBy('name')->get(['id','name']);
        $this->ingredients = Ingredient::orderBy('name')->get(['id','name']);

        $this->originalPrice = (float) $price;
        $this->finalPrice = $this->originalPrice;


    }

    public function setTimezone($tz) {
        if (in_array($tz, \DateTimeZone::listIdentifiers())) {
            $this->timezone = $tz;
            if (auth()->check()) auth()->user()->update(['timezone' => $tz]);
        }
    }

    public function updatedDietPlanId($value)
    {
        // reset downstream selections and errors
        $this->reset(['plan_category_id', 'days_of_week_selected', 'selected_plan_id']);
        $this->resetValidation();
    }

    // computed: plan categories for selected diet plan
    #[Computed]
    public function getPlanCategoriesProperty()
    {
        if (!$this->diet_plan_id) {
            return collect();
        }

        return PlanCategory::where('diet_plan_id', $this->diet_plan_id)
            ->orderBy('name')
            ->get();
    }

    public function updatedPlanCategoryId($value)
    {
        $this->resetValidation();

        $this->days_of_week_selected = null;
        $this->selected_plan_id = null;

    }

    // computed: distinct days_of_week from plans filtered by category or diet plan
    #[Computed]
    public function getDaysOptionsProperty()
    {
        // nothing to show until category or diet plan selected
        if (!$this->plan_category_id) {
            return collect();
        }

        $query = Plan::query();

        if ($this->plan_category_id) {
            $query->where('plan_category_id', $this->plan_category_id);
        } elseif ($this->diet_plan_id) {
            $query->whereHas('planCategory', function ($q) {
                $q->where('diet_plan_id', $this->diet_plan_id);
            });
        }

        // return a collection of distinct values (preserves DB values)
        return $query->select('days_of_week')
            ->distinct()
            ->orderBy('days_of_week')
            ->pluck('days_of_week');
    }

    public function updatedDaysOfWeekSelected($value)
    {
        $this->resetValidation();
        $first = $this->plans->first(); // plans computed requires both category+days
        $this->selected_plan_id = $first ? $first->id : null;

    }

    // computed: plans filtered by selected category and optional days_of_week
    #[Computed]
    public function getPlansProperty()
    {

        if (!$this->plan_category_id || $this->days_of_week_selected === null || $this->days_of_week_selected === '') {
            return collect();
        }

        $query = Plan::where('plan_category_id', $this->plan_category_id)
                 ->where('days_of_week', $this->days_of_week_selected);

        return $query->with(['planCategory.mealTypes'])->orderBy('id')->get();
    }

    #[Computed]
    public function getSelectedPlanProperty()
    {
        if (!$this->selected_plan_id) {
            return null;
        }

        return Plan::with(['planCategory.mealTypes'])->find($this->selected_plan_id);
    }

    // starting date fixing
    public function getMinStartingDateProperty()
    {
        // delivery_time অপশনগুলোর earliest start time
        $slotStartMap = [
            'Morning 7am - 1pm'   => '07:00:00',
            'Afternoon 5pm - 8pm' => '17:00:00',
        ];

        // যদি delivery_time না থাকে, ডিফল্ট স্লট ধরো
        $slot = $this->delivery_time ?? 'Morning 7am - 1pm';
        $slotTime = $slotStartMap[$slot] ?? '09:00:00';

        // ইউজারের টাইমজোন ব্যবহার করো যদি থাকে, নাহলে অ্যাপ টাইমজোন
        $tz = $this->timezone ?? config('app.timezone');

        // লক্ষ্য সময়: এখন + 24 ঘন্টা (টাইমজোন সহ)
        $target = Carbon::now($tz)->addDay();

        // টার্গেটের তারিখে ওই স্লটের শুরু সময় — copy() ব্যবহার করে মূল target অপরিবর্তিত রাখি
        $slotDateTimeOnTarget = Carbon::parse($target->toDateString() . ' ' . $slotTime, $tz);

        // যদি স্লটের শুরু সময় target (now +24h) থেকে ছোট হয় -> min হবে পরের দিন
        if ($slotDateTimeOnTarget->lt($target)) {
            $minDate = $target->copy()->addDay()->toDateString();
        } else {
            $minDate = $target->toDateString();
        }

        return $minDate;
    }

    use PromoApply;

    use Submit;

    public function render()
    {
        return view('livewire.frontend.plan-details-page.plan-details-index', [
            'dietPlans' => $this->dietPlans,
            'ingredients' => $this->ingredients,
            'planCategories' => $this->planCategories,
            'daysOptions' => $this->daysOptions,
            'plans' => $this->plans,
            'selectedPlan' => $this->selectedPlan,
        ]);
    }
}
