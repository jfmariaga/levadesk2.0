<?php

namespace App\Domains\Organizacion\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sociedad extends Model
{
    protected $table = 'sociedades';

    protected $fillable = [
        'nombre',
        'codigo',
        'estado',
    ];

    public function areas(): HasMany
    {
        return $this->hasMany(Area::class);
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}