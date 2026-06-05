<?php

namespace App\Domains\Organizacion\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Area extends Model
{
    protected $connection = 'mysql';

    protected $table = 'areas';

    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'sociedad_id',
        'nombre',
        'codigo',
        'activo',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    public function sociedad(): BelongsTo
    {
        return $this->belongsTo(Sociedad::class);
    }

    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
