<?php

namespace App\Domains\Organizacion\Models;

use Illuminate\Database\Eloquent\Model;
use App\Domains\Organizacion\Models\Aplicacion;
class Sociedad extends Model
{
    protected $table = 'sociedades';

    protected $fillable = [

        'nombre',

        'descripcion',

        'codigo',

        'estado',

    ];

    protected function casts(): array
    {
        return [

            'estado' => 'boolean',

        ];
    }

    public function aplicaciones()
    {
        return $this->hasMany(
            Aplicacion::class,
            'sociedad_id'
        );
    }
}
