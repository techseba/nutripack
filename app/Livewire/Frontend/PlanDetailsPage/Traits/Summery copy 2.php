<?php

namespace App\Livewire\Frontend\PlanDetailsPage\Traits;

use Carbon\Carbon;

trait Summery
{
    public $totalAdditionalMealPrice = 0;

    /**
     * Map subscription pattern string to Carbon weekday numbers.
     * Carbon weekday numbers: 0 = Sunday, 1 = Monday, ..., 6 = Saturday
     */
    protected function patternToWeekdayNumbers(string $pattern): array
    {
        return match (strtolower($pattern)) {
            'sat-wed' => [6, 0, 1, 2, 3],   // Sat, Sun, Mon, Tue, Wed
            'sun-thu' => [0, 1, 2, 3, 4],   // Sun, Mon, Tue, Wed, Thu
            'sat-thu' => [6, 0, 1, 2, 3, 4],// Sat..Thu (6 days)
            'sat-fri' => [6, 0, 1, 2, 3, 4, 5], // Sat..Fri (7 days)
            default => [], // unknown pattern -> empty
        };
    }

    /**
     * Return available subscription patterns for a given "days of week" selection.
     * Example: 5 => ['sat-wed','sun-thu'], 6 => ['sat-thu'], 7 => ['sat-fri']
     */
    protected function subscriptionPatternsForDays(int $days): array
    {
        return match ($days) {
            5 => ['sat-wed', 'sun-thu'],
            6 => ['sat-thu'],
            7 => ['sat-fri'],
            default => [],
        };
    }

    /**
     * Count matching weekdays between two dates inclusive according to a pattern.
     *
     * @param  string|\Carbon\Carbon  $startDate
     * @param  string|\Carbon\Carbon  $endDate
     */
    protected function countMatchingDaysBetweenDates($startDate, $endDate, string $pattern): int
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

    /**
     * Livewire computed property: planDays
     *
     * - If no selectedPlan or starting_date -> 0
     * - If subscription_days is empty -> full inclusive range length (start..end)
     * - If subscription_days provided -> count only matching weekdays
     *
     * Relies on these component properties being present:
     * - $this->selectedPlan
     * - $this->starting_date
     * - $this->subscription_days
     */
    public function getPlanDaysProperty(): int
    {
        // echo  $this->subscription_days;
        if (! isset($this->selectedPlan) || empty($this->starting_date)) {
            return 0;
        }

        // total days from plan definition
        $totalDays = (int) ($this->selectedPlan->planCategory->days_of_plan ?? 0);
        if ($totalDays <= 0) {
            return 0;
        }

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

    /**
     * Livewire hook: when days_of_week_selected changes.
     *
     * - Reset subscription_days (so invalid/old pattern won't linger)
     * - If there is exactly one available pattern for the selected days, optionally set it automatically.
     * - This method ensures dependent computed properties (like planDays) will be recalculated.
     *
     * Note: The host component must use the property name `days_of_week_selected`.
     */
    public function updatedDaysOfWeekSelected($value): void
    {
        // clear previously chosen subscription pattern
        $this->subscription_days = null;

        // ensure numeric
        $days = is_numeric($value) ? (int) $value : 0;
        if ($days <= 0) {
            // force a refresh to ensure planDays recomputes to 0
            $this->emitSelf('refreshComponent');

            return;
        }

        // determine available patterns for this days count
        $patterns = $this->subscriptionPatternsForDays($days);

        // if exactly one pattern is available, auto-select it
        if (count($patterns) === 1) {
            $this->subscription_days = $patterns[0];
        }

        // ensure Livewire re-renders computed properties immediately
        $this->emitSelf('refreshComponent');
    }

    /**
     * Optional helper: return subscription patterns for current component state.
     * Useful for Blade to render options dynamically.
     */
    protected function getAvailableSubscriptionPatterns(): array
    {
        $days = isset($this->days_of_week_selected) && is_numeric($this->days_of_week_selected)
            ? (int) $this->days_of_week_selected
            : 0;

        return $this->subscriptionPatternsForDays($days);
    }
}
