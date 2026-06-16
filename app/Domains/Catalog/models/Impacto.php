<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class Impacto extends Model
{
    protected $table = 'impactos';

    protected $fillable = [

        'nombre',

        'puntuacion',

    ];
}