<?php

use App\Livewire\Admin\Catalog\CategoriaCrud;
use App\Livewire\Admin\Catalog\EstadoCrud;
use App\Livewire\Admin\Catalog\ImpactoCrud;
use App\Livewire\Admin\Catalog\SubcategoriaCrud;
use App\Livewire\Admin\Catalog\TipoSolicitudCrud;
use App\Livewire\Admin\Catalog\UrgenciaCrud;
use Illuminate\Support\Facades\Route;

use App\Livewire\Auth\Login;
use App\Livewire\Auth\Register;
use App\Livewire\Perfil\Perfil;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Livewire\Admin\DashboardAdmin;
use App\Livewire\Admin\Organization\SociedadCrud;
use App\Livewire\Admin\Organization\AreaCrud;
use App\Livewire\Admin\Organization\UsuarioCrud;
use App\Livewire\Admin\Organization\GrupoCrud;
use App\Livewire\Admin\Organization\AplicacionCrud;
use App\Livewire\Admin\Organization\FlujoTerceroCrud;
use App\Livewire\Admin\Organization\TerceroCrud;
use App\Livewire\Admin\Security\RolPermisoCrud;
use App\Livewire\Admin\Workflow\AsignacionSubcategoria;
use App\Livewire\Admin\SLA\AnsCrud;

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
        '/perfil',
        Perfil::class
    )->name('perfil');

    Route::middleware(['role:Admin'])
        ->prefix('administracion')
        ->name('admin.')
        ->group(function () {

            Route::get(
                '/',
                DashboardAdmin::class
            )->name('dashboard');

            Route::get(
                '/tipos-solicitud',
                TipoSolicitudCrud::class
            )->name('tipos-solicitud.index');

            Route::get(
                '/categorias',
                CategoriaCrud::class
            )->name('categorias.index');

            Route::get(
                '/subcategorias',
                SubcategoriaCrud::class
            )->name('subcategorias.index');

            Route::get(
                '/estados',
                EstadoCrud::class
            )->name('estados.index');

            Route::get(
                '/urgencias',
                UrgenciaCrud::class
            )->name('urgencias.index');

            Route::get(
                '/impactos',
                ImpactoCrud::class
            )->name('impactos.index');

            Route::get(
                '/sociedades',
                SociedadCrud::class
            )->name('sociedades.index');

            Route::get(
                '/areas',
                AreaCrud::class
            )->name('areas.index');

            Route::get(
                '/usuarios',
                UsuarioCrud::class
            )->name('usuarios.index');

            Route::get(
                '/roles-permisos',
                RolPermisoCrud::class
            )->name('roles-permisos.index');

            Route::get(
                '/grupos',
                GrupoCrud::class
            )->name('grupos.index');

            Route::get(
                '/aplicaciones',
                AplicacionCrud::class
            )->name('aplicaciones.index');

            Route::get(
                '/asignaciones',
                AsignacionSubcategoria::class
            )->name('asignaciones.index');

            Route::get(
                '/ans',
                AnsCrud::class
            )->name('ans.index');

            Route::get(
                '/terceros',
                TerceroCrud::class
            )->name('terceros.index');

            Route::get(
                '/flujos-terceros',
                FlujoTerceroCrud::class
            )->name('flujos-terceros.index');
        });
});
