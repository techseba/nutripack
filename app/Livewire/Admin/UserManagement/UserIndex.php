<?php

namespace App\Livewire\Admin\UserManagement;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithoutUrlPagination;
use Livewire\WithPagination;
use Spatie\Permission\Models\Role;

#[Title('Users Management')]
class UserIndex extends Component
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
    public string $subject = 'user';

    // ===== Filters =====
    public string $search = '';

    // ===== Table State =====
    public array $selected = [];
    public bool $selectAll = false;
    public int $perPage = 5;

    // ===== Form State =====
    public bool $isEdit = false;
    public ?int $editRow = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';
    public string $user_role;

    public $status;

    public $csv;


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
        return User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name','like',"%{$this->search}%")
                    ->orWhere('phone','like',"%{$this->search}%")
                    ->orWhere('email','like',"%{$this->search}%");
                });
        })->latest();
    }

    // This function will
    #[Computed]
    public function rows()
    {
        return $this->rowsQuery->paginate($this->perPage);
    }

     #[Computed]
    public function roles()
    {
        return Role::get();
    }

    /*
    |--------------------------------------------------------------------------
    | 6. Action Methods (CRUD)
    |--------------------------------------------------------------------------
    */

    // This function open the edit modal
    public function edit(int $id)
    {

        // Selecting specific table row with specific ID
        $user = User::findOrFail($id);

        // Assigning field properties values ​​from database values
        $this->fill(['name' => $user->name]);
        $this->fill(['email' => $user->email]);
        $this->fill(['status' => $user->status]);
        $this->user_role = $user->getRoleNames()->first();

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
        $this->name = str($this->name)->trim()->toString();
        $this->email = str($this->email)->trim()->toString();

        if ($this->isEdit) {

            $this->authorize('user.edit');

            // Checking form validation
            $data = $this->validate([
                'name' => [
                    'required',
                    'string',
                    'min:3',
                    'max:40'
                ],

                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users','email')->ignore($this->editRow)
                ],

                'password' => [
                    'nullable',
                    'string',
                    'min:8',
                    'confirmed'
                ],

                'user_role' => [
                    'required',
                    'string',
                    'exists:roles,name'
                ],

                'status' => [
                    'required',
                    Rule::in(['active','inactive'])
                ],
            ]);

            $isSuperAdmin = $this->editRow == config('app.super_admin_id');

            if ($isSuperAdmin && $this->user_role !== 'Developer') {
                $this->dispatch('toast', message: 'Developer role cannot be changed!', type: 'error');
                $this->user_role = 'Developer';
                return;
            }

            if ($isSuperAdmin && $this->status === 'inactive') {
                $this->dispatch('toast', message: 'Developer account cannot be deactivated!', type: 'error');
                $this->status = 'active';
                return;
            }

            // Selecting specific table row with specific ID
            $user = User::findOrFail($this->editRow);

            if (!empty($this->password)) {

                $data['password'] = Hash::make($this->password);

                // Force logout user from all devices
                if ($this->editRow != auth()->id()) {
                    DB::table('sessions')
                        ->where('user_id', $this->editRow)
                        ->delete();
                }

            } else {

                unset($data['password']); // password field update হবে না
            }

            // Updating row into the database
            DB::transaction(function () use ($data, $user) {

                $user->update($data);
                $user->syncRoles($this->user_role);

            });

            activity()->causedBy(auth()->user())->withProperties(['describe'=>'User name - ' . $user->name])->log('User updated');

            // Notifying that a row has been successfully updated into the database
            $this->dispatch('toast', message: ucfirst($this->subject) . ' updated successfully', type: 'success');

        } else {

            $this->authorize('user.create');

            // Checking form validation
            $data = $this->validate([
                'name'      =>  ['required','string','min:3','max:40'],
                'email'     =>  ['required','email','max:255',Rule::unique('users','email')],
                'password'  =>  ['required','min:8','confirmed'],
            ]);

            $data['password'] = Hash::make($this->password);

            // Inserting a row into the database
            $user = User::create($data);

            $user->syncRoles($this->user_role ?? 'User');

            // Notifying that a row has been successfully inserted into the database
            $this->dispatch('toast', message: ucfirst($this->subject) . ' created successfully', type: 'success');
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
            'csv' => ['required','file','mimes:csv,txt','max:2048']
        ]);

        $path = $this->csv->getRealPath();

        $rows = array_map('str_getcsv', file($path));

        // header remove
        $header = array_shift($rows);

        foreach ($rows as $row) {

            $data = array_combine($header, $row);

            // database insert
            if (!User::where('name', $data['name'])->exists()) {
                User::firstOrCreate([
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
        $this->authorize('user.delete');

        if ($id == config('app.super_admin_id')) {
            $this->dispatch('toast', message: 'Developer account cannot be deleted!', type: 'error');

            $this->resetSelection();
            $this->dispatch('clear-selection');
            $this->dispatch('close-delete-modal');
            return;
        }

        // 🚫 Prevent self delete
        if ($id === auth()->id()) {
            $this->dispatch('toast', message: 'You cannot delete your own account!', type: 'error');

            $this->resetSelection();
            $this->dispatch('clear-selection');

            $this->dispatch('close-delete-modal');
            return;
        }

        User::whereKey($id)->withTrashed()->forceDelete();
        // User::whereKey($id)->delete();

        $this->dispatch('toast', message: ucfirst($this->subject) . ' deleted successfully', type: 'success');

        $this->refreshTable();

        // ensure modal closes
        $this->dispatch('close-delete-modal');
    }

    // bulk delete
    public function deleteSelected()
    {
        $this->authorize('user.bulk-delete');

        if (empty($this->selected)) {
            $this->dispatch('toast', message: 'No roles selected!', type: 'warning');
            return;
        }

        if (in_array(config('app.super_admin_id'), $this->selected)) {
            $this->dispatch('toast', message: 'Developer account cannot be deleted!', type: 'error');

            $this->resetSelection();
            $this->dispatch('clear-selection');
            $this->dispatch('close-bulk-delete-modal');
            return;
        }

        // 🚫 Prevent self delete
        if (in_array(auth()->id(), $this->selected)) {
            $this->dispatch('toast', message: 'You cannot delete your own account!', type: 'error');

            $this->resetSelection();
            $this->dispatch('clear-selection');
            $this->dispatch('close-bulk-delete-modal');
            return;
        }
        User::whereIn('id', $this->selected)->withTrashed()->forceDelete();
        // User::whereIn('id', $this->selected)->delete();


        // 🚫 Prevent self delete
        // $idsToDelete = collect($this->selected)
        //     ->reject(fn ($id) => $id == auth()->id())
        //     ->toArray();

        // if (count($idsToDelete) !== count($this->selected)) {
        //     $this->dispatch('toast', message: 'Your account was excluded from deletion.', type: 'warning');
        // }
        // User::whereIn('id', $idsToDelete)->delete();


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
        $this->reset(['search','name','email','password','password_confirmation','user_role','status','csv','isEdit','editRow']);

        // for reset all validation error
        $this->resetValidation();

        $this->resetPage();
    }

    public function render()
    {
        $this->authorize('user.view');

        return view('livewire.admin.user-management.user-index');
    }
}
