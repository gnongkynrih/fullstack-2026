<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;

    protected $rules = [
        'email' => 'required|email',
        'password' => 'required',
    ];

    protected $messages = [
        'email.required' => 'Please enter your email address.',
        'email.email' => 'Please enter a valid email address.',
        'password.required' => 'Please enter your password.',
    ];

    public function login()
    {
        $this->validate(); //validates the input
        $authenticate = Auth::attempt(
            [
            'email' => $this->email, 
            'password' => $this->password
            ], 
            $this->remember);
        if ($authenticate) {
            \Log::info('User logged in', ['user_id' => Auth::user()->id]);
            session()->regenerate(); //generates a new session id
            if (Auth::user()->hasRole('waiter')) {
                return redirect()->intended('/staff-home'); //redirects to the intended page
            }
            return redirect()->intended('/'); //redirects to the intended page
        }

        $this->addError('email', 'These credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login')
        ->layout('components.layouts.guests');
    }
}
