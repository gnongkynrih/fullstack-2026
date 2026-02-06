<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Mary\Traits\Toast;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Spatie\Permission\Models\Role;

class Register extends Component
{
    use Toast;

    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedRole = null;
    public $roles = [];
    public $showModal = false;
    public $isEditing = false;
    public $editingUser = null;
    public $confirmDeleteModal = false;
    public $deletingUser = null;
    public $refreshKey = 0;

    protected function rules()
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'selectedRole' => 'required',
        ];

        if ($this->isEditing == false) {
            $rules['email'] .= '|unique:users,email';
            $rules['password'] = 'required|string|min:4|confirmed';
        } else {
            $rules['email'] .= '|unique:users,email,' . $this->editingUser->id;
            if ($this->password) {
                $rules['password'] = 'required|string|min:4|confirmed';
            }
        }

        return $rules;
    }

    public function mount()
    {
        $this->roles = Role::orderBy('name')->get();
    }

    #[Computed]
    public function users()
    {
        $this->refreshKey; // Dependency for cache invalidation
        return User::with(['roles'])->orderBy('name')->get();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->selectedRole = null;
        $this->isEditing = false;
        $this->editingUser = null;
    }

    public function store()
    {
        $this->validate();

        try {
            if ($this->isEditing && $this->editingUser) {
                // Update user
                $this->editingUser->update([
                    'name' => $this->name,
                    'email' => $this->email,
                ]);

                if ($this->password) {
                    $this->editingUser->update(['password' => bcrypt($this->password)]);
                }

                $this->editingUser->syncRoles([$this->selectedRole]);

                $this->toast(
                    type: 'success',
                    title: 'Success',
                    description: 'User updated successfully',
                );
            } else {
                // Create user
                $user = User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                ])->assignRole($this->selectedRole);

                $this->toast(
                    type: 'success',
                    title: 'Success',
                    message: 'User created successfully',
                    position: 'toast-top toast-end'
                );
            }

            $this->closeModal();
            $this->loadUsers();
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                message: $e->getMessage(),
                position: 'toast-top toast-end'
            );
        }
    }

    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $this->editingUser = $user;
        $this->isEditing = true;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedRole = $user->roles->first()?->name;
        $this->showModal = true;
    }

    public function confirmDelete($id)
    {
        $this->deletingUser = User::findOrFail($id);
        $this->confirmDeleteModal = true;
    }

    public function delete()
    {
        try {

            //user with admin role cannot be deleted
            //if user hasrole admin we will not delete
            if($this->deletingUser->hasRole('admin')){
                $this->toast(
                    type: 'warning',
                    title: 'Warning',
                    description: 'User with admin role cannot be deleted',
                );
                $this->confirmDeleteModal = false;
                $this->deletingUser = null;
                return;
            }
            $this->deletingUser->delete();
            $this->toast(
                type: 'success',
                title: 'Success',
                message: 'User deleted successfully',
                position: 'toast-top toast-end'
            );
            $this->loadUsers();
        } catch (\Exception $e) {
            $this->toast(
                type: 'error',
                title: 'Error',
                message: $e->getMessage(),
                position: 'toast-top toast-end'
            );
        }
        $this->confirmDeleteModal = false;
        $this->deletingUser = null;
    }

    public function render()
    {
        
        return view('livewire.auth.register');
    }
}
