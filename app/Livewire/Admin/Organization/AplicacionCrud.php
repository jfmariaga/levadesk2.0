<?php

namespace App\Livewire\Admin\Organization;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\Organizacion\Models\Grupo;
use App\Domains\Organizacion\Models\Sociedad;
use App\Domains\Organizacion\Models\Aplicacion;

class AplicacionCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public ?int $sociedad_id = null;

    public ?int $grupo_id = null;

    public string $nombre = '';

    public int $estado = 0;

    public string $sortField = 'nombre';

    public string $sortDirection = 'asc';

    public ?int $filtroSociedad = null;

    public ?int $grupoDetalle = null;

    protected function rules(): array
    {
        return [

            'sociedad_id' => [
                'required',
                'exists:sociedades,id',
            ],

            'grupo_id' => [
                'required',
                'exists:grupos,id',
            ],

            'nombre' => [
                'required',
                'min:3',
            ],

        ];
    }

    public function verGrupo(int $grupoId): void
    {
        $this->grupoDetalle = $grupoId;
    }

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
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

        $this->showDrawer = true;
    }

    public function edit(int $id): void
    {
        $aplicacion = Aplicacion::findOrFail($id);

        $this->editingId = $aplicacion->id;

        $this->sociedad_id = $aplicacion->sociedad_id;

        $this->grupo_id = $aplicacion->grupo_id;

        $this->nombre = $aplicacion->nombre;

        $this->estado = $aplicacion->estado;

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        Aplicacion::updateOrCreate(

            ['id' => $this->editingId],

            [

                'sociedad_id' => $this->sociedad_id,

                'grupo_id' => $this->grupo_id,

                'nombre' => strtoupper($this->nombre),

                'estado' => $this->estado,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Aplicación guardada correctamente.'
        );
    }

    public function toggle(int $id): void
    {
        $aplicacion = Aplicacion::findOrFail($id);

        $aplicacion->estado =
            $aplicacion->estado == 0
            ? 1
            : 0;

        $aplicacion->save();

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

        $this->sociedad_id = null;

        $this->grupo_id = null;

        $this->nombre = '';

        $this->estado = 0;

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function updatedFiltroSociedad(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        return view(
            'livewire.admin.organization.aplicacion-crud',
            [

                'aplicaciones' => Aplicacion::query()

                    ->with([
                        'sociedad',
                        'grupo',
                        'grupo.usuarios',
                    ])

                    ->when(
                        $this->search,
                        fn($query) =>
                        $query->where(
                            'nombre',
                            'like',
                            "%{$this->search}%"
                        )
                    )

                    ->when(
                        $this->filtroSociedad,
                        fn($query) =>
                        $query->where(
                            'sociedad_id',
                            $this->filtroSociedad
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

                'grupos' => Grupo::query()

                    ->orderBy('nombre')

                    ->get(),

            ]
        )->layout('layouts.admin');
    }
}
