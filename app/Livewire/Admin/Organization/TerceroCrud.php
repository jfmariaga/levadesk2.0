<?php

namespace App\Livewire\Admin\Organization;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\Organizacion\Models\Tercero;

class TerceroCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public string $nombre = '';

    public string $descripcion = '';

    public int $activo = 1;

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

    public function create(): void
    {
        $this->resetForm();

        $this->showDrawer = true;
    }

    public function edit(int $id): void
    {
        $tercero = Tercero::findOrFail($id);

        $this->editingId = $tercero->id;

        $this->nombre = $tercero->nombre;

        $this->descripcion = $tercero->descripcion ?? '';

        $this->activo = $tercero->activo;

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        Tercero::updateOrCreate(

            ['id' => $this->editingId],

            [

                'nombre' => strtoupper($this->nombre),

                'descripcion' => $this->descripcion,

                'activo' => $this->activo,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'Tercero guardado correctamente.'
        );
    }

    public function toggle(int $id): void
    {
        $tercero = Tercero::findOrFail($id);

        $tercero->activo =
            $tercero->activo == 1
            ? 0
            : 1;

        $tercero->save();

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

        $this->descripcion = '';

        $this->activo = 1;

        $this->showDrawer = false;

        $this->resetValidation();
    }

    public function render()
    {
        return view(
            'livewire.admin.organization.tercero-crud',
            [

                'terceros' => Tercero::query()

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

            ]
        )->layout('layouts.admin');
    }
}