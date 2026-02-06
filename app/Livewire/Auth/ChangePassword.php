<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ChangePassword extends Component
{
    public $current_password = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'current_password' => 'required',
        'password' => 'required|min:4|confirmed',
    ];

    public function changePassword()
    {
        $this->validate();
        $checkUser = Hash::check($this->current_password, auth()->user()->password);
        if (!$checkUser) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->password),
        ]);

        session()->flash('status', 'Password changed successfully.');

        $this->reset(['current_password', 'password', 'password_confirmation']);
    }

    public function render()
    {
        return view('livewire.auth.change-password');
    }
}
