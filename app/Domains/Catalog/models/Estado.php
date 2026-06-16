<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    protected $table = 'estados';

    protected $fillable = [

        'nombre',

        'descripcion',

    ];
}