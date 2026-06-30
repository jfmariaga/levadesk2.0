<?php

namespace App\Livewire\Admin\Organization;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\Tickets\Models\Ticket;
use App\Models\User;
use App\Domains\Organizacion\Models\Area;
use App\Domains\Organizacion\Models\Sociedad;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UsuarioCrud extends Component
{
    use WithPagination;

    private const ESTADOS_TICKET_CERRADO = [
        4,
        5,
    ];

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public string $name = '';

    public string $last_name = '';

    public string $email = '';

    public string $password = '';

    public string $password_confirmation = '';

    public ?int $sociedad_id = null;

    public ?int $area_id = null;

    public string $area = '';

    public string $profile_photo = '';

    public int $estado = 1;

    public int $en_vacaciones = 0;

    public int $aprobador_ti = 0;

    public int $correo_verificado = 1;

    public array $rolesSeleccionados = [];

    public string $sortField = 'name';

    public string $sortDirection = 'asc';

    public ?int $filtroSociedad = null;

    public ?int $filtroArea = null;

    public string $filtroRol = '';

    public string $filtroEstado = '';

    public string $filtroVacaciones = '';

    public string $filtroAprobador = '';

    public bool $showReasignacionModal = false;

    public ?int $usuarioReasignacionId = null;

    public string $accionReasignacion = '';

    public array $ticketsComoUsuario = [];

    public array $ticketsComoAgente = [];

    public array $reasignacionesUsuario = [];

    public array $reasignacionesAgente = [];

    public array $busquedasUsuarioReasignacion = [];

    public array $busquedasAgenteReasignacion = [];

    protected function rules(): array
    {
        $passwordRules = [
            Password::min(
                config('seguridad.password_min_length')
            )
                ->letters()
                ->mixedCase()
                ->numbers(),
            'confirmed',
        ];

        if ($this->editingId) {

            array_unshift(
                $passwordRules,
                'nullable'
            );
        } else {

            array_unshift(
                $passwordRules,
                'required'
            );
        }

        return [

            'name' => [
                'required',
                'min:3',
            ],

            'last_name' => [
                'nullable',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                Rule::unique(
                    'users',
                    'email'
                )->ignore(
                    $this->editingId
                ),
            ],

            'password' => $passwordRules,

            'sociedad_id' => [
                'nullable',
                'exists:sociedades,id',
            ],

            'area_id' => [
                'nullable',
                'exists:areas,id',
            ],

            'area' => [
                'nullable',
                'max:255',
            ],

            'profile_photo' => [
                'nullable',
                'max:255',
            ],

            'estado' => [
                'required',
                'boolean',
            ],

            'en_vacaciones' => [
                'required',
                'boolean',
            ],

            'aprobador_ti' => [
                'required',
                'boolean',
            ],

            'correo_verificado' => [
                'required',
                'boolean',
            ],

            'rolesSeleccionados' => [
                'array',
            ],

            'rolesSeleccionados.*' => [
                'exists:roles,name',
            ],

        ];
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroSociedad(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroArea(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroRol(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroEstado(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroVacaciones(): void
    {
        $this->resetPage();
    }

    public function updatedFiltroAprobador(): void
    {
        $this->resetPage();
    }

    public function updatedSociedadId(): void
    {
        if (!$this->area_id) {
            return;
        }

        $area = Area::find(
            $this->area_id
        );

        if (
            $area
            && $area->sociedad_id
            && $this->sociedad_id
            && (int) $area->sociedad_id !== (int) $this->sociedad_id
        ) {

            $this->area_id = null;
        }
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

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {

            $this->sortDirection =
                $this->sortDirection === 'asc'
                ? 'desc'
                : 'asc';
        } else {

            $this->sortField = $field;

            $this->sortDirection = 'asc';
        }
    }

    public function create(): void
    {
        $this->resetForm();

        if (
            Role::query()
                ->where('name', 'Usuario')
                ->exists()
        ) {

            $this->rolesSeleccionados = [
                'Usuario',
            ];
        }

        $this->showDrawer = true;
    }

    public function edit(int $id): void
    {
        $usuario = User::query()

            ->with([
                'roles',
                'areaRelacion',
            ])

            ->findOrFail($id);

        $this->editingId = $usuario->id;

        $this->name = $usuario->name;

        $this->last_name = $usuario->last_name ?? '';

        $this->email = $usuario->email;

        $this->password = '';

        $this->password_confirmation = '';

        $this->sociedad_id = $usuario->sociedad_id;

        $this->area_id = $usuario->area_id;

        $this->area = $usuario->area ?? '';

        $this->profile_photo = $usuario->profile_photo ?? '';

        $this->estado = $usuario->estado ? 1 : 0;

        $this->en_vacaciones = $usuario->en_vacaciones ? 1 : 0;

        $this->aprobador_ti = $usuario->aprobador_ti ? 1 : 0;

        $this->correo_verificado = $usuario->hasVerifiedEmail()
            ? 1
            : 0;

        $this->rolesSeleccionados =
            $usuario->roles
            ->pluck('name')
            ->toArray();

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        $usuarioActual = $this->editingId
            ? User::find($this->editingId)
            : null;

        if (
            $usuarioActual
            && $usuarioActual->estado
            && (int) $this->estado === 0
            && $this->prepararReasignacionSiTieneTickets(
                $usuarioActual,
                'save'
            )
        ) {

            return;
        }

        $this->persistUsuario();
    }

    private function persistUsuario(
        string $message = 'Usuario guardado correctamente.'
    ): void
    {
        $areaTexto = trim($this->area);

        if ($this->area_id) {

            $areaRelacion = Area::find(
                $this->area_id
            );

            $areaTexto =
                $areaRelacion?->nombre
                ?? $areaTexto;
        }

        $fechaVerificacion = null;

        if ($this->correo_verificado) {

            $fechaVerificacion = $this->editingId
                ? User::find($this->editingId)?->email_verified_at ?? now()
                : now();
        }

        $data = [

            'name' => trim($this->name),

            'last_name' => trim($this->last_name) !== ''
                ? trim($this->last_name)
                : null,

            'email' => strtolower(
                trim($this->email)
            ),

            'estado' => $this->estado,

            'sociedad_id' => $this->sociedad_id ?: null,

            'area_id' => $this->area_id ?: null,

            'area' => $areaTexto !== ''
                ? $areaTexto
                : null,

            'profile_photo' => trim($this->profile_photo) !== ''
                ? trim($this->profile_photo)
                : null,

            'en_vacaciones' => $this->en_vacaciones,

            'aprobador_ti' => $this->aprobador_ti,

            'email_verified_at' => $fechaVerificacion,

        ];

        if (trim($this->password) !== '') {

            $data['password'] = Hash::make(
                $this->password
            );
        }

        $usuario = User::updateOrCreate(

            ['id' => $this->editingId],

            $data

        );

        $usuario->syncRoles(
            $this->rolesSeleccionados
        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: $message
        );
    }

    public function toggle(int $id): void
    {
        $usuario = User::findOrFail($id);

        if (
            $usuario->estado
            && $this->prepararReasignacionSiTieneTickets(
                $usuario,
                'toggle'
            )
        ) {

            return;
        }

        $usuario->estado =
            $usuario->estado
            ? 0
            : 1;

        $usuario->save();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Estado actualizado correctamente.'
        );
    }

    public function confirmarReasignacion(): void
    {
        $usuario = User::findOrFail(
            $this->usuarioReasignacionId
        );

        if (! $this->validarReasignacionesPendientes()) {

            return;
        }

        DB::transaction(function () use ($usuario) {

            foreach ($this->reasignacionesUsuario as $ticketId => $nuevoUsuarioId) {

                Ticket::query()
                    ->whereKey($ticketId)
                    ->where('usuario_id', $usuario->id)
                    ->update([
                        'usuario_id' => (int) $nuevoUsuarioId,
                    ]);
            }

            foreach ($this->reasignacionesAgente as $ticketId => $nuevoAgenteId) {

                Ticket::query()
                    ->whereKey($ticketId)
                    ->where('asignado_a', $usuario->id)
                    ->update([
                        'asignado_a' => (int) $nuevoAgenteId,
                    ]);
            }

            if ($this->accionReasignacion === 'save') {

                $this->estado = 0;

                $this->persistUsuario(
                    'Tickets reasignados y usuario inactivado correctamente.'
                );

                return;
            }

            $usuario->update([
                'estado' => false,
            ]);
        });

        if ($this->accionReasignacion === 'toggle') {

            $this->dispatch(
                'notify',
                type: 'success',
                message: 'Tickets reasignados y usuario inactivado correctamente.'
            );
        }

        $this->cerrarReasignacion();
    }

    public function cerrarReasignacion(): void
    {
        $this->resetReasignacion();

        $this->resetErrorBag([
            'reasignacionesUsuario',
            'reasignacionesAgente',
        ]);
    }

    private function prepararReasignacionSiTieneTickets(
        User $usuario,
        string $accion
    ): bool {
        $this->cargarTicketsPendientes(
            $usuario
        );

        if (
            count($this->ticketsComoUsuario) === 0
            && count($this->ticketsComoAgente) === 0
        ) {

            $this->resetReasignacion();

            return false;
        }

        $this->usuarioReasignacionId = $usuario->id;

        $this->accionReasignacion = $accion;

        $this->showReasignacionModal = true;

        $this->dispatch(
            'notify',
            type: 'warning',
            message: 'Este usuario tiene tickets pendientes. Reasígnalos antes de inactivarlo.'
        );

        return true;
    }

    private function cargarTicketsPendientes(User $usuario): void
    {
        $this->reasignacionesUsuario = [];

        $this->reasignacionesAgente = [];

        $this->busquedasUsuarioReasignacion = [];

        $this->busquedasAgenteReasignacion = [];

        $this->ticketsComoUsuario =
            Ticket::query()
            ->with([
                'estado',
                'asignado',
            ])
            ->where('usuario_id', $usuario->id)
            ->whereNotIn(
                'estado_id',
                self::ESTADOS_TICKET_CERRADO
            )
            ->orderByDesc('created_at')
            ->get()
            ->map(
                fn(Ticket $ticket) =>
                $this->mapTicketPendiente($ticket)
            )
            ->toArray();

        $this->ticketsComoAgente =
            Ticket::query()
            ->with([
                'estado',
                'usuario',
            ])
            ->where('asignado_a', $usuario->id)
            ->whereNotIn(
                'estado_id',
                self::ESTADOS_TICKET_CERRADO
            )
            ->orderByDesc('created_at')
            ->get()
            ->map(
                fn(Ticket $ticket) =>
                $this->mapTicketPendiente($ticket)
            )
            ->toArray();
    }

    private function mapTicketPendiente(Ticket $ticket): array
    {
        return [
            'id' => $ticket->id,
            'nomenclatura' => $ticket->nomenclatura,
            'titulo' => $ticket->titulo,
            'estado' => $ticket->estado?->nombre ?? 'Sin estado',
            'solicitante' => $ticket->usuario?->nombre_completo ?? 'Sin solicitante',
            'agente' => $ticket->asignado?->nombre_completo ?? 'Sin agente',
        ];
    }

    private function validarReasignacionesPendientes(): bool
    {
        $usuariosValidos = $this->usuariosActivosReasignacion()
            ->pluck('id')
            ->map(fn($id) => (int) $id);

        $agentesValidos = $this->agentesActivosReasignacion()
            ->pluck('id')
            ->map(fn($id) => (int) $id);

        foreach ($this->ticketsComoUsuario as $ticket) {

            $nuevoUsuarioId =
                (int) ($this->reasignacionesUsuario[$ticket['id']] ?? 0);

            if (
                ! $nuevoUsuarioId
                || ! $usuariosValidos->contains($nuevoUsuarioId)
            ) {

                $this->addError(
                    'reasignacionesUsuario',
                    'Debes reasignar todos los tickets donde el usuario es solicitante.'
                );

                return false;
            }
        }

        foreach ($this->ticketsComoAgente as $ticket) {

            $nuevoAgenteId =
                (int) ($this->reasignacionesAgente[$ticket['id']] ?? 0);

            if (
                ! $nuevoAgenteId
                || ! $agentesValidos->contains($nuevoAgenteId)
            ) {

                $this->addError(
                    'reasignacionesAgente',
                    'Debes reasignar todos los tickets donde el usuario es agente.'
                );

                return false;
            }
        }

        return true;
    }

    public function closeDrawer(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;

        $this->name = '';

        $this->last_name = '';

        $this->email = '';

        $this->password = '';

        $this->password_confirmation = '';

        $this->sociedad_id = null;

        $this->area_id = null;

        $this->area = '';

        $this->profile_photo = '';

        $this->estado = 1;

        $this->en_vacaciones = 0;

        $this->aprobador_ti = 0;

        $this->correo_verificado = 1;

        $this->rolesSeleccionados = [];

        $this->showDrawer = false;

        $this->resetReasignacion();

        $this->resetValidation();
    }

    private function resetReasignacion(): void
    {
        $this->showReasignacionModal = false;

        $this->usuarioReasignacionId = null;

        $this->accionReasignacion = '';

        $this->ticketsComoUsuario = [];

        $this->ticketsComoAgente = [];

        $this->reasignacionesUsuario = [];

        $this->reasignacionesAgente = [];

        $this->busquedasUsuarioReasignacion = [];

        $this->busquedasAgenteReasignacion = [];
    }

    private function usuariosActivosReasignacion()
    {
        return User::query()
            ->where('estado', true)
            ->when(
                $this->usuarioReasignacionId,
                fn($query) =>
                $query->where(
                    'id',
                    '!=',
                    $this->usuarioReasignacionId
                )
            )
            ->orderBy('name')
            ->get();
    }

    private function agentesActivosReasignacion()
    {
        return User::query()
            ->role([
                'Admin',
                'Agente',
            ])
            ->where('estado', true)
            ->where('en_vacaciones', false)
            ->when(
                $this->usuarioReasignacionId,
                fn($query) =>
                $query->where(
                    'id',
                    '!=',
                    $this->usuarioReasignacionId
                )
            )
            ->orderBy('name')
            ->get();
    }

    private function candidatosFiltradosPorTicket(
        array $tickets,
        $candidatos,
        array $busquedas,
        array $selecciones
    ): array {
        $opciones = [];

        foreach ($tickets as $ticket) {

            $ticketId = (string) $ticket['id'];

            $busqueda = Str::lower(
                trim($busquedas[$ticketId] ?? '')
            );

            $filtrados = $candidatos;

            if ($busqueda !== '') {

                $filtrados = $candidatos
                    ->filter(
                        fn(User $usuario) =>
                        Str::contains(
                            Str::lower(
                                $usuario->nombre_completo
                                . ' '
                                . $usuario->email
                            ),
                            $busqueda
                        )
                    );
            }

            $filtrados = $filtrados
                ->take(25)
                ->values();

            $seleccionadoId = (int) ($selecciones[$ticketId] ?? 0);

            if (
                $seleccionadoId
                && ! $filtrados->contains('id', $seleccionadoId)
            ) {

                $seleccionado = $candidatos
                    ->firstWhere(
                        'id',
                        $seleccionadoId
                    );

                if ($seleccionado) {

                    $filtrados = collect([
                        $seleccionado,
                    ])
                        ->concat($filtrados)
                        ->unique('id')
                        ->values();
                }
            }

            $opciones[$ticketId] = $filtrados;
        }

        return $opciones;
    }

    public function render()
    {
        $usuariosActivosReasignacion =
            $this->usuariosActivosReasignacion();

        $agentesActivosReasignacion =
            $this->agentesActivosReasignacion();

        return view(
            'livewire.admin.organization.usuario-crud',
            [

                'usuarios' => User::query()

                    ->with([
                        'sociedad',
                        'areaRelacion',
                        'roles',
                    ])

                    ->when(
                        $this->search,
                        function ($query) {

                            $query->where(
                                function ($q) {

                                    $q
                                        ->where(
                                            'name',
                                            'like',
                                            "%{$this->search}%"
                                        )
                                        ->orWhere(
                                            'last_name',
                                            'like',
                                            "%{$this->search}%"
                                        )
                                        ->orWhere(
                                            'email',
                                            'like',
                                            "%{$this->search}%"
                                        )
                                        ->orWhere(
                                            'area',
                                            'like',
                                            "%{$this->search}%"
                                        )
                                        ->orWhereHas(
                                            'sociedad',
                                            fn($sub) =>
                                            $sub->where(
                                                'nombre',
                                                'like',
                                                "%{$this->search}%"
                                            )
                                        )
                                        ->orWhereHas(
                                            'areaRelacion',
                                            fn($sub) =>
                                            $sub->where(
                                                'nombre',
                                                'like',
                                                "%{$this->search}%"
                                            )
                                        );
                                }
                            );
                        }
                    )

                    ->when(
                        $this->filtroSociedad,
                        fn($query) =>
                        $query->where(
                            'sociedad_id',
                            $this->filtroSociedad
                        )
                    )

                    ->when(
                        $this->filtroArea,
                        fn($query) =>
                        $query->where(
                            'area_id',
                            $this->filtroArea
                        )
                    )

                    ->when(
                        $this->filtroRol,
                        fn($query) =>
                        $query->whereHas(
                            'roles',
                            fn($sub) =>
                            $sub->where(
                                'name',
                                $this->filtroRol
                            )
                        )
                    )

                    ->when(
                        $this->filtroEstado !== '',
                        fn($query) =>
                        $query->where(
                            'estado',
                            (int) $this->filtroEstado
                        )
                    )

                    ->when(
                        $this->filtroVacaciones !== '',
                        fn($query) =>
                        $query->where(
                            'en_vacaciones',
                            (int) $this->filtroVacaciones
                        )
                    )

                    ->when(
                        $this->filtroAprobador !== '',
                        fn($query) =>
                        $query->where(
                            'aprobador_ti',
                            (int) $this->filtroAprobador
                        )
                    )

                    ->orderBy(
                        $this->sortField,
                        $this->sortDirection
                    )

                    ->paginate($this->perPage),

                'sociedades' => Sociedad::query()

                    ->where('estado', 0)

                    ->orderBy('nombre')

                    ->get(),

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

                    ->orderBy('nombre')

                    ->get(),

                'areasFiltro' => Area::query()

                    ->orderBy('nombre')

                    ->get(),

                'roles' => Role::query()

                    ->orderBy('name')

                    ->get(),

                'usuariosActivosReasignacion' =>
                $usuariosActivosReasignacion,

                'agentesActivosReasignacion' =>
                $agentesActivosReasignacion,

                'usuariosReasignacionPorTicket' =>
                $this->candidatosFiltradosPorTicket(
                    $this->ticketsComoUsuario,
                    $usuariosActivosReasignacion,
                    $this->busquedasUsuarioReasignacion,
                    $this->reasignacionesUsuario
                ),

                'agentesReasignacionPorTicket' =>
                $this->candidatosFiltradosPorTicket(
                    $this->ticketsComoAgente,
                    $agentesActivosReasignacion,
                    $this->busquedasAgenteReasignacion,
                    $this->reasignacionesAgente
                ),

            ]
        )->layout('layouts.admin');
    }
}
