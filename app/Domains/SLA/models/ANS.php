<?php

namespace App\Domains\SLA\Models;

use Illuminate\Database\Eloquent\Model;

use App\Domains\Catalog\Models\TipoSolicitud;

class ANS extends Model
{
    protected $table = 'a_n_s';

    protected $fillable = [

        'solicitud_id',

        'nivel',

        'h_atencion',

        't_asignacion_segundos',

        't_resolucion_segundos',

        't_aceptacion_segundos',

    ];

    public function tipoSolicitud()
    {
        return $this->belongsTo(
            TipoSolicitud::class,
            'solicitud_id'
        );
    }
}