<?php

namespace App\Livewire\Frontend\SubscriptionPage\Traits;

use App\Models\Meal;
use App\Models\SubscriberAdditionalMealSelection;
use App\Models\SubscriberMealSelection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;

trait ADMealSelection
{
    public function selectADMMeal(int $mealId): void
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
        if (!empty($this->lockedMealTypesAD[$mealTypeId])) {
            $this->dispatch('toast', message: 'This type of match is already locked and cannot be changed.', type: 'warning');
            $this->dispatch('close-select-modal');
            return;
        }

        // Double-check DB (race-safe)
        $existing = SubscriberAdditionalMealSelection::where('subscriber_id', $this->subscriber->id)
            ->whereDate('date', $dateYmd)
            ->where('meal_type_id', $mealTypeId)
            ->first();

        if ($existing) {
            // someone else already selected — lock and inform
            $this->lockedMealTypesAD[$mealTypeId] = true;
            $this->selectedMealsAD[$mealTypeId] = $existing->meal_id;
            $this->dispatch('toast', message: 'There is already a selection for this type and cannot be changed.', type: 'warning');
            $this->dispatch('close-select-modal');
            return;
        }

        // Insert new selection and lock
        try {
            DB::table('subscriber_additional_meal_selections')->insert([
                'subscriber_id' => $this->subscriber->id,
                'date' => $dateYmd,
                'meal_type_id' => $mealTypeId,
                'meal_id' => $mealId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (QueryException $e) {
            // unique constraint or other DB errors
            $this->lockedMealTypesAD[$mealTypeId] = true;
            $this->dispatch('toast', message: 'Could not save selection. It may already exist.', type: 'warning');
            $this->dispatch('close-select-modal');
            return;
        }

        // update local state immediately
        $this->selectedMealsAD[$mealTypeId] = $mealId;
        $this->lockedMealTypesAD[$mealTypeId] = true;

        $this->dispatch('toast', message: 'Meal selected and locked successfully', type: 'success');
        $this->dispatch('close-select-modal');
    }
}
