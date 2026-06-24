<?php

namespace App\Livewire\Admin\Workflow;

use Livewire\Component;

use App\Models\User;

use App\Domains\Catalog\Models\Categoria;
use App\Domains\Catalog\Models\Subcategoria;

use App\Domains\Organizacion\Models\Grupo;
use App\Domains\Organizacion\Models\Sociedad;

use App\Domains\Workflow\Models\SociedadSubcategoriaGrupo;

class AsignacionSubcategoria extends Component
{
    public ?int $sociedadId = null;

    public ?int $subcategoriaId = null;

    public ?int $grupoId = null;

    public ?int $supervisorId = null;

    public ?int $supervisorSecundarioId = null;

    public ?Subcategoria $subcategoriaSeleccionada = null;

    public function seleccionarSubcategoria(
        int $subcategoriaId
    ): void {

        // Limpiar selección anterior
        $this->grupoId = null;

        $this->supervisorId = null;

        $this->supervisorSecundarioId = null;

        $this->subcategoriaId = $subcategoriaId;

        $this->subcategoriaSeleccionada =
            Subcategoria::query()

            ->with([
                'categoria',
                'categoria.tipoSolicitud',
            ])

            ->findOrFail(
                $subcategoriaId
            );

        $configuracion =
            SociedadSubcategoriaGrupo::query()

            ->where(
                'sociedad_id',
                $this->sociedadId
            )

            ->where(
                'subcategoria_id',
                $subcategoriaId
            )

            ->first();

        if ($configuracion) {

            $this->grupoId =
                $configuracion->grupo_id;

            $this->supervisorId =
                $configuracion->supervisor_id;

            $this->supervisorSecundarioId =
                $configuracion->supervisor_id_2;
        }

        $this->resetValidation();
    }

    public function guardar(): void
    {
        if (
            !$this->sociedadId ||
            !$this->subcategoriaId
        ) {
            return;
        }

        SociedadSubcategoriaGrupo::updateOrCreate(

            [

                'sociedad_id' => $this->sociedadId,

                'subcategoria_id' => $this->subcategoriaId,

            ],

            [

                'grupo_id' => $this->grupoId,

                'supervisor_id' => $this->supervisorId,

                'supervisor_id_2'
                => $this->supervisorSecundarioId,

            ]

        );

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Configuración guardada correctamente.'
        );
    }

    public function render()
    {
        return view(
            'livewire.admin.workflow.asignacion-subcategoria',
            [

                'sociedades' => Sociedad::query()

                    ->where('estado', 0)

                    ->orderBy('nombre')

                    ->get(),

                'categorias' => Categoria::query()

                    ->where('estado', 0)

                    ->whereHas(
                        'subcategorias',
                        fn($query) =>
                        $query->where(
                            'estado',
                            0
                        )
                    )

                    ->with([

                        'tipoSolicitud',

                        'subcategorias' => fn($query) =>

                        $query->where(
                            'estado',
                            0
                        )

                            ->orderBy('nombre'),

                    ])

                    ->whereHas(
                        'tipoSolicitud',
                        fn($query) =>
                        $query->where(
                            'estado',
                            0
                        )
                    )

                    ->orderBy('nombre')

                    ->get(),

                'grupos' => Grupo::query()

                    ->with('usuarios')

                    ->orderBy('nombre')

                    ->get(),

                'supervisores' => User::query()

                    ->role([
                        'Admin',
                        'Agente',
                    ])

                    ->where('estado', 1)

                    ->orderBy('name')

                    ->get(),

                'configuradas' =>

                $this->sociedadId

                    ? SociedadSubcategoriaGrupo::query()

                    ->where(
                        'sociedad_id',
                        $this->sociedadId
                    )

                    ->count()

                    : 0,

            ]
        )->layout('layouts.admin');
    }
}
