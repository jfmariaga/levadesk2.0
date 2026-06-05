<?php

namespace App\Livewire\Admin\Catalog;

use Livewire\Component;
use Livewire\WithPagination;
use App\Domains\Catalog\Models\TipoSolicitud;

class TipoSolicitudCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public string $nombre = '';

    public string $codigo = '';

    public int $estado = 0;

    public string $sortField = 'nombre';

    public string $sortDirection = 'asc';

    protected function rules(): array
    {
        return [

            'nombre' => [
                'required',
                'min:3',
            ],

            'codigo' => [
                'required',
                'max:5',
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
        $tipo = TipoSolicitud::findOrFail($id);

        $this->editingId = $tipo->id;

        $this->nombre = $tipo->nombre;

        $this->codigo = $tipo->codigo;

        $this->estado = $tipo->estado;

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        TipoSolicitud::updateOrCreate(

            ['id' => $this->editingId],

            [

                'nombre' => strtoupper($this->nombre),

                'codigo' => strtoupper($this->codigo),

                'estado' => $this->estado,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Tipo de solicitud guardado correctamente.'
        );
    }

    public function toggle(int $id): void
    {
        $tipo = TipoSolicitud::findOrFail($id);

        $tipo->estado = $tipo->estado == 0
            ? 1
            : 0;

        $tipo->save();

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

        $this->nombre = '';

        $this->codigo = '';

        $this->estado = 0;

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.admin.catalog.tipo-solicitud-crud',
            [

                'tipos' => TipoSolicitud::query()

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

            ]
        )->layout('layouts.admin');
    }
}
