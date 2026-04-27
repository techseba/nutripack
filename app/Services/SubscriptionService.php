<?php

namespace App\Services;

use App\Models\Subscriber;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class SubscriptionService
{
    protected function rangeToWeekdays(string $range): array
    {
        $map = ['sun' => 0, 'mon' => 1, 'tue' => 2, 'wed' => 3, 'thu' => 4, 'fri' => 5, 'sat' => 6];
        $parts = explode('-', strtolower($range));
        if (count($parts) !== 2)
            return [];
        $from = substr($parts[0], 0, 3);
        $to = substr($parts[1], 0, 3);
        if (!isset($map[$from]) || !isset($map[$to]))
            return [];
        $start = $map[$from];
        $end = $map[$to];
        $days = [];
        $cursor = $start;
        do {
            $days[] = $cursor;
            $cursor = ($cursor + 1) % 7;
        } while ($cursor !== ($end + 1) % 7);
        return $days;
    }

    public function generateAndStoreDeliveryDays(
        Subscriber $subscriber,
        string $rangeString,
        int $daysOfWeekSelected,
        ?string $startingDate = null,
        ?string $expiresDate = null,
        ?int $maxDeliveries = null
    ): array {
        $start = Carbon::parse($startingDate ?? $subscriber->starting_date)->startOfDay();
        $end = $expiresDate ? Carbon::parse($expiresDate)->endOfDay() : null;
        $selectedWeekdays = $this->rangeToWeekdays($rangeString);

        if (!$end && !$maxDeliveries) {
            $end = $start->copy()->addDays(29)->endOfDay();
        }

        $dates = [];
        $cursor = $start->copy();

        if ($maxDeliveries) {
            while (count($dates) < $maxDeliveries) {
                if (in_array($cursor->dayOfWeek, $selectedWeekdays)) {
                    $dates[] = $cursor->copy();
                }
                $cursor->addDay();
            }
        } else {
            while ($cursor->lte($end)) {
                if (in_array($cursor->dayOfWeek, $selectedWeekdays)) {
                    $dates[] = $cursor->copy();
                }
                $cursor->addDay();
            }
        }

        $excluded = $subscriber->exclusions()->pluck('excluded_date')
            ->map(fn($d) => Carbon::parse($d)->toDateString())->toArray();

        $dates = collect($dates)->filter(fn($dt) => !in_array($dt->toDateString(), $excluded))->values();

        $rows = $dates->map(function ($dt) use ($subscriber) {
            return [
                'subscriber_id' => $subscriber->id,
                'delivery_date' => $dt->toDateString(),
                'day_of_week' => $dt->dayOfWeek,
                'items' => null,
                'status' => 'scheduled',
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->toArray();

        foreach (array_chunk($rows, 200) as $chunk) {
            DB::table('subscriber_delivery_days')->upsert(
                $chunk,
                ['subscriber_id', 'delivery_date'],
                ['day_of_week', 'items', 'status', 'updated_at']
            );
        }

        return $dates->map(fn($d) => $d->toDateString())->toArray();
    }
}
