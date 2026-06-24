<?php

namespace App\Domains\Organizacion\Models;

use Illuminate\Database\Eloquent\Model;

class Tercero extends Model
{
    protected $table = 'terceros';

    protected $fillable = [

        'nombre',

        'descripcion',

        'activo',

    ];

    public function flujos()
    {
        return $this->hasMany(
            FlujoTercero::class,
            'tercero_id'
        );
    }

    protected function casts(): array
    {
        return [

            'activo' => 'boolean',

        ];
    }
}
