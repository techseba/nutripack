<?php

namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use Carbon\Carbon;

trait Summery
{
    protected function patternToWeekdayNumbers(string $pattern): array
    {
        // Carbon weekday numbers: 0 = Sunday, 1 = Monday, ..., 6 = Saturday
        return match ($pattern) {
            'sat-wed' => [6, 0, 1, 2, 3],   // Sat, Sun, Mon, Tue, Wed
            'sun-thu' => [0, 1, 2, 3, 4],   // Sun, Mon, Tue, Wed, Thu
            'sat-thu' => [6, 0, 1, 2, 3, 4],// Sat..Thu (6 days)
            'sat-fri' => [6, 0, 1, 2, 3, 4, 5], // Sat..Fri (7 days)
            default => [], // unknown pattern -> empty
        };
    }

    protected function countMatchingDaysBetweenDates(string $startDate, string $endDate, string $pattern): int
    {
        $weekdays = $this->patternToWeekdayNumbers($pattern);
        if (empty($weekdays)) {
            return 0;
        }

        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        if ($end->lt($start)) {
            return 0;
        }

        $count = 0;
        for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
            if (in_array($date->dayOfWeek, $weekdays, true)) {
                $count++;
            }
        }

        return $count;
    }

    public function getPlanDaysProperty(): int
    {
        if (! $this->selectedPlan || empty($this->starting_date)) {
            return 0;
        }

        // total days from plan definition
        $totalDays = (int) $this->selectedPlan->planCategory->days_of_plan;

        // compute end date (inclusive)
        $start = Carbon::parse($this->starting_date)->startOfDay();
        $end = $start->copy()->addDays($totalDays)->subDay(); // start + totalDays - 1

        // if no subscription_days selected, default to full range length
        if (empty($this->subscription_days)) {
            return $start->diffInDays($end) + 1;
        }

        // otherwise count only matching weekdays
        return $this->countMatchingDaysBetweenDates($start->toDateString(), $end->toDateString(), $this->subscription_days);
    }
}
