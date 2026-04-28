<?php

namespace App\Livewire\Frontend\SubscriptionPage;

use App\Models\DayWiseMeal;
use App\Models\Meal;
use App\Models\Subscriber;
use App\Models\SubscriberMealSelection;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Subscription')]
#[Layout('layouts::frontend')]
class SubscriptionIndex extends Component
{
    public ?int $userId = null;
    public array $groupedMeals = [];
    public ?Subscriber $subscriber = null;

    public $selectedDate = '';
    public $selectedMeals;

    public $subscriberMealTypes = [];




    // Livewire component properties
    public ?string $filterDate = null;
    public ?array $lockedMealTypes = []; // [meal_type_id => true]

    public function mount()
    {
        $user = Auth::user();
        if (!$user) {
            // guest users should be redirected to home or login
            return redirect()->route('home');
        }

        $this->userId = $user->id;

        // load the active subscription if any, eager load relations you need
        $this->subscriber = Subscriber::with(['plan.planCategory.mealTypes', 'deliveryDays'])
            ->where('user_id', $this->userId)
            ->where('status', 'active')
            ->where('expires_date', '>=', now())
            ->orderBy('starting_date', 'desc')
            ->first();

        $this->subscriberMealTypes = $this->subscriber?->plan?->planCategory?->mealTypes ?? collect();

        // ensure arrays are initialized
        $this->selectedMeals = [];
        $this->lockedMealTypes = [];

        $this->loadSelectedMeals();
    }

    public function updatedFilterDate()
    {
        $this->loadSelectedMeals();
    }

    protected function loadSelectedMeals(): void
    {
        if (!$this->subscriber) {
            $this->selectedMeals = [];
            $this->lockedMealTypes = [];
            return;
        }

        $date = $this->filterDate ?? now()->toDateString();

        $selections = SubscriberMealSelection::where('subscriber_id', $this->subscriber->id)
            ->whereDate('date', $date)
            ->get(['meal_type_id', 'meal_id']);

        $this->selectedMeals = [];
        $this->lockedMealTypes = [];

        foreach ($selections as $s) {
            $this->selectedMeals[$s->meal_type_id] = $s->meal_id;
            // lock every existing meal_type so it cannot be changed later
            $this->lockedMealTypes[$s->meal_type_id] = true;
        }
    }

    public function showMeals($date): void
    {
        // parse incoming date and set canonical filterDate (Y-m-d)
        $carbon = Carbon::parse($date)->setTimezone(config('app.timezone'));
        $this->filterDate = $carbon->toDateString(); // canonical date used by loadSelectedMeals()
        $this->selectedDate = $carbon->format('d F Y'); // display-friendly

        // Load meals for UI
        $meals = DayWiseMeal::with(['meal', 'mealType'])
            ->whereDate('date', $carbon->toDateString())
            ->get();

        $mealsByType = $meals->groupBy('meal_type_id');

        $subscriberMealTypes = $this->subscriberMealTypes ?? collect();
        $grouped = collect();

        foreach ($subscriberMealTypes as $mealType) {
            $items = collect($mealsByType->get($mealType->id, []))
                ->map(function ($row) {
                    return [
                        'id' => $row->meal->id ?? null,
                        'name' => $row->meal->name ?? '-',
                        'image' => $row->meal->image ?? null,
                        'meal_type_id' => $row->meal_type_id ?? null,
                    ];
                })
                ->values()
                ->toArray();

            if (!empty($items)) {
                $grouped[$mealType->name] = $items;
            }
        }

        $this->groupedMeals = $grouped->toArray();

        // IMPORTANT: refresh selections/locks for this date BEFORE opening modal
        $this->loadSelectedMeals();

        // Open modal (your JS listener)
        $this->dispatch('open-modal');
    }

    public function selectMeal(int $mealId): void
    {
        if (!$this->subscriber) {
            $this->dispatch('toast', message: 'Subscription not found', type: 'error');
            $this->dispatch('close-select-modal');
            return;
        }

        // Use canonical filterDate (Y-m-d). Fallback to today if not set.
        $dateYmd = $this->filterDate ?? now()->toDateString();

        $meal = Meal::with('mealType')->find($mealId);
        if (!$meal) {
            $this->dispatch('toast', message: 'Selected meal not found', type: 'error');
            $this->dispatch('close-select-modal');
            return;
        }

        $mealTypeId = $meal->meal_type_id ?? ($meal->mealType->id ?? null);
        if (!$mealTypeId) {
            $this->dispatch('toast', message: 'Meal type not found for this meal', type: 'error');
            $this->dispatch('close-select-modal');
            return;
        }

        // If this meal type is locked, do not allow any change
        if (!empty($this->lockedMealTypes[$mealTypeId])) {
            $this->dispatch('toast', message: 'এই টাইপের মিলটি ইতোমধ্যে লক করা আছে এবং পরিবর্তন করা যাবে না।', type: 'warning');
            $this->dispatch('close-select-modal');
            return;
        }

        // Double-check DB (race-safe)
        $existing = SubscriberMealSelection::where('subscriber_id', $this->subscriber->id)
            ->whereDate('date', $dateYmd)
            ->where('meal_type_id', $mealTypeId)
            ->first();

        if ($existing) {
            // someone else already selected — lock and inform
            $this->lockedMealTypes[$mealTypeId] = true;
            $this->selectedMeals[$mealTypeId] = $existing->meal_id;
            $this->dispatch('toast', message: 'এই টাইপের জন্য ইতোমধ্যে একটি সিলেকশন আছে এবং পরিবর্তন করা যাবে না।', type: 'warning');
            $this->dispatch('close-select-modal');
            return;
        }

        // Insert new selection and lock
        try {
            DB::table('subscriber_meal_selections')->insert([
                'subscriber_id' => $this->subscriber->id,
                'date' => $dateYmd,
                'meal_type_id' => $mealTypeId,
                'meal_id' => $mealId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (QueryException $e) {
            // unique constraint or other DB errors
            $this->lockedMealTypes[$mealTypeId] = true;
            $this->dispatch('toast', message: 'Could not save selection. It may already exist.', type: 'warning');
            $this->dispatch('close-select-modal');
            return;
        }

        // update local state immediately
        $this->selectedMeals[$mealTypeId] = $mealId;
        $this->lockedMealTypes[$mealTypeId] = true;

        $this->dispatch('toast', message: 'Meal selected and locked successfully', type: 'success');
        $this->dispatch('close-select-modal');
    }

    public $showPreview = false;
    public $selectedMeal = null;

    // Modal Data
    public $image;
    public $name;
    public $description;
    public $mealDietPlans;
    public $calories;
    public $protein;
    public $carbs;
    public $fat;
    public $fiber;
    public $ingredients = [];
    public $price;

    public function openPreview($id)
    {
        $this->selectedMeal = Meal::find($id);
        $selectedMeal = Meal::with('ingredients')->findOrFail($id);

        $this->image = $selectedMeal->image;
        $this->name = $selectedMeal->name;
        $this->mealDietPlans = $selectedMeal->dietPlans->pluck('name')->join(', ');
        $this->description = $selectedMeal->description;
        $this->calories = $selectedMeal->calories;
        $this->protein = $selectedMeal->protein;
        $this->carbs = $selectedMeal->carbs;
        $this->fat = $selectedMeal->fat;
        $this->ingredients = $selectedMeal->ingredients;

        $this->showPreview = true;
    }

    public function closePreview()
    {
        $this->showPreview = false;
        $this->selectedMeal = null;
    }

    public function render()
    {
        // if no active subscription show a different view or redirect
        if (!$this->subscriber) {
            return view('livewire.frontend.subscription-page.no-subscription');
        }

        // use payment_status safely
        if ($this->subscriber->payment_status === 'pending') {
            return view('livewire.frontend.subscription-page.pending-subscription');
        }

        // use payment_status safely
        if ($this->subscriber->payment_status === 'paid') {
            return view('livewire.frontend.subscription-page.subscription-index', [
                'subscriber' => $this->subscriber,
            ]);
        }

        return view('livewire.frontend.subscription-page.unpaid-subscription');
    }
}
