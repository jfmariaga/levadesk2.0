<?php

namespace App\Livewire\Admin\Catalog;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\Catalog\Models\Estado;

class EstadoCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public string $nombre = '';

    public string $descripcion = '';

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
        $estado = Estado::findOrFail($id);

        $this->editingId = $estado->id;

        $this->nombre = $estado->nombre;

        $this->descripcion = $estado->descripcion ?? '';

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        Estado::updateOrCreate(

            ['id' => $this->editingId],

            [

                'nombre' => strtoupper($this->nombre),

                'descripcion' => $this->descripcion,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Estado guardado correctamente.'
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

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.admin.catalog.estado-crud',
            [

                'estados' => Estado::query()

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
