<?php

namespace App\Livewire\Admin\KitchenReportManagement;

use App\Models\DietPlan;
use App\Models\SubscriberAdditionalMealSelection;
use App\Models\SubscriberMealSelection;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Title('Kitchen Report Management')]
class KitchenReportIndex extends Component
{
    use AuthorizesRequests;
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */

    // ===== For Livewire Pagination =====
    use WithoutUrlPagination, WithPagination;

    /*
    |--------------------------------------------------------------------------
    | 2. Public State Properties
    |--------------------------------------------------------------------------
    */

    // ===== Page Meta =====
    public string $subject = 'kitchen report';

    // ===== Filters =====
    public string $search = '';

    // ===== Table State =====
    public array $selected = [];

    public bool $selectAll = false;

    public int $perPage = 10;

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

    // This function will
    #[Computed]
    public function rowsQuery(): Builder
    {
        return SubscriberMealSelection::query()
            ->with('meal')
            ->select(
                DB::raw('MIN(id) as id'), // group থেকে একটি id নিন
                'meal_id',
                'date',
                DB::raw('COUNT(*) as qty')
            )
            ->groupBy('meal_id', 'date') // meal_id + date অনুযায়ী গ্রুপ করলে প্রতিদিনের রিপোর্ট হবে
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('date', 'like', "%{$this->search}%");
                });
            });
    }

    // This function will
    #[Computed]
    public function rows()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    // This function will
    #[Computed]
    public function rowsQueryAD(): Builder
    {
        return SubscriberAdditionalMealSelection::query()
            ->with('meal')
            ->select(
                DB::raw('MIN(id) as id'), // group থেকে একটি id নিন
                'meal_id',
                'date',
                DB::raw('COUNT(*) as qty')
            )
            ->groupBy('meal_id', 'date') // meal_id + date অনুযায়ী গ্রুপ করলে প্রতিদিনের রিপোর্ট হবে
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('date', 'like', "%{$this->search}%");
                });
            });
    }

    // This function will
    #[Computed]
    public function rowsAD()
    {
        return $this->rowsQueryAD->paginate($this->perPage);
    }

    // Livewire computed property: view এ $combinedRows হিসেবে পাওয়া যাবে
    public function getCombinedRowsProperty()
    {
        $search = $this->search;
        $perPage = $this->perPage;

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
                'meals.name as meal_name',
                'meal_types.name as meal_type_name',
                't.date',
                't.qty'
            )
            ->orderByDesc('t.date');

        return $final->paginate($perPage);
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
        $rows = $this->rows();

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

    // This function open the edit modal
    public function edit(int $id)
    {
        $this->authorize('kitchen-report.edit');
        // Selecting specific table row with specific ID
        $dietPlan = DietPlan::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill($dietPlan->only([
            'name',
            'slug',
            'description',
            'diet_plan_type',
            'color',
            'status',
        ]));

        // Changing value to isEdit property
        $this->isEdit = true;

        // Assigning value to edit Row
        $this->editRow = $id;

        // Opening the form modal
        $this->dispatch('open-modal');
    }

    // This function is storing and updating data in a specific table.
    public function save()
    {
        // Sanitizing form data
        $this->sanitize();

        if ($this->isEdit) {

            $this->authorize('kitchen-report.edit');

            $dietPlan = DietPlan::findOrFail($this->editRow);

            // ✅ Always slugify
            $this->slug = Str::slug($this->slug);

            $data = $this->validate([
                'name' => ['required', 'string', 'max:40'],
                'slug' => [
                    'required',
                    'string',
                    'max:60',
                    Rule::unique('diet_plans', 'slug')->whereNull('deleted_at')->ignore($dietPlan->id),
                ],
                'description' => ['nullable', 'string'],
                'diet_plan_type' => ['required', 'string', 'max:40'],
                'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:256'],
                'color' => ['nullable', 'string', 'max:20'],
                'status' => ['required', 'in:active,inactive'],
            ]);

            // ✅ if new image uploaded
            if ($this->image) {

                if ($dietPlan->image && Storage::disk('public')->exists($dietPlan->image)) {
                    Storage::disk('public')->delete($dietPlan->image);
                }

                $filename = Str::slug($this->name).'-'.time().'.'.$this->image->extension();

                $data['image'] = $this->image->storeAs('diet-plans', $filename, 'public');
            } else {
                unset($data['image']);
            }

            $dietPlan->update($data);

            $this->dispatch('toast', message: ucfirst($this->subject).' updated successfully', type: 'success');

        } else {

            $this->authorize('diet-plan.create');

            // Checking form validation
            $data = $this->validate([
                'name' => ['required', 'string', 'max:40'],
                'description' => ['nullable', 'string'],
                'diet_plan_type' => ['required', 'string', 'max:40'],
                'image' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:256'],
                'color' => ['nullable', 'string', 'max:20'],
            ]);

            $slug = Str::slug($this->name);
            $originalSlug = $slug;
            $count = 1;

            while (DietPlan::where('slug', $slug)->exists()) {
                $slug = $originalSlug.'-'.$count++;
            }
            $data['slug'] = $slug;

            $data['user_id'] = auth()->id();

            // Store image
            if ($this->image) {
                $filename = Str::slug($this->name).'-'.time().'.'.$this->image->extension();
                $data['image'] = $this->image->storeAs('diet-plans', $filename, 'public');
            }

            // Inserting a row into the database
            DietPlan::create($data);

            // Notifying that a row has been successfully inserted into the database
            $this->dispatch('toast', message: ucfirst($this->subject).' created successfully', type: 'success');
        }

        // Resetting form fields
        $this->resetFields();

        // Refreshing the table
        $this->refreshTable();

        // Closing the form modal
        $this->dispatch('close-modal');
    }

    // This function is deleted a single data in a specific table
    public function delete(int $id)
    {
        $this->authorize('kitchen-report.delete');

        $row = SubscriberMealSelection::findOrFail($id);

        $row->whereKey($id)->delete();
        // DietPlan::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject).' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('kitchen-report.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');

            return;
        }

        // $rows = SubscriberMealSelection::whereIn('id', $this->selected)->get();

        // foreach ($rows as $row) {
        //     $row->delete();
        // }

        // DietPlan::whereIn('id', $this->selected)->withTrashed()->forceDelete();
        SubscriberMealSelection::whereIn('id', $this->selected)->delete();

        $this->dispatch('toast', message: count($this->selected).' '.ucfirst($this->subject).' deleted successfully!', type: 'success');

        // Reset selection
        $this->resetSelection();
        $this->dispatch('clear-selection');
        $this->refreshTable();
        $this->dispatch('close-bulk-delete-modal');

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
        $this->authorize('user.view');

        return view('livewire.admin.kitchen-report-management.kitchen-report-index');
    }
}
