<?php

namespace App\Livewire\Admin\Organization;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\Organizacion\Models\Area;
use App\Domains\Organizacion\Models\Sociedad;

class AreaCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public ?int $sociedad_id = null;

    public string $nombre = '';

    public string $codigo = '';

    public int $activo = 1;

    public string $sortField = 'nombre';

    public string $sortDirection = 'asc';

    public ?int $filtroSociedad = null;

    public string $filtroActivo = '';

    protected function rules(): array
    {
        return [

            'sociedad_id' => [
                'nullable',
                'exists:sociedades,id',
            ],

            'nombre' => [
                'required',
                'min:3',
            ],

            'codigo' => [
                'nullable',
                'max:255',
            ],

            'activo' => [
                'required',
                'boolean',
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

    public function updatedFiltroActivo(): void
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
        $area = Area::findOrFail($id);

        $this->editingId = $area->id;

        $this->sociedad_id = $area->sociedad_id;

        $this->nombre = $area->nombre;

        $this->codigo = $area->codigo ?? '';

        $this->activo = $area->activo ? 1 : 0;

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        Area::updateOrCreate(

            ['id' => $this->editingId],

            [

                'sociedad_id' => $this->sociedad_id ?: null,

                'nombre' => trim($this->nombre),

                'codigo' => trim($this->codigo) !== ''
                    ? trim($this->codigo)
                    : null,

                'activo' => $this->activo,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Área guardada correctamente.'
        );
    }

    public function toggle(int $id): void
    {
        $area = Area::findOrFail($id);

        $area->activo =
            $area->activo
            ? 0
            : 1;

        $area->save();

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

        $this->nombre = '';

        $this->codigo = '';

        $this->activo = 1;

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.admin.organization.area-crud',
            [

                'areas' => Area::query()

                    ->with('sociedad')

                    ->when(
                        $this->search,
                        function ($query) {

                            $query->where(
                                function ($q) {

                                    $q
                                        ->where(
                                            'nombre',
                                            'like',
                                            "%{$this->search}%"
                                        )
                                        ->orWhere(
                                            'codigo',
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
                        $this->filtroActivo !== '',
                        fn($query) =>
                        $query->where(
                            'activo',
                            (int) $this->filtroActivo
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

            ]
        )->layout('layouts.admin');
    }
}
