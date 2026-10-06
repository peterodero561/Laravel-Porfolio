<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $key = 'admin-login:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 5)) {
            $this->addError('email', 'Too many attempts. Try again in '.RateLimiter::availableIn($key).'s.');

            return;
        }

        $ok = Auth::attempt(
            ['email' => $this->email, 'password' => $this->password, 'is_admin' => true],
            $this->remember,
        );

        if (! $ok) {
            RateLimiter::hit($key, 60);
            $this->addError('email', 'These credentials do not match our records.');
            $this->password = '';

            return;
        }

        RateLimiter::clear($key);
        session()->regenerate();

        $this->redirectRoute('admin.dashboard', navigate: true);
    }

    public function render()
    {
        return view('livewire.admin.login')
            ->layout('components.layouts.auth', ['title' => 'Sign in']);
    }
}
