<?php

use App\Livewire\Admin\Catalog\TipoSolicitudCrud;
use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Livewire\Admin\DashboardAdmin;
/*
|--------------------------------------------------------------------------
| Guest
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login', Login::class)
        ->name('login');

    Route::get('/register', Register::class)
        ->name('register');
});

/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    auth()->logout();

    request()->session()->invalidate();

    request()->session()->regenerateToken();

    return redirect()->route('login');
})->middleware('auth')->name('logout');

/*
|--------------------------------------------------------------------------
| Verificación correo
|--------------------------------------------------------------------------
*/

Route::get('/email/verify', function () {

    return view('auth.verify-email');
})->middleware('auth')
    ->name('verification.notice');



Route::get('/email/verificar/{id}/{hash}', function (
    $id,
    $hash
) {

    $usuario = User::findOrFail($id);

    /*
    |--------------------------------------------------------------------------
    | Validar hash
    |--------------------------------------------------------------------------
    */

    if (! hash_equals(
        sha1($usuario->getEmailForVerification()),
        $hash
    )) {

        abort(403);
    }

    /*
    |--------------------------------------------------------------------------
    | Verificar
    |--------------------------------------------------------------------------
    */

    if (! $usuario->hasVerifiedEmail()) {

        $usuario->markEmailAsVerified();

        event(new Verified($usuario));
    }

    /*
    |--------------------------------------------------------------------------
    | Redirect login
    |--------------------------------------------------------------------------
    */

    return redirect()
        ->route('login')
        ->with(
            'success',
            'Tu correo fue verificado correctamente. Ya puedes iniciar sesión.'
        );
})->middleware([
    'signed',
    'throttle:6,1'
])->name('verification.verify');

/*
|--------------------------------------------------------------------------
| Dashboard autenticado
|--------------------------------------------------------------------------
*/


Route::middleware(['auth'])->group(function () {

    Route::view('/', 'dashboard')
        ->name('dashboard');

    Route::get(
        '/administracion',
        DashboardAdmin::class
    )->name('admin.dashboard');
    
    Route::get(
        '/administracion/tipos-solicitud',
        TipoSolicitudCrud::class
    )->name('admin.tipos-solicitud.index');
});
