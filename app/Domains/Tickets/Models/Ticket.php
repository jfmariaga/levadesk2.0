<?php

namespace App\Domains\Tickets\Models;

use App\Domains\Catalog\Models\Estado;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $table = 'tickets';

    protected $fillable = [
        'titulo',
        'descripcion',
        'usuario_id',
        'asignado_a',
        'asignado_por_vacaciones',
        'agente_original_id',
        'grupo_id',
        'sociedad_id',
        'tipo_solicitud_id',
        'categoria_id',
        'subcategoria_id',
        'estado_id',
        'ans_id',
        'nomenclatura',
        'urgencia_id',
        'impacto_id',
        'prioridad',
        'notificado',
        'ans_vencido',
        'ans_inicial_vencido',
        'escalar',
        'aplicacion_id',
        'tiempo_inicio_resolucion',
        'tiempo_inicio_aceptacion',
        'notificadoSolucion',
        'notificadoAceptacion',
        'tiempo_restante',
        'finalizar',
        'aviso_enviado_at',
        'tercero_id',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }

    public function asignado(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'asignado_a'
        );
    }

    public function estado(): BelongsTo
    {
        return $this->belongsTo(
            Estado::class,
            'estado_id'
        );
    }
}
