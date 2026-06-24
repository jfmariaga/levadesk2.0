<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function categorias(): HasMany
    {
        return $this->hasMany(
            Categoria::class,
            'solicitud_id'
        );
    }
}
