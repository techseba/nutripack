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
    // কত করে লোড হবে প্রতি চাংকে
    public $perPage = 8;

    // UI তে দেখানোর জন্য লোড করা activities (array of models)
    public $activities = [];

    // মোট কতটি লোড করা হয়েছে (offset)
    public $loaded = 0;

    // prevent concurrent loadMore calls
    public $loadingMore = false;

    public function mount()
    {
        $this->loadMore(); // প্রথমে প্রথম চাংক লোড
    }

    // চাংক লোড করার মেথড
    public function loadMore()
    {
        if ($this->loadingMore) return;

        $this->loadingMore = true;

        // ensure loaded matches actual items (prevents skip mismatch after prepend)
        $this->loaded = count($this->activities);

        $next = Activity::with('causer','subject')
            ->latest()
            ->skip($this->loaded)
            ->take($this->perPage)
            ->get();

        foreach ($next as $act) {
            $this->activities[] = $act;
        }

        $this->loaded = count($this->activities);

        $this->loadingMore = false;
    }


    // প্রতি poll এ নতুন এন্ট্রি চেক করে টপে prepend করবে
    public function refreshLatest()
    {
        if (empty($this->activities)) {
            $this->loadMore();
            return;
        }

        $firstId = $this->activities[0]->id ?? null;

        $newItems = Activity::with('causer','subject')
            ->latest()
            ->where('id', '>', $firstId)
            ->get();

        if ($newItems->isNotEmpty()) {
            foreach ($newItems->reverse() as $item) {
                // avoid duplicates
                if (!collect($this->activities)->contains('id', $item->id)) {
                    array_unshift($this->activities, $item);
                }
            }
            // sync loaded to actual count
            $this->loaded = count($this->activities);
        }
    }


    public function render()
    {
        $this->authorize('dashboard.view');

        $subscribers = Subscriber::latest()->get();
        $meals = Meal::latest()->get();

        return view('livewire.admin.dashboard-management.overview', [
            'subscriberCount' => count($subscribers),
            'mealCount' => count($meals),
            'activities' => $this->activities,
        ]);
    }
}
