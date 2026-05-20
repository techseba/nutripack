<?php

namespace App\Livewire\Admin\KitchenReportManagement;

use App\Models\SubscriberAdditionalMealSelection;
use App\Models\SubscriberMealSelection;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Kitchen Report Management')]
class KitchenReportIndex extends Component
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
    public string $subject = 'kitchen report';

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

    // Livewire computed property: view এ $combinedRows
    #[Computed()]
    public function combinedRows()
    {
        $search = $this->search;

        // প্রথম স্তরের গ্রুপিং (প্রতিটি টেবিলেই meal_id + date অনুযায়ী)
        $q1 = SubscriberMealSelection::query()
            ->selectRaw('MIN(id) as id, meal_id, date, COUNT(*) as qty')
            ->when($search, fn ($q) => $q->where('date', 'like', "%{$search}%"))
            ->groupBy('meal_id', 'date');

        $q2 = SubscriberAdditionalMealSelection::query()
            ->selectRaw('MIN(id) as id, meal_id, date, COUNT(*) as qty')
            ->when($search, fn ($q) => $q->where('date', 'like', "%{$search}%"))
            ->groupBy('meal_id', 'date');

        // Union all
        $union = $q1->unionAll($q2);

        // Wrap union as subquery, তারপর আবার meal_id + date অনুযায়ী গ্রুপ করে qty যোগ করা
        $combined = DB::query()
            ->fromSub($union, 'u')
            ->selectRaw('MIN(u.id) as id, u.meal_id, u.date, SUM(u.qty) as qty')
            ->groupBy('u.meal_id', 'u.date');

        // এখন meals ও meal_types জয়েন করে নাম আনছি
        $final = DB::query()
            ->fromSub($combined, 't')
            ->join('meals', 'meals.id', '=', 't.meal_id')
            ->leftJoin('meal_types', 'meal_types.id', '=', 'meals.meal_type_id') // adjust FK if different
            ->select(
                't.id',
                't.meal_id',
                'meals.slug as meal_name',
                'meal_types.name as meal_type_name',
                't.date',
                't.qty'
            )
            ->orderByDesc('t.date');

        return $final->get();
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    public function exportKitchenPdf(): StreamedResponse
    {
        // Ensure date filter is set; fallback to today
        $date = $this->search ?? now()->setTimezone(config('app.timezone'))->toDateTimeString();

        // Get rows using your existing computed method
        $rows = $this->combinedRows();

        if (count($rows) === 0) {
            $this->dispatch('toast', message: 'Items is empty!', type: 'warning');
        }

        // Prepare filename
        $filename = 'kitchen-report-'.$date.'.pdf';

        // Load blade view and generate PDF
        $pdf = PDF::loadView('pdf.kitchen-report', [
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

        return view('livewire.admin.kitchen-report-management.kitchen-report-index');
    }
}
