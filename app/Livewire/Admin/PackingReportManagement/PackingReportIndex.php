<?php

namespace App\Livewire\Admin\PackingReportManagement;

use App\Models\SubscriberMealSelection;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Packing Report Management')]
class PackingReportIndex extends Component
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
    public string $subject = 'packing report';

    // ===== Filters =====
    public string $search = '';

    public ?string $filterDate = null;

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
    public function packingRows(): Collection
    {
        $date = $this->filterDate ?? $this->search ?? now()->toDateString();

        $mainSelections = SubscriberMealSelection::with(['subscriber.user', 'meal'])
            ->when($date, fn ($q) => $q->whereDate('date', $date))
            ->when($this->search, function ($q) {
                $q->whereHas('subscriber.user', function ($sq) {
                    $sq->where('name', 'like', "%{$this->search}%");
                })->orWhere('date', 'like', "%{$this->search}%");
            })
            ->get();

        $additionalSelections = \App\Models\SubscriberAdditionalMealSelection::with(['subscriber.user', 'meal'])
            ->when($date, fn ($q) => $q->whereDate('date', $date))
            ->when($this->search, function ($q) {
                $q->whereHas('subscriber.user', function ($sq) {
                    $sq->where('name', 'like', "%{$this->search}%");
                })->orWhere('date', 'like', "%{$this->search}%");
            })
            ->get();

        $all = $mainSelections->concat($additionalSelections);

        $grouped = $all->groupBy(function ($item) {
            return $item->subscriber_id.'|'.$item->date;
        });

        $rows = $grouped->map(function ($group) {
            $first = $group->first();
            $user = $first->subscriber->user ?? null;

            // === এখানে পরিবর্তন: countBy() ব্যবহার করে সঠিক গণনা ===
            $mealCounts = $group->pluck('meal.slug')
                ->filter()            // remove nulls
                ->countBy()           // returns Collection like ['Chicken' => 2, 'Rice' => 1]
                ->toArray();

            // স্ট্রিং অ্যারে বানানো যাতে PDF-এ implode কাজ করে
            $mealNamesWithCount = collect($mealCounts)->map(function ($count, $name) {
                return "{$name} ({$count})";
            })->values()->all();

            return (object) [
                'id' => $first->id,
                'date' => $first->date,
                'subscriber_id' => $first->subscriber_id,
                'subscriber_name' => $user->name ?? ($first->subscriber->name ?? '—'),
                'subscriber_phone' => $user->phone ?? ($first->subscriber->phone ?? '—'),
                'subscriber_address' => collect([$first->subscriber->house, $first->subscriber->road, $first->subscriber->area, $first->subscriber->additional_direction])
                    ->filter()->implode(', '),
                'meal_names' => $mealNamesWithCount, // এখন array of strings: ["Chicken (2)", "Rice (1)"]
                'subscriber_allergens' => $first->subscriber->allergens ?? [],
            ];
        })->values();

        return $rows;
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    public function exportPackingPdf(): StreamedResponse
    {
        // Ensure date filter is set; fallback to today
        $date = $this->search ?? now()->setTimezone(config('app.timezone'))->toDateTimeString();

        // Get rows using your existing computed method
        $rows = $this->packingRows();

        if (count($rows) === 0) {
            $this->dispatch('toast', message: 'Items is empty!', type: 'warning');
        }

        // Prepare filename
        $filename = 'packing-report-'.$date.'.pdf';

        // Load blade view and generate PDF
        $pdf = PDF::loadView('pdf.packing-report', [
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
        $this->authorize('user.view');

        return view('livewire.admin.packing-report-management.packing-report-index');
    }
}
