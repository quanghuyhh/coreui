<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public function render()
    {
        return view('livewire.auth.login');
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate()
    {
        $validated = $this->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (! Auth::attempt($validated, $this->remember)) {
            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        return redirect()->route(
            auth()->user()->isManager()
                ? config('role.manager')
                : config('role.teacher')
        );
    }
}
