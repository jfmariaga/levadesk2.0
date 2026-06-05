<?php

namespace App\Livewire\Auth;

use Livewire\Component;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    /*
    |--------------------------------------------------------------------------
    | Login
    |--------------------------------------------------------------------------
    */

    public function login()
    {
        /*
        |--------------------------------------------------------------------------
        | Rate limiting
        |--------------------------------------------------------------------------
        */

        $key = 'login:'.request()->ip();

        if (RateLimiter::tooManyAttempts(
            $key,
            config('seguridad.max_intentos_login')
        )) {

            $this->addError(
                'email',
                'Demasiados intentos. Intenta más tarde.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validación
        |--------------------------------------------------------------------------
        */

        $credentials = $this->validate([

            'email' => [

                'required',

                'email',

            ],

            'password' => [

                'required',

            ],

        ]);

        /*
        |--------------------------------------------------------------------------
        | Buscar usuario
        |--------------------------------------------------------------------------
        */

        $usuario = User::where(
            'email',
            $this->email
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Usuario inactivo
        |--------------------------------------------------------------------------
        */

        if ($usuario && ! $usuario->estaActivo()) {

            $this->addError(
                'email',
                'Tu cuenta está inactiva.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Intentar autenticación
        |--------------------------------------------------------------------------
        */

        if (! Auth::attempt(
            $credentials,
            $this->remember
        )) {

            RateLimiter::hit($key);

            $this->addError(
                'email',
                'Credenciales incorrectas.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Reset attempts
        |--------------------------------------------------------------------------
        */

        RateLimiter::clear($key);

        /*
        |--------------------------------------------------------------------------
        | Regenerar sesión
        |--------------------------------------------------------------------------
        */

        request()->session()->regenerate();

        /*
        |--------------------------------------------------------------------------
        | Verificación correo
        |--------------------------------------------------------------------------
        */

        if (! Auth::user()->hasVerifiedEmail()) {

            Auth::logout();

            $this->addError(
                'email',
                'Debes verificar tu correo electrónico.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Redirect dashboard
        |--------------------------------------------------------------------------
        */

        return redirect()->route('dashboard');
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('layouts.auth');
    }
}