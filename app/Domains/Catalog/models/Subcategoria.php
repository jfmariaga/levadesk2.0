<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategoria extends Model
{
    protected $table = 'subcategorias';

    protected $fillable = [

        'categoria_id',

        'nombre',

        'codigo',

        'descripcion',

        'estado',

    ];

    public function categoria()
    {
        return $this->belongsTo(
            Categoria::class,
            'categoria_id'
        );
    }
}