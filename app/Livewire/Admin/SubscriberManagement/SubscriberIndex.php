<?php

namespace App\Livewire\Admin\SubscriberManagement;

use App\Models\Subscriber;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Permission;

#[Title('Subscriber Management')]
class SubscriberIndex extends Component
{
    /*
    |--------------------------------------------------------------------------
    | 1. Traits
    |--------------------------------------------------------------------------
    */

    // ===== For Livewire Pagination =====
    use WithPagination, WithoutUrlPagination;
    use AuthorizesRequests;
    use WithFileUploads;

    /*
    |--------------------------------------------------------------------------
    | 2. Public State Properties
    |--------------------------------------------------------------------------
    */

    // ===== Page Meta =====
    public string $subject = 'subscriber';

    // ===== Filters =====
    public string $search = '';

    // ===== Table State =====
    public array $selected = [];
    public bool $selectAll = false;
    public int $perPage = 5;

    // ===== Form State =====
    public $updaterEmail;
    public $updaterPhone;
    public $name;
    public $phone;
    public $email;
    public $address;
    public $days_of_week;
    public $plan_price;
    public $subtotal;
    public $promo_code;
    public $discount_amount;
    public $total;
    public $payment_status;
    public $starting_date;
    public $expires_date;
    public $status;

    public bool $isEdit = false;
    public ?int $editRow = null;


    /*
    |--------------------------------------------------------------------------
    | 3. Lifecycle Hooks
    |--------------------------------------------------------------------------
    */

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
        return Subscriber::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('id', 'like', "%{$this->search}%")
                        ->orWhere('phone', 'like', "%{$this->search}%");
                });
            })->latest();
    }

    // This function will
    #[Computed]
    public function rows()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    public function singleView(int $id)
    {
        // Selecting specific table row with specific ID
        $subscriber = Subscriber::findOrFail($id);
        $updaterModel = User::findOrFail($subscriber->updater_id);

        // Assigning field properties values ​​from database values
        $this->updaterEmail = $updaterModel->email;
        $this->updaterPhone = $updaterModel->phone;

        $this->name = $subscriber->user->name;
        $this->phone = $subscriber->phone;
        $this->email = $subscriber->user->email;
        $this->address = $subscriber->house . ', ' . $subscriber->road . ', ' . $subscriber->block . ', ' . $subscriber->area . ', ' . $subscriber->additional_direction;
        $this->days_of_week = $subscriber->plan->days_of_week;
        $this->plan_price = $subscriber->plan->price;
        $this->subtotal = $subscriber->subtotal;
        $this->promo_code = $subscriber->promo_code;
        $this->discount_amount = $subscriber->discount_amount;
        $this->total = $subscriber->total;

        // Opening the form modal
        $this->dispatch('open-view-modal');
    }

    // This function open the edit modal
    public function edit(int $id)
    {
        // Selecting specific table row with specific ID
        $selectedTableRow = Subscriber::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->name = $selectedTableRow->user->name;
        $this->phone = $selectedTableRow->phone;
        $this->payment_status = $selectedTableRow->payment_status;
        $this->starting_date = Carbon::parse($selectedTableRow->starting_date)->format('Y-m-d');
        $this->expires_date = Carbon::parse($selectedTableRow->expires_date)->format('Y-m-d');
        $this->status = $selectedTableRow->status;

        // Changing value to isEdit property
        $this->isEdit = true;

        // Assigning value to edit Row
        $this->editRow = $id;

        // Opening the form modal
        $this->dispatch('open-modal');
    }

    protected function sanitize()
    {
        foreach ([
            'phone',
            'starting_date',
            'expires_date',
        ] as $field) {
            $this->$field = str($this->$field)->squish()->toString();
        }
    }

    // This function is storing and updating data in a specific table.
    public function save()
    {
        // Sanitizing form data
        $this->sanitize();

        if ($this->isEdit) {

            $this->authorize('subscriber.edit');

            $subscriber = Subscriber::findOrFail($this->editRow);

            $data = $this->validate([
                'phone' => ['required', 'string', Rule::unique('subscribers', 'phone')->ignore($subscriber->user_id, 'user_id')],
                'starting_date' => ['required', 'date'],
                'expires_date' => ['required', 'date'],

                'payment_status' => ['required', 'in:unpaid,pending,paid'],
                'status' => ['required', 'in:active,inactive'],
            ]);

            $data['updater_id'] = auth()->id();

            $subscriber->update($data);

            activity()->causedBy(auth()->user())->withProperties(['describe'=>'Subscriber name - ' . $subscriber->user->name])->log('Subscribed updated with ' . $this->payment_status);

            $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');

        }

        // Resetting form fields
        $this->resetFields();

        // Refreshing the table
        $this->refreshTable();

        // Closing the form modal
        $this->dispatch('close-modal');
    }

    // for csv file upload
    public function importCSV()
    {
        $this->validate([
            'csv' => ['required', 'file', 'mimes:csv,txt', 'max:2048']
        ]);

        $path = $this->csv->getRealPath();

        $rows = array_map('str_getcsv', file($path));

        // header remove
        $header = array_shift($rows);

        foreach ($rows as $row) {

            $data = array_combine($header, $row);

            // database insert
            if (!Subscriber::where('promo_code', $data['promo_code'])->exists()) {
                Subscriber::firstOrCreate([
                    'name' => $data['name'],
                ]);
            }
        }

        $this->dispatch('toast', message: ucfirst($this->subject) . ' CSV imported successfully', type: 'success');

        // Resetting form fields
        $this->resetFields();

        // Refreshing the table
        $this->refreshTable();

        // Closing the import modal
        $this->dispatch('close-import-modal');
    }

    // This function is deleted a single data in a specific table
    public function delete(int $id)
    {
        $this->authorize('subscriber.delete');

        Subscriber::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('subscriber.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        Subscriber::whereIn('id', $this->selected)->delete();

        $this->dispatch('toast', message: count($this->selected) . ' ' . ucfirst($this->subject) . ' deleted successfully!', type: 'success');

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

    // This function reset all fields value
    public function resetFields()
    {
        $this->reset([
            'search',
            'updaterEmail',
            'updaterPhone',
            'name',
            'phone',
            'email',
            'address',
            'days_of_week',
            'plan_price',
            'subtotal',
            'promo_code',
            'discount_amount',
            'total',
            'payment_status',
            'starting_date',
            'expires_date',
            'status',

            'isEdit',
            'editRow'
        ]);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function getMissingSubscriber()
    {
        $date = now()->addDay()->toDateString(); // notify for tomorrow
        dd($date);
        $subs = Subscriber::active()->where('expires_date','>=',$date)
            ->whereDoesntHave('selections', fn($q)=> $q->whereDate('date',$date))
            ->with('user')->get();


    }

    public function render()
    {
        $this->authorize('subscriber.view');

        return view('livewire.admin.subscriber-management.subscriber-index');
    }
}

