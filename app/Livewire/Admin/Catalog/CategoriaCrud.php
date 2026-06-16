<?php

namespace App\Livewire\Admin\Catalog;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\Catalog\Models\Categoria;
use App\Domains\Catalog\Models\TipoSolicitud;

class CategoriaCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public string $nombre = '';

    public string $codigo = '';

    public string $descripcion = '';

    public int $estado = 0;

    public ?int $solicitud_id = null;

    public string $sortField = 'nombre';

    public string $sortDirection = 'asc';

    protected function rules(): array
    {
        return [

            'solicitud_id' => [
                'required',
                'exists:tipo_solicitudes,id',
            ],

            'nombre' => [
                'required',
                'min:3',
            ],

            'codigo' => [
                'required',
                'max:1',
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
        $categoria = Categoria::findOrFail($id);

        $this->editingId = $categoria->id;

        $this->solicitud_id = $categoria->solicitud_id;

        $this->nombre = $categoria->nombre;

        $this->codigo = $categoria->codigo;

        $this->descripcion = $categoria->descripcion ?? '';

        $this->estado = $categoria->estado;

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        Categoria::updateOrCreate(

            ['id' => $this->editingId],

            [

                'solicitud_id' => $this->solicitud_id,

                'nombre' => strtoupper($this->nombre),

                'codigo' => strtoupper($this->codigo),

                'descripcion' => $this->descripcion,

                'estado' => $this->estado,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Categoría guardada correctamente.'
        );
    }

    public function toggle(int $id): void
    {
        $categoria = Categoria::findOrFail($id);

        $categoria->estado = $categoria->estado == 0
            ? 1
            : 0;

        $categoria->save();

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

        $this->solicitud_id = null;

        $this->nombre = '';

        $this->codigo = '';

        $this->descripcion = '';

        $this->estado = 0;

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.admin.catalog.categoria-crud',
            [

                'categorias' => Categoria::query()

                    ->with('tipoSolicitud')

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

                'tiposSolicitud' => TipoSolicitud::where(
                    'estado',
                    0
                )
                    ->orderBy('nombre')
                    ->get(),

            ]
        )->layout('layouts.admin');
    }
}
