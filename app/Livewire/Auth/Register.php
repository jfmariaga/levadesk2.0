<?php

namespace App\Livewire\Auth;

use Livewire\Component;

use App\Models\User;

use Illuminate\Support\Facades\Hash;

use Illuminate\Validation\Rules\Password;

use App\Domains\Organizacion\Models\Area;
use App\Domains\Organizacion\Models\Sociedad;

class Register extends Component
{
    /*
    |--------------------------------------------------------------------------
    | Campos
    |--------------------------------------------------------------------------
    */

    public string $nombres = '';

    public string $apellidos = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public ?int $sociedad_id = null;

    public ?int $area_id = null;

    /*
    |--------------------------------------------------------------------------
    | Mensajes personalizados
    |--------------------------------------------------------------------------
    */

    protected array $messages = [

        'nombres.required' => 'Debes ingresar tus nombres.',

        'nombres.min' => 'Los nombres deben tener mínimo 3 caracteres.',

        'apellidos.required' => 'Debes ingresar tus apellidos.',

        'apellidos.min' => 'Los apellidos deben tener mínimo 3 caracteres.',

        'email.required' => 'Debes ingresar tu correo corporativo.',

        'email.email' => 'Debes ingresar un correo válido.',

        'email.unique' => 'Este correo ya se encuentra registrado.',

        'password.required' => 'Debes ingresar una contraseña.',

        'password.confirmed' => 'Las contraseñas no coinciden.',

        'sociedad_id.required' => 'Debes seleccionar una sociedad.',

        'sociedad_id.exists' => 'La sociedad seleccionada no es válida.',

        'area_id.required' => 'Debes seleccionar un área.',

        'area_id.exists' => 'El área seleccionada no es válida.',

    ];

    /*
    |--------------------------------------------------------------------------
    | Validación realtime
    |--------------------------------------------------------------------------
    */

    public function updated($property)
    {
        $this->validateOnly($property, $this->rules());
    }

    /*
    |--------------------------------------------------------------------------
    | Rules
    |--------------------------------------------------------------------------
    */

    protected function rules(): array
    {
        return [

            'nombres' => [

                'required',

                'min:3',

            ],

            'apellidos' => [

                'required',

                'min:3',

            ],

            'email' => [

                'required',

                'email',

                'unique:users,email',

                function ($attribute, $value, $fail) {

                    $dominios = config(
                        'seguridad.dominios_corporativos'
                    );

                    $dominio = substr(
                        strrchr($value, "@"),
                        1
                    );

                    if (! in_array($dominio, $dominios)) {

                        $fail(
                            'Debes ingresar un correo corporativo válido.'
                        );
                    }
                }

            ],

            'password' => [

                'required',

                'confirmed',

                Password::min(
                    config('seguridad.password_min_length')
                )
                    ->letters()
                    ->mixedCase()
                    ->numbers(),

            ],

            'sociedad_id' => [

                'required',

                'exists:sociedades,id',

            ],

            'area_id' => [

                'required',

                'exists:areas,id',

            ],

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Registro
    |--------------------------------------------------------------------------
    */

    public function register()
    {
        $this->validate();

        /*
        |--------------------------------------------------------------------------
        | Crear usuario
        |--------------------------------------------------------------------------
        */

        $usuario = User::create([

            'name' => $this->nombres,

            'last_name' => $this->apellidos,

            'email' => $this->email,

            'password' => Hash::make($this->password),

            'sociedad_id' => $this->sociedad_id,

            'area_id' => $this->area_id,

            'estado' => true,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Rol default
        |--------------------------------------------------------------------------
        */

        $usuario->assignRole('Usuario');

        /*
        |--------------------------------------------------------------------------
        | Verificación correo
        |--------------------------------------------------------------------------
        */

        $usuario->sendEmailVerificationNotification();

        /*
|--------------------------------------------------------------------------
| Login temporal
|--------------------------------------------------------------------------
*/

        auth()->login($usuario);

        /*
|--------------------------------------------------------------------------
| Redirect verify
|--------------------------------------------------------------------------
*/

        return redirect()
            ->route('verification.notice');
    }

    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    public function render()
    {
        return view('livewire.auth.register', [

            'sociedades' => Sociedad::where(
                'estado',
                0
            )
                ->orderBy('nombre')
                ->get(),

            'areas' => Area::where(
                'activo',
                true
            )
                ->orderBy('nombre')
                ->get(),

        ])->layout('layouts.auth');
    }
}
