<?php

namespace App\Livewire\Admin\SLA;

use Livewire\Component;
use Livewire\WithPagination;

use App\Domains\SLA\Models\ANS;
use App\Domains\Catalog\Models\TipoSolicitud;

class AnsCrud extends Component
{
    use WithPagination;

    protected string $paginationTheme = 'tailwind';

    public int $perPage = 10;

    public bool $showDrawer = false;

    public ?int $editingId = null;

    public string $search = '';

    public ?int $solicitud_id = null;

    public string $nivel = '';

    public string $h_atencion = '5x8';

    public int $t_asignacion = 60;

    public int $t_aceptacion = 60;

    public int $t_resolucion = 480;

    public string $sortField = 'nivel';

    public string $sortDirection = 'asc';

    public array $niveles = [

        'INICIAL',

        'BAJA',

        'MEDIA',

        'ALTA',

        'CRÍTICA',

    ];

    public array $horarios = [

        '5x8',

        '7x24',

    ];

    protected function rules(): array
    {
        return [

            'solicitud_id' => [
                'required',
            ],

            'nivel' => [
                'required',
            ],

        ];
    }

    public function updatedSearch(): void
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
        $ans = ANS::findOrFail($id);

        $this->editingId = $ans->id;

        $this->solicitud_id = $ans->solicitud_id;

        $this->nivel = $ans->nivel;

        $this->h_atencion = $ans->h_atencion;

        $this->t_asignacion =
            intval(
                $ans->t_asignacion_segundos / 60
            );

        $this->t_aceptacion =
            intval(
                $ans->t_aceptacion_segundos / 60
            );

        $this->t_resolucion =
            intval(
                $ans->t_resolucion_segundos / 60
            );

        $this->showDrawer = true;
    }

    public function save(): void
    {
        $this->validate();

        ANS::updateOrCreate(

            ['id' => $this->editingId],

            [

                'solicitud_id' => $this->solicitud_id,

                'nivel' => $this->nivel,

                'h_atencion' => $this->h_atencion,

                't_asignacion_segundos'
                => $this->t_asignacion * 60,

                't_aceptacion_segundos'
                => $this->t_aceptacion * 60,

                't_resolucion_segundos'
                => $this->t_resolucion * 60,

            ]

        );

        $this->resetForm();

        $this->dispatch(
            'notify',
            type: 'success',
            message: 'ANS guardado correctamente.'
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

        $this->nivel = '';

        $this->h_atencion = '5x8';

        $this->t_asignacion = 60;

        $this->t_aceptacion = 60;

        $this->t_resolucion = 480;

        $this->showDrawer = false;
    }

    public function formatearTiempo(int $segundos): string
    {
        $minutos = intval($segundos / 60);

        if ($minutos < 60) {

            return $minutos . ' minutos';
        }

        $horas = intval($minutos / 60);

        if ($horas < 24) {

            return $horas . ' horas';
        }

        $dias = intval($horas / 24);

        return $dias . ' días';
    }

    public function render()
    {
        return view(
            'livewire.admin.s-l-a.ans-crud',
            [

                'ans' => ANS::query()

                    ->with('tipoSolicitud')

                    ->paginate($this->perPage),

                'tiposSolicitud' => TipoSolicitud::query()

                    ->where('estado', 0)

                    ->orderBy('nombre')

                    ->get(),

            ]
        )->layout('layouts.admin');
    }
}
