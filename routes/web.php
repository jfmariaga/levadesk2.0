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

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use App\Livewire\Admin\DashboardAdmin;
use App\Livewire\Admin\Organization\SociedadCrud;
use App\Livewire\Admin\Organization\GrupoCrud;
use App\Livewire\Admin\Organization\AplicacionCrud;
use App\Livewire\Admin\Organization\FlujoTerceroCrud;
use App\Livewire\Admin\Organization\TerceroCrud;
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
        '/administracion',
        DashboardAdmin::class
    )->name('admin.dashboard');

    Route::get(
        '/administracion/tipos-solicitud',
        TipoSolicitudCrud::class
    )->name('admin.tipos-solicitud.index');


    Route::get(
        '/administracion/categorias',
        CategoriaCrud::class
    )->name('admin.categorias.index');

    Route::get(
        '/administracion/subcategorias',
        SubcategoriaCrud::class
    )->name('admin.subcategorias.index');

    Route::get(
        '/administracion/estados',
        EstadoCrud::class
    )->name('admin.estados.index');

    Route::get(
        '/administracion/urgencias',
        UrgenciaCrud::class
    )->name('admin.urgencias.index');

    Route::get(
        '/administracion/impactos',
        ImpactoCrud::class
    )->name('admin.impactos.index');

    Route::get(
        '/administracion/sociedades',
        SociedadCrud::class
    )->name('admin.sociedades.index');

    Route::get(
        '/administracion/grupos',
        GrupoCrud::class
    )->name('admin.grupos.index');

    Route::get(
        '/administracion/aplicaciones',
        AplicacionCrud::class
    )->name('admin.aplicaciones.index');

    Route::get(
        '/administracion/asignaciones',
        AsignacionSubcategoria::class
    )->name('admin.asignaciones.index');

    Route::get(
        '/administracion/ans',
        AnsCrud::class
    )->name('admin.ans.index');

    Route::get(
        '/administracion/terceros',
        TerceroCrud::class
    )->name('admin.terceros.index');

    Route::get(
        '/administracion/flujos-terceros',
        FlujoTerceroCrud::class
    )->name('admin.flujos-terceros.index');
});
