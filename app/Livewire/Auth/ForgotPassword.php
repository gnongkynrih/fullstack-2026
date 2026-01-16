<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Password;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $email = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $user = User::where('email', $this->email)->first();
        if (!$user) {
            session()->flash('status', 'If this email address is registered, you will receive a password reset link.');
            return;
        }
        //will send the reset link to the user
        Password::sendResetLink(['email' => $this->email]);

        session()->flash('status', 'If this email address is registered, you will receive a password reset link.');

        $this->email = '';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
