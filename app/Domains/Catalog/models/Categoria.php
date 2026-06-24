<?php

namespace App\Domains\Catalog\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [

        'nombre',

        'codigo',

        'descripcion',

        'estado',

        'solicitud_id',

    ];

    public function tipoSolicitud()
    {
        return $this->belongsTo(
            TipoSolicitud::class,
            'solicitud_id'
        );
    }

    public function subcategorias(): HasMany
    {
        return $this->hasMany(
            Subcategoria::class,
            'categoria_id'
        );
    }
}
