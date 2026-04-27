<?php

namespace App\Livewire\Admin\DashboardManagement;

use App\Models\Meal;
use App\Models\Subscriber;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Spatie\Activitylog\Models\Activity;

#[Title('App Overview')]
#[Layout('layouts::dashboard')]
class Overview extends Component
{
    public $subscriberCount = '';
    public $mealCount = '';

    public $activityLogs = [];

    public function mount()
    {
        $subscribers = Subscriber::latest()->get();
        $meals = Meal::latest()->get();

        $this->activityLogs = Activity::latest()->get();

        $this->subscriberCount = count($subscribers);
        $this->mealCount = count($meals);
    }
    public function render()
    {
        $this->authorize('dashboard.view');
        return view('livewire.admin.dashboard-management.overview');
    }
}
