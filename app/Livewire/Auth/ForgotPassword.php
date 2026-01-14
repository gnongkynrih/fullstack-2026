<?php

namespace App\Livewire\Auth;

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

        Password::sendResetLink(['email' => $this->email]);

        session()->flash('status', 'We have emailed your password reset link.');

        $this->email = '';
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
