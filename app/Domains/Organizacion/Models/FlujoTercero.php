<?php

namespace App\Domains\Organizacion\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class FlujoTercero extends Model
{
    protected $table = 'flujos_terceros';

    protected $fillable = [

        'aplicacion_id',

        'tercero_id',

        'usuario_id',

        'destinatarios',

        'activo',

    ];

    protected function casts(): array
    {
        return [

            'activo' => 'boolean',

        ];
    }

    public function aplicacion()
    {
        return $this->belongsTo(
            Aplicacion::class,
            'aplicacion_id'
        );
    }

    public function tercero()
    {
        return $this->belongsTo(
            Tercero::class,
            'tercero_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}
