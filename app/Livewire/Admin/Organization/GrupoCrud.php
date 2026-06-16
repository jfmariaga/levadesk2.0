<?php

namespace App\Livewire\Admin\Organization;

use Livewire\Component;
use Livewire\WithPagination;

use App\Models\User;
use App\Domains\Organizacion\Models\Grupo;

class GrupoCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public string $nombre = '';

    public string $descripcion = '';

    public array $usuariosSeleccionados = [];

    public string $sortField = 'nombre';

    public string $sortDirection = 'asc';

    protected function rules(): array
    {
        return [

            'nombre' => [
                'required',
                'min:3',
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
        $grupo = Grupo::with('usuarios')->findOrFail($id);

        $this->editingId = $grupo->id;

        $this->nombre = $grupo->nombre;

        $this->descripcion = $grupo->descripcion ?? '';

        $this->usuariosSeleccionados =
            $grupo->usuarios
            ->pluck('id')
            ->toArray();

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        $grupo = Grupo::updateOrCreate(

            ['id' => $this->editingId],

            [

                'nombre' => strtoupper($this->nombre),

                'descripcion' => $this->descripcion,

            ]

        );

        $grupo->usuarios()->sync(
            $this->usuariosSeleccionados
        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Grupo guardado correctamente.'
        );
    }

    public function closeDrawer(): void
    {
        $this->resetForm();
    }

    private function resetForm(): void
    {
        $this->editingId = null;

        $this->nombre = '';

        $this->descripcion = '';

        $this->usuariosSeleccionados = [];

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.admin.organization.grupo-crud',
            [

                'grupos' => Grupo::query()

                    ->with('usuarios')

                    ->when(
                        $this->search,
                        fn($query) =>
                        $query->where(
                            'nombre',
                            'like',
                            "%{$this->search}%"
                        )
                    )

                    ->orderBy(
                        $this->sortField,
                        $this->sortDirection
                    )

                    ->paginate($this->perPage),

                'usuarios' => User::query()

                    ->role([
                        'Admin',
                        'Agente'
                    ])

                    ->where('estado', 1)

                    ->orderBy('name')

                    ->get(),

            ]
        )->layout('layouts.admin');
    }
}
