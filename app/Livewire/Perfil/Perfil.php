<?php

namespace App\Livewire\Perfil;

use Livewire\Component;
use Livewire\WithFileUploads;

use App\Domains\Organizacion\Models\Area;
use App\Domains\Organizacion\Models\Aplicacion;
use App\Domains\Workflow\Models\BackupFlujo;
use App\Domains\Workflow\Models\SociedadSubcategoriaGrupo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class Perfil extends Component
{
    use WithFileUploads;

    public string $name = '';

    public string $last_name = '';

    public string $email = '';

    public ?int $sociedad_id = null;

    public ?int $area_id = null;

    public string $area = '';

    public $profile_photo = null;

    public string $current_password = '';

    public string $password = '';

    public string $password_confirmation = '';

    public string $activeSection = 'profile';

    public array $backupAsignaciones = [];

    public ?int $backupGlobalId = null;

    public int $en_vacaciones = 0;

    public function mount(): void
    {
        $usuario = Auth::user();

        $this->name = $usuario->name;

        $this->last_name = $usuario->last_name ?? '';

        $this->email = $usuario->email;

        $this->sociedad_id = $usuario->sociedad_id;

        $this->area_id = $usuario->area_id;

        $this->area = $usuario->area ?? '';

        $this->en_vacaciones = $usuario->en_vacaciones ? 1 : 0;

        $this->backupAsignaciones =
            BackupFlujo::query()
            ->where('agente_id', $usuario->id)
            ->get()
            ->mapWithKeys(function (BackupFlujo $backup) {

                if ($backup->flujo_id) {

                    return [
                        'flujo:' . $backup->flujo_id => (string) $backup->backup_id,
                    ];
                }

                if ($backup->aplicacion_id) {

                    return [
                        'app:' . $backup->aplicacion_id => (string) $backup->backup_id,
                    ];
                }

                return [];
            })
            ->toArray();

        $this->activeSection = session(
            'perfil.activeSection',
            'profile'
        );
    }

    public function setActiveSection(string $section): void
    {
        $this->activeSection = $section;

        session()->put(
            'perfil.activeSection',
            $section
        );

        $this->resetValidation();
    }

    public function updatedAreaId(): void
    {
        if (!$this->area_id) {
            return;
        }

        $area = Area::find(
            $this->area_id
        );

        if ($area) {

            $this->area = $area->nombre;

            if (!$this->sociedad_id && $area->sociedad_id) {

                $this->sociedad_id = $area->sociedad_id;
            }
        }
    }

    public function asignarBackupGlobal(): void
    {
        if (!$this->backupGlobalId) {

            $this->dispatch(
                'notify',
                type: 'warning',
                message: 'Selecciona un agente backup antes de continuar.'
            );

            return;
        }

        $usuario = Auth::user();

        $items = $this->itemsAsignables(
            $usuario
        );

        if ($items->isEmpty()) {

            $this->dispatch(
                'notify',
                type: 'warning',
                message: 'No tienes flujos o aplicaciones asignadas para cubrir.'
            );

            return;
        }

        foreach ($items as $item) {

            $this->backupAsignaciones[$item['key']] =
                (string) $this->backupGlobalId;
        }

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Backup global seleccionado correctamente.'
        );
    }

    public function updateProfile(): void
    {
        $usuario = Auth::user();

        $this->validate([

            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'last_name' => [
                'nullable',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $usuario->id
                ),
                function ($attribute, $value, $fail) {

                    $dominios = config(
                        'seguridad.dominios_corporativos'
                    );

                    $dominio = substr(
                        strrchr($value, '@'),
                        1
                    );

                    if (! in_array($dominio, $dominios)) {

                        $fail(
                            'Debes ingresar un correo corporativo válido.'
                        );
                    }
                },
            ],

            'area_id' => [
                'nullable',
                'exists:areas,id',
            ],

            'area' => [
                'nullable',
                'string',
                'max:255',
            ],

            'profile_photo' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png',
                'max:1024',
            ],

        ]);

        if ($this->profile_photo) {

            $usuario->profile_photo =
                $this->profile_photo->store(
                    'profile-photos',
                    'public'
                );
        }

        $usuario->update([

            'name' => trim($this->name),

            'last_name' => trim($this->last_name) !== ''
                ? trim($this->last_name)
                : null,

            'email' => strtolower(
                trim($this->email)
            ),

            'sociedad_id' => $this->sociedad_id ?: null,

            'area_id' => $this->area_id ?: null,

            'area' => trim($this->area) !== ''
                ? trim($this->area)
                : null,

        ]);

        $this->profile_photo = null;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Perfil actualizado correctamente.'
        );
    }

    public function updatePassword(): void
    {
        $this->validate([

            'current_password' => [
                'required',
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

        ]);

        $usuario = Auth::user();

        if (! Hash::check(
            $this->current_password,
            $usuario->password
        )) {

            $this->addError(
                'current_password',
                'La contraseña actual no es correcta.'
            );

            return;
        }

        $usuario->update([
            'password' => Hash::make(
                $this->password
            ),
        ]);

        $this->current_password = '';

        $this->password = '';

        $this->password_confirmation = '';

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Contraseña actualizada correctamente.'
        );
    }

    public function guardarBackups(): void
    {
        if (! $this->syncBackups()) {

            return;
        }

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Backups guardados correctamente.'
        );
    }

    public function marcarVacaciones(): void
    {
        if (! $this->syncBackups()) {

            return;
        }

        $usuario = Auth::user();

        $usuario->update([
            'en_vacaciones' => true,
        ]);

        $this->en_vacaciones = 1;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Vacaciones activadas. Tus backups quedaron configurados.'
        );
    }

    public function volverDelTrabajo(): void
    {
        $usuario = Auth::user();

        $usuario->update([
            'en_vacaciones' => false,
        ]);

        BackupFlujo::query()
            ->where('agente_id', $usuario->id)
            ->delete();

        $this->backupAsignaciones = [];

        $this->backupGlobalId = null;

        $this->en_vacaciones = 0;

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Bienvenido de vuelta. Se desactivó tu estado de vacaciones.'
        );
    }

    private function syncBackups(): bool
    {
        $usuario = Auth::user();

        $items = $this->itemsAsignables(
            $usuario
        );

        if ($items->isEmpty()) {

            BackupFlujo::query()
                ->where('agente_id', $usuario->id)
                ->delete();

            return true;
        }

        $faltantes = $items
            ->filter(
                fn($item) =>
                empty($this->backupAsignaciones[$item['key']])
            );

        if ($faltantes->isNotEmpty()) {

            $this->addError(
                'backupAsignaciones',
                'Debes asignar backup a todos los flujos y aplicaciones antes de marcar vacaciones.'
            );

            return false;
        }

        $backupsValidos = $this->agentesDisponibles(
            $usuario
        )
            ->pluck('id')
            ->map(fn($id) => (int) $id);

        $asignaciones = [];

        foreach ($items as $item) {

            $backupId = (int) ($this->backupAsignaciones[$item['key']] ?? 0);

            if (
                $backupId === (int) $usuario->id
                || ! $backupsValidos->contains($backupId)
            ) {

                $this->addError(
                    'backupAsignaciones',
                    'Uno de los backups seleccionados no es válido o no está disponible.'
                );

                return false;
            }

            $asignaciones[$item['key']] = (string) $backupId;
        }

        BackupFlujo::query()
            ->where('agente_id', $usuario->id)
            ->delete();

        foreach ($items as $item) {

            BackupFlujo::create([
                'agente_id' => $usuario->id,
                'flujo_id' => $item['type'] === 'flujo'
                    ? $item['id']
                    : null,
                'aplicacion_id' => $item['type'] === 'app'
                    ? $item['id']
                    : null,
                'backup_id' => (int) $asignaciones[$item['key']],
            ]);
        }

        $this->backupAsignaciones = $asignaciones;

        $this->resetErrorBag(
            'backupAsignaciones'
        );

        return true;
    }

    private function flujosUsuario(User $usuario)
    {
        $grupoIds = $usuario->grupos()
            ->pluck('grupos.id');

        return SociedadSubcategoriaGrupo::query()

            ->with([
                'sociedad',
                'subcategoria',
                'grupo',
            ])

            ->where(function ($query) use ($grupoIds, $usuario) {

                if ($grupoIds->isNotEmpty()) {

                    $query->whereIn(
                        'grupo_id',
                        $grupoIds
                    )
                        ->orWhere(
                            'supervisor_id',
                            $usuario->id
                        )
                        ->orWhere(
                            'supervisor_id_2',
                            $usuario->id
                        );

                    return;
                }

                $query
                    ->where(
                        'supervisor_id',
                        $usuario->id
                    )
                    ->orWhere(
                        'supervisor_id_2',
                        $usuario->id
                    );
            })

            ->orderBy('subcategoria_id')

            ->get();
    }

    private function aplicacionesUsuario(User $usuario)
    {
        $grupoIds = $usuario->grupos()
            ->pluck('grupos.id');

        return Aplicacion::query()

            ->with([
                'sociedad',
                'grupo',
            ])

            ->whereIn(
                'grupo_id',
                $grupoIds
            )

            ->where(
                'estado',
                0
            )

            ->orderBy('nombre')

            ->get();
    }

    private function itemsAsignables(User $usuario)
    {
        $flujos = $this->flujosUsuario(
            $usuario
        )
            ->map(
                fn($flujo) => [
                    'type' => 'flujo',
                    'id' => $flujo->id,
                    'key' => 'flujo:' . $flujo->id,
                    'nombre' => $flujo->subcategoria?->nombre ?? 'Flujo sin nombre',
                    'contexto' => $flujo->sociedad?->nombre ?? 'Sin sociedad',
                    'grupo' => $flujo->grupo?->nombre ?? 'Sin grupo',
                    'model' => $flujo,
                ]
            );

        $apps = $this->aplicacionesUsuario(
            $usuario
        )
            ->map(
                fn($app) => [
                    'type' => 'app',
                    'id' => $app->id,
                    'key' => 'app:' . $app->id,
                    'nombre' => $app->nombre,
                    'contexto' => $app->sociedad?->nombre ?? 'Sin sociedad',
                    'grupo' => $app->grupo?->nombre ?? 'Sin grupo',
                    'model' => $app,
                ]
            );

        return $flujos
            ->concat($apps)
            ->values();
    }

    private function agentesDisponibles(User $usuario)
    {
        return User::query()

            ->role([
                'Agente',
                'Admin',
            ])

            ->where('estado', true)

            ->where('en_vacaciones', false)

            ->where('id', '!=', $usuario->id)

            ->orderBy('name')

            ->get();
    }

    private function puedeGestionarVacaciones(User $usuario): bool
    {
        $roles = $usuario->roles;

        $esAprobador = $roles
            ->contains(
                fn($rol) =>
                str_contains(
                    strtolower($rol->name),
                    'aprobador'
                )
            );

        if ($esAprobador) {

            return false;
        }

        return ! (
            $roles->count() === 1
            && $roles->first()?->name === 'Usuario'
        );
    }

    public function render()
    {
        $usuario = Auth::user();

        $itemsBackup = $this->itemsAsignables(
            $usuario
        );

        return view(
            'livewire.perfil.perfil',
            [

                'usuario' => $usuario->load([
                    'sociedad',
                    'areaRelacion',
                    'roles',
                    'grupos',
                    'backups',
                ]),

                'areas' => Area::query()

                    ->when(
                        $this->sociedad_id,
                        function ($query) {

                            $query->where(
                                function ($q) {

                                    $q
                                        ->where(
                                            'sociedad_id',
                                            $this->sociedad_id
                                        )
                                        ->orWhereNull(
                                            'sociedad_id'
                                        );
                                }
                            );
                        }
                    )

                    ->where('activo', true)

                    ->orderBy('nombre')

                    ->get(),

                'agentes' => $this->agentesDisponibles(
                    $usuario
                ),

                'itemsBackup' => $itemsBackup,

                'totalItemsBackup' => $itemsBackup->count(),

                'itemsCubiertos' => $itemsBackup
                    ->filter(
                        fn($item) =>
                        ! empty($this->backupAsignaciones[$item['key']])
                    )
                    ->count(),

                'puedeGestionarVacaciones' =>
                $this->puedeGestionarVacaciones($usuario),

            ]
        )->layout('layouts.portal');
    }
}
