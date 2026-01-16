<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Component;

class ResetPassword extends Component
{
    public $token;
    public $email = '';
    public $password = '';
    public $password_confirmation = '';

    protected $rules = [
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:4|confirmed',
    ];

    public function mount($token)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email, 
                'password' => $this->password, 
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token
            ],
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));

                $user->save();

                Auth::login($user);
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect('/')->with('status', 'Your password has been reset.');
        } else {
            $this->addError('email', 'This password reset token is invalid.');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
