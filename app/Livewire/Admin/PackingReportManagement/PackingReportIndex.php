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
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Packing Report Management')]
class PackingReportIndex extends Component
{
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */

    // ===== For Livewire Pagination =====
    use WithPagination, WithoutUrlPagination;
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

    // ===== Table State =====
    public array $selected = [];
    public bool $selectAll = false;
    public int $perPage = 5;

    // ===== Form State =====
    public bool $isEdit = false;
    public ?int $editRow = null;


    /*
    |--------------------------------------------------------------------------
    | 3. Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->search = Carbon::now()->toDateString();
    }

    // This function will reset the page once after searching.
    public function updatedSearch()
    {
        $this->resetPage();
        $this->resetSelection();
    }

    // this method will
    public function updatedSelectAll($value)
    {
        if ($value) {
            $this->selected = $this->rows
                ->pluck('id')
                ->toArray();
        } else {
            $this->selected = [];
        }
    }

    public function updatedSelected()
    {
        $rowCount = $this->rows->count();

        $this->selectAll = $rowCount > 0 && count($this->selected) === $rowCount;
    }

    public function updatedPage()
    {
        $this->resetSelection();
    }

    /*
    |--------------------------------------------------------------------------
    | 4. Computed Properties
    |--------------------------------------------------------------------------
    */

    #[Computed()]
    public function packingRows(): Collection
    {
        // Optional: filter date from component property $this->filterDate (Y-m-d) or use today
        $date = $this->filterDate ?? now()->toDateString();

        $selections = SubscriberMealSelection::with(['subscriber.user', 'meal'])
            ->whereDate('date', $date)
            ->when($this->search, function ($q) {
                $q->whereHas('subscriber.user', function ($sq) {
                    $sq->where('name', 'like', "%{$this->search}%");
                })->orWhere('date', 'like', "%{$this->search}%");
            })
            ->get();

        // Group by subscriber_id + date in PHP
        $grouped = $selections->groupBy(function ($item) {
            return $item->subscriber_id . '|' . $item->date;
        });

        // Map to objects for Blade friendliness
        $rows = $grouped->map(function ($group) {
            $first = $group->first();
            $user = $first->subscriber->user ?? null;

            return (object) [
                'id' => $first->id,
                'date' => $first->date,
                'subscriber_id' => $first->subscriber_id,
                'subscriber_name' => $user->name ?? ($first->subscriber->name ?? '—'),
                'subscriber_phone' => $user->phone ?? ($first->subscriber->phone ?? '—'),
                'subscriber_address' => collect([$first->subscriber->house, $first->subscriber->road, $first->subscriber->area, $first->subscriber->additional_direction])
                    ->filter()->implode(', '),
                'meal_names' => $group->pluck('meal.name')->filter()->values()->all(), // array of names
                'subscriber_allergens' => $first->subscriber->allergens, // array of names
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

        if(count($rows) === 0) {
            $this->dispatch('toast', message: 'Items is empty!', type: 'warning');
        }

        // Prepare filename
        $filename = 'packing-report-' . $date . '.pdf';

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

    /*
    |--------------------------------------------------------------------------
    | 7. Helper Methods
    |--------------------------------------------------------------------------
    */

    // This method reset bulk selected property
    protected function resetSelection()
    {
        $this->reset(['selected', 'selectAll']);
    }

    protected function refreshTable()
    {
        unset($this->rows, $this->rowsQuery);
    }

    protected function sanitize()
    {
        foreach ([
            'name',
            'slug',
            'description',
            'diet_plan_type',
            'color',
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function reset all fields value
    public function resetFields()
    {
        $this->reset(['search', 'isEdit', 'editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('diet-plan.view');

        return view('livewire.admin.packing-report-management.packing-report-index');
    }
}


