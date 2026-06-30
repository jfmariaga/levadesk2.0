<?php

namespace App\Livewire\Admin\Organization;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\User;

use App\Domains\Organizacion\Models\Tercero;
use App\Domains\Organizacion\Models\Aplicacion;
use App\Domains\Organizacion\Models\FlujoTercero;

class FlujoTerceroCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public ?int $aplicacion_id = null;

    public ?int $tercero_id = null;

    public ?int $usuario_id = null;

    public string $destinatarios = '';

    public string $nuevoCorreo = '';

    public array $correos = [];

    public int $activo = 1;

    protected function rules(): array
    {
        return [

            'aplicacion_id' => [
                'required',
            ],

            'tercero_id' => [
                'required',
            ],

            'usuario_id' => [
                'required',
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

    public function create(): void
    {
        $this->resetForm();

        $this->showDrawer = true;
    }

    public function edit(int $id): void
    {
        $flujo = FlujoTercero::findOrFail($id);

        $this->editingId = $flujo->id;

        $this->aplicacion_id = $flujo->aplicacion_id;

        $this->tercero_id = $flujo->tercero_id;

        $this->usuario_id = $flujo->usuario_id;

        $this->correos =
            json_decode(
                $flujo->destinatarios ?? '[]',
                true
            ) ?? [];

        $this->activo = $flujo->activo;

        $this->showDrawer = true;
    }

    public function agregarCorreo(): void
    {
        $correo = trim(
            $this->nuevoCorreo
        );

        if (
            !$correo ||
            !filter_var(
                $correo,
                FILTER_VALIDATE_EMAIL
            )
        ) {
            return;
        }

        if (
            !in_array(
                $correo,
                $this->correos
            )
        ) {

            $this->correos = [

                ...$this->correos,

                $correo,

            ];
        }

        $this->nuevoCorreo = '';

        $this->destinatarios =
            json_encode(
                $this->correos
            );

        // $this->dispatch('$refresh');
    }

    public function eliminarCorreo(
        string $correo
    ): void {

        $correo =
            base64_decode(
                $correo
            );

        $this->correos = array_values(

            array_filter(

                $this->correos,

                fn($item) =>
                $item !== $correo

            )

        );

        $this->destinatarios =
            json_encode(
                $this->correos
            );

        // $this->dispatch('$refresh');
    }

    public function save(): void
    {
        $this->validate();

        if (
            count(
                $this->correos
            ) === 0
        ) {

            $this->addError(
                'correos',
                'Debe ingresar al menos un destinatario.'
            );

            return;
        }

        if (
            count($this->correos) === 0
        ) {

            $this->addError(
                'correos',
                'Debe ingresar al menos un destinatario.'
            );

            return;
        }
        FlujoTercero::updateOrCreate(

            ['id' => $this->editingId],

            [

                'aplicacion_id' => $this->aplicacion_id,

                'tercero_id' => $this->tercero_id,

                'usuario_id' => $this->usuario_id,

                'destinatarios' => json_encode(
                    $this->correos
                ),

                'activo' => $this->activo,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Flujo guardado correctamente.'
        );
    }

    public function toggle(int $id): void
    {
        $flujo = FlujoTercero::findOrFail($id);

        $flujo->activo =
            $flujo->activo == 1
            ? 0
            : 1;

        $flujo->save();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Estado actualizado correctamente.'
        );
    }

    public function closeDrawer(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;

        $this->aplicacion_id = null;

        $this->tercero_id = null;

        $this->usuario_id = null;

        // $this->destinatarios = '';

        $this->nuevoCorreo = '';

        $this->correos = [];

        $this->activo = 1;

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.admin.organization.flujo-tercero-crud',
            [

                'flujos' => FlujoTercero::query()

                    ->with([
                        'aplicacion.sociedad',
                        'tercero',
                        'usuario',
                    ])

                    ->when(
                        $this->search,
                        function ($query) {

                            $query->where(

                                function ($q) {

                                    $q

                                        ->whereHas(
                                            'aplicacion',
                                            fn($sub) =>
                                            $sub->where(
                                                'nombre',
                                                'like',
                                                "%{$this->search}%"
                                            )
                                        )

                                        ->orWhereHas(
                                            'tercero',
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

                    ->paginate(
                        $this->perPage
                    ),

                'aplicaciones' => Aplicacion::query()

                    ->where(
                        'estado',
                        0
                    )

                    ->orderBy(
                        'nombre'
                    )

                    ->get(),

                'terceros' => Tercero::query()

                    ->where(
                        'activo',
                        1
                    )

                    ->orderBy(
                        'nombre'
                    )

                    ->get(),

                'usuarios' => User::query()

                    ->role([
                        'Admin',
                        'Agente',
                    ])

                    ->where(
                        'estado',
                        1
                    )

                    ->orderBy(
                        'name'
                    )

                    ->get(),

            ]
        )->layout(
            'layouts.admin'
        );
    }
}
