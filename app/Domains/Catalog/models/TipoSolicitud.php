<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class TipoSolicitud extends Model
{
    protected $table = 'tipo_solicitudes';

    protected $fillable = [

        'nombre',

        'codigo',

        'estado',

    ];

    protected function casts(): array
    {
        return [

            'estado' => 'boolean',

        ];
    }
}