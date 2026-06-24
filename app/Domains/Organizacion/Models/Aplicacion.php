<?php

namespace App\Domains\Organizacion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Aplicacion extends Model
{
    protected $table = 'aplicaciones';

    protected $fillable = [

        'sociedad_id',

        'grupo_id',

        'nombre',

        'estado',

    ];

    public function sociedad(): BelongsTo
    {
        return $this->belongsTo(
            Sociedad::class,
            'sociedad_id'
        );
    }

    public function grupo(): BelongsTo
    {
        return $this->belongsTo(
            Grupo::class,
            'grupo_id'
        );
    }

    public function flujosTerceros()
    {
        return $this->hasMany(
            FlujoTercero::class,
            'aplicacion_id'
        );
    }

    protected function casts(): array
    {
        return [

            'estado' => 'boolean',

        ];
    }
    
}
