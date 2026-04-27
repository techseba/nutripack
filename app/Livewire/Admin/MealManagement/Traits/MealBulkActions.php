<?php

namespace App\Livewire\Admin\MealManagement\Traits;

use App\Models\Meal;
use Illuminate\Support\Facades\Storage;

trait MealBulkActions
{
    public function deleteSelected()
    {
        $this->authorize('meal.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        $meals = Meal::withTrashed()->whereIn('id', $this->selected)->get();

        foreach($meals as $meal) {
            if ($meal->image && Storage::disk('public')->exists($meal->image)) {
                Storage::disk('public')->delete($meal->image);
            }
            $meal->forceDelete();
        }

        // Meal::whereIn('id', $this->selected)->withTrashed()->forceDelete();
        // Meal::whereIn('id', $this->selected)->delete();

        $this->dispatch('toast', message: count($this->selected) . ' ' . ucfirst($this->subject) . ' deleted successfully!', type: 'success');

        // Reset selection
        $this->resetSelection();
        $this->dispatch('clear-selection');
        $this->refreshTable();
        $this->dispatch('close-bulk-delete-modal');

    }
}
