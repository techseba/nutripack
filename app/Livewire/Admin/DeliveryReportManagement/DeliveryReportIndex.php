<?php

namespace App\Livewire\Admin\DeliveryReportManagement;

use App\Models\SubscriberMealSelection;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Delivery Report Management')]
class DeliveryReportIndex extends Component
{
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */
    use AuthorizesRequests;

    /*
    |--------------------------------------------------------------------------
    | 2. Public State Properties
    |--------------------------------------------------------------------------
    */

    // ===== Page Meta =====
    public string $subject = 'delivery report';

    // ===== Filters =====
    public string $search = '';

    /*
    |--------------------------------------------------------------------------
    | 3. Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->search = Carbon::now()->toDateString();
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Computed Properties
    |--------------------------------------------------------------------------
    */

    #[Computed()]
public function deliveryRows(): Collection
{
    // date filter: filterDate ব্যবহার করলে সেটি নিন, নাহলে $this->search বা today
    $date = $this->filterDate ?? $this->search ?? now()->toDateString();

    // Main selections
    $mainSelections = SubscriberMealSelection::with(['subscriber.user', 'meal'])
        ->when($date, fn($q) => $q->whereDate('date', $date))
        ->when($this->search, function ($q) {
            $q->whereHas('subscriber.user', function ($sq) {
                $sq->where('name', 'like', "%{$this->search}%");
            })->orWhereHas('subscriber', function ($sq) {
                $sq->where('phone', 'like', "%{$this->search}%");
            })->orWhere('date', 'like', "%{$this->search}%");
        })
        ->get();

    // Additional selections
    $additionalSelections = \App\Models\SubscriberAdditionalMealSelection::with(['subscriber.user', 'meal'])
        ->when($date, fn($q) => $q->whereDate('date', $date))
        ->when($this->search, function ($q) {
            $q->whereHas('subscriber.user', function ($sq) {
                $sq->where('name', 'like', "%{$this->search}%");
            })->orWhereHas('subscriber', function ($sq) {
                $sq->where('phone', 'like', "%{$this->search}%");
            })->orWhere('date', 'like', "%{$this->search}%");
        })
        ->get();

    // Merge collections
    $all = $mainSelections->concat($additionalSelections);

    // Group by subscriber_id + date
    $grouped = $all->groupBy(function ($item) {
        return $item->subscriber_id . '|' . $item->date;
    });

    // Map groups to rows
    $rows = $grouped->map(function ($group) {
        $first = $group->first();
        $user = $first->subscriber->user ?? null;

        // === Meal counting: countBy() gives correct counts per meal name ===
        $mealCounts = $group->pluck('meal.slug')
            ->filter()            // remove nulls
            ->countBy()           // Collection: ['Chicken' => 2, 'Rice' => 1]
            ->toArray();

        // Convert to string array for easy display / PDF implode
        $mealNamesWithCount = collect($mealCounts)->map(function ($count, $name) {
            return "{$name} ({$count})";
        })->values()->all();

        // Delivery status: যদি কোনো সিলেকশনে delivered_at থাকে সেটাকে delivered ধরে নিন
        $delivered = $group->contains(fn($s) => ! empty($s->delivered_at));
        // Prefer the latest delivered_at if multiple
        $deliveredAt = $group->pluck('delivered_at')->filter()->sort()->last() ?? null;

        // Optionally delivery person (if relation exists on selection)
        $deliveryPerson = null;
        if ($group->first()->relationLoaded('deliveryPerson') || isset($group->first()->delivery_person_id)) {
            // adjust according to your relation/field names
            $deliveryPerson = $group->pluck('deliveryPerson')->filter()->first()?->name ?? null;
        }

        return (object) [
            'id' => $first->id,
            'date' => $first->date,
            'subscriber_id' => $first->subscriber_id,
            'subscriber_name' => $user->name ?? ($first->subscriber->name ?? '—'),
            'subscriber_phone' => $user->phone ?? ($first->subscriber->phone ?? '—'),
            'subscriber_address' => collect([
                $first->subscriber->house,
                $first->subscriber->road,
                $first->subscriber->area,
                $first->subscriber->additional_direction
            ])->filter()->implode(', '),
            'delivery_status' => $delivered ? 'Delivered' : 'Pending',
            'delivered_at' => $deliveredAt ?? ($first->subscriber->delivery_time ?? null),
            'delivery_person' => $deliveryPerson,
            'meal_names' => $mealNamesWithCount, // array of strings: ["Chicken (2)", "Rice (1)"]
        ];
    })->values();

    return $rows;
}


    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    public function exportDeliveryPdf(): StreamedResponse
    {
        // Ensure date filter is set; fallback to today
        $date = $this->search ?? now()->setTimezone(config('app.timezone'))->toDateTimeString();

        // Get rows using your existing computed method
        $rows = $this->deliveryRows();

        if (count($rows) === 0) {
            $this->dispatch('toast', message: 'Items is empty!', type: 'warning');
        }

        // Prepare filename
        $filename = 'delivery-report-'.$date.'.pdf';

        // Load blade view and generate PDF
        $pdf = PDF::loadView('pdf.delivery-report', [
            'rows' => $rows,
            'date' => $date,
            'generated_at' => now(),
        ]);

        // Optional: set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Return download response (works inside Livewire action)
        return response()->streamDownload(function () use ($pdf) {
            echo $pdf->output();
        }, $filename, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="$filename"',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }

    public function render()
    {
        $this->authorize('diet-plan.view');

        return view('livewire.admin.delivery-report-management.delivery-report-index');
    }
}
