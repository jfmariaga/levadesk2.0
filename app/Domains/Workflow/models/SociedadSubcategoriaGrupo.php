<?php

namespace App\Domains\Workflow\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;
use App\Domains\Catalog\Models\Subcategoria;
use App\Domains\Organizacion\Models\Grupo;
use App\Domains\Organizacion\Models\Sociedad;

class SociedadSubcategoriaGrupo extends Model
{
    protected $table = 'sociedad_subcategoria_grupo';

    protected $fillable = [

        'sociedad_id',

        'subcategoria_id',

        'grupo_id',

        'supervisor_id',

        'supervisor_secundario_id',

    ];

    public function sociedad()
    {
        return $this->belongsTo(
            Sociedad::class
        );
    }

    public function subcategoria()
    {
        return $this->belongsTo(
            Subcategoria::class
        );
    }

    public function grupo()
    {
        return $this->belongsTo(
            Grupo::class
        );
    }

    public function supervisor()
    {
        return $this->belongsTo(
            User::class,
            'supervisor_id'
        );
    }

    public function supervisorSecundario()
    {
        return $this->belongsTo(
            User::class,
            'supervisor_secundario_id'
        );
    }
}
