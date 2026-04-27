<?php

namespace App\Livewire\Frontend\SubscriptionPage\Traits;

use App\Models\SubscriberMealSelection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

trait MealSelection
{
    public int $subscriberId;
    public string $date; // 'Y-m-d' format

    // public $subscriberMealTypes; // Collection of MealType models
    public array $selectedMeals = []; // keyed by meal_type_id => meal_id

    public function mount(int $subscriberId, string $date, $subscriberMealTypes)
    {
        $this->subscriberId = $subscriberId;
        $this->date = Carbon::parse($date)->toDateString();
        $this->subscriberMealTypes = $subscriberMealTypes;

        // Load existing selections for this subscriber + date
        $existing = SubscriberMealSelection::where('subscriber_id', $this->subscriberId)
            ->whereDate('date', $this->date)
            ->get()
            ->keyBy('meal_type_id');

        foreach ($this->subscriberMealTypes as $mt) {
            $this->selectedMeals[$mt->id] = $existing->has($mt->id) ? $existing->get($mt->id)->meal_id : null;
        }
    }

    public function updatedSelectedMeals($value, $key)
    {
        // Optional: instant validation or UI side effects
        // $key is meal_type_id, $value is meal_id
    }

    public function saveSelections()
    {
        // Server side validation
        $rules = [
            'date' => 'required|date',
            'subscriberId' => 'required|integer|exists:subscribers,id',
            'selectedMeals' => 'required|array',
        ];

        // Each entry must be integer or null; ensure keys are valid meal_type ids
        foreach ($this->selectedMeals as $mealTypeId => $mealId) {
            $rules["selectedMeals.$mealTypeId"] = 'nullable|integer|exists:meals,id';
        }

        $this->validate($rules);

        // Normalize: only keep entries where a meal is selected
        $toSave = [];
        $now = now();
        foreach ($this->selectedMeals as $mealTypeId => $mealId) {
            if (empty($mealId)) continue;
            $toSave[] = [
                'subscriber_id' => $this->subscriberId,
                'date' => $this->date,
                'meal_type_id' => (int) $mealTypeId,
                'meal_id' => (int) $mealId,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        if (empty($toSave)) {
            $this->dispatch('toast', message: 'No meals selected', type: 'warning');
            return;
        }

        // Use upsert to insert or update atomically (unique constraint ensures single per meal_type)
        DB::transaction(function () use ($toSave) {
            DB::table('subscriber_meal_selections')
                ->upsert(
                    $toSave,
                    ['subscriber_id', 'date', 'meal_type_id'], // unique keys
                    ['meal_id', 'updated_at'] // columns to update on conflict
                );
        });

        $this->dispatch('toast', message: 'Selections saved successfully', type: 'success');
        $this->emit('selectionsUpdated', ['date' => $this->date]);
    }


    public function selectMeal($mealID)
    {
        dd($mealID);

        // ensure modal closes
        $this->dispatch('close-select-modal');
    }
}
