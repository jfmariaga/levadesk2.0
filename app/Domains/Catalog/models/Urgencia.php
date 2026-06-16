<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class Urgencia extends Model
{
    protected $table = 'urgencias';

    protected $fillable = [

        'nombre',

        'puntuacion',

    ];
}