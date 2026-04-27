<?php

namespace App\Livewire\Admin\MealManagement\Traits;

use App\Models\Meal;
use Illuminate\Support\Facades\Storage;

trait MealDelete
{
    public function delete(int $id)
    {
        $this->authorize('meal.delete');

        $meal = Meal::findOrFail($id);

        // ✅ old image delete
        if ($meal->image && Storage::disk('public')->exists($meal->image)) {
            Storage::disk('public')->delete($meal->image);
        }

        $meal->whereKey($id)->withTrashed()->forceDelete();
        // Meal::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }
}
