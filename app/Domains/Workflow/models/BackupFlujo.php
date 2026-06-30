<?php

namespace App\Domains\Workflow\Models;

use App\Domains\Organizacion\Models\Aplicacion;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class BackupFlujo extends Model
{
    protected $table = 'backup_flujos';

    protected $fillable = [
        'agente_id',
        'flujo_id',
        'aplicacion_id',
        'backup_id',
    ];

    public function agente()
    {
        return $this->belongsTo(
            User::class,
            'agente_id'
        );
    }

    public function backup()
    {
        return $this->belongsTo(
            User::class,
            'backup_id'
        );
    }

    public function flujo()
    {
        return $this->belongsTo(
            SociedadSubcategoriaGrupo::class,
            'flujo_id'
        );
    }

    public function aplicacion()
    {
        return $this->belongsTo(
            Aplicacion::class,
            'aplicacion_id'
        );
    }
}
