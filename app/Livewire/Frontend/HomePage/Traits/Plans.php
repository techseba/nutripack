<?php
namespace App\Livewire\Frontend\HomePage\Traits;

use App\Models\DietPlan;
use App\Models\Plan;
use App\Models\PlanCategory;
use Livewire\Attributes\Computed;

trait Plans
{
    #[Computed]
    public function dietPlans()
    {
        return DietPlan::get(['name']);
    }

    #[Computed]
    public function planCategories()
    {
        return PlanCategory::get();
    }

    public function showPlan()
    {
        $this->redirectRoute('plan', navigate: true);
    }
}
