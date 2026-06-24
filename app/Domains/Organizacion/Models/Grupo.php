<?php

namespace App\Domains\Organizacion\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Domains\Organizacion\Models\Aplicacion;
use App\Models\User;

class Grupo extends Model
{
    protected $table = 'grupos';

    protected $fillable = [

        'nombre',

        'descripcion',

    ];

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'grupo_user',
            'grupo_id',
            'user_id'
        );
    }

    public function aplicaciones()
{
    return $this->hasMany(
        Aplicacion::class,
        'grupo_id'
    );
}
}