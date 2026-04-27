<?php

namespace App\Livewire\Admin\GuestMealManagement;

use App\Models\Meal;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;

#[Title('Guest Meals Management')]
class GuestMealIndex extends Component
{
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */

    // ===== For Livewire Pagination =====
    use WithPagination, WithoutUrlPagination;

    // ===== Page Meta =====
    public string $subject = 'guest meals';

    // ===== Filters =====
    public string $search = '';

    public int $perPage = 18;

    // This function will reset the page once after searching.
    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function toggleMeal($id)
    {
        $meal = Meal::findOrFail($id);

        $meal->update([
            'is_guest_meal' => !$meal->is_guest_meal
        ]);
    }

    public function render()
    {
        return view('livewire.admin.guest-meal-management.guest-meal-index', [
            'meals' => Meal::query()
                            ->when($this->search, function ($query) {
                                $query->where(function ($q) {
                                    $q->where('name','like',"%{$this->search}%");
                                });
                        })->latest()->paginate($this->perPage)
        ]);
    }
}
