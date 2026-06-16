<?php

namespace App\Livewire\Admin\Catalog;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\Catalog\Models\Categoria;
use App\Domains\Catalog\Models\Subcategoria;

class SubcategoriaCrud extends Component
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

    public ?int $categoria_id = null;

    public string $sortField = 'nombre';

    public string $sortDirection = 'asc';

    protected function rules(): array
    {
        return [

            'categoria_id' => [
                'required',
                'exists:categorias,id',
            ],

            'nombre' => [
                'required',
                'min:3',
            ],

            'codigo' => [
                'required',
                'max:2',
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
        $subcategoria = Subcategoria::findOrFail($id);

        $this->editingId = $subcategoria->id;

        $this->categoria_id = $subcategoria->categoria_id;

        $this->nombre = $subcategoria->nombre;

        $this->codigo = $subcategoria->codigo;

        $this->descripcion = $subcategoria->descripcion ?? '';

        $this->estado = $subcategoria->estado;

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        Subcategoria::updateOrCreate(

            ['id' => $this->editingId],

            [

                'categoria_id' => $this->categoria_id,

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
            message: 'Subcategoría guardada correctamente.'
        );
    }

    public function toggle(int $id): void
    {
        $subcategoria = Subcategoria::findOrFail($id);

        $subcategoria->estado =
            $subcategoria->estado == 0
                ? 1
                : 0;

        $subcategoria->save();

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

        $this->categoria_id = null;

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
            'livewire.admin.catalog.subcategoria-crud',
            [

                'subcategorias' => Subcategoria::query()

                    ->with([
                        'categoria',
                        'categoria.tipoSolicitud',
                    ])

                    ->when(
                        $this->search,
                        fn ($query) =>
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

                'categorias' => Categoria::query()

                    ->with('tipoSolicitud')

                    ->where('estado', 0)

                    ->orderBy('nombre')

                    ->get(),

            ]
        )->layout('layouts.admin');
    }
}