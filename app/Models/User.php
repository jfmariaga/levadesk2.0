<?php

namespace App\Models;

use Laravel\Sanctum\HasApiTokens;

use Illuminate\Notifications\Notifiable;

use Illuminate\Contracts\Auth\MustVerifyEmail;

use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Notifications\VerificarCorreoNotification;

use Spatie\Permission\Traits\HasRoles;

use App\Domains\Organizacion\Models\Area;
use App\Domains\Organizacion\Models\Grupo;
use App\Domains\Organizacion\Models\Sociedad;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use HasRoles;

    /*
    |--------------------------------------------------------------------------
    | Tabla
    |--------------------------------------------------------------------------
    */

    protected $table = 'users';

    /*
    |--------------------------------------------------------------------------
    | Mass Assignment
    |--------------------------------------------------------------------------
    */

    protected $fillable = [

        'name',

        'last_name',

        'email',

        'email_verified_at',

        'password',

        'estado',

        'sociedad_id',

        'area_id',

        'area',

        'profile_photo',

        'en_vacaciones',

        'aprobador_ti',

        'teams_webhook_url',

    ];

    /*
    |--------------------------------------------------------------------------
    | Hidden
    |--------------------------------------------------------------------------
    */

    protected $hidden = [

        'password',

        'remember_token',

    ];

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [

            'email_verified_at' => 'datetime',

            'password' => 'hashed',

            'estado' => 'boolean',

            'en_vacaciones' => 'boolean',

            'aprobador_ti' => 'boolean',

        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones Organización
    |--------------------------------------------------------------------------
    */

    public function sociedad(): BelongsTo
    {
        return $this->belongsTo(Sociedad::class);
    }

    public function areaRelacion(): BelongsTo
    {
        return $this->belongsTo(
            Area::class,
            'area_id',
            'id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relaciones Grupos
    |--------------------------------------------------------------------------
    */

    public function grupos(): BelongsToMany
    {
        return $this->belongsToMany(
            Grupo::class,
            'grupo_user',
            'user_id',
            'grupo_id'
        );
    }

    // /*
    // |--------------------------------------------------------------------------
    // | Tickets
    // |--------------------------------------------------------------------------
    // */

    // public function ticketsAsignados(): HasMany
    // {
    //     return $this->hasMany(
    //         Ticket::class,
    //         'asignado_a'
    //     );
    // }

    /*
    |--------------------------------------------------------------------------
    | Backups
    |--------------------------------------------------------------------------
    */

    public function backups(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'agente_backup',
            'agente_id',
            'backup_id'
        )->withTimestamps();
    }

    public function esBackupDe(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'agente_backup',
            'backup_id',
            'agente_id'
        )->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getNombreCompletoAttribute(): string
    {
        return trim(
            $this->name . ' ' . $this->last_name
        );
    }

    public function getAreaNombreAttribute(): string
    {
        return $this->areaRelacion?->nombre
            ?? $this->area
            ?? 'Sin área';
    }

    /*
    |--------------------------------------------------------------------------
    | Teams
    |--------------------------------------------------------------------------
    */

    public function routeNotificationForMicrosoftTeams(): ?string
    {
        return $this->teams_webhook_url;
    }

    /*
    |--------------------------------------------------------------------------
    | Seguridad
    |--------------------------------------------------------------------------
    */

    public function estaActivo(): bool
    {
        return $this->estado === true;
    }

    public function estaEnVacaciones(): bool
    {
        return $this->en_vacaciones === true;
    }

    public function esAprobadorTi(): bool
    {
        return $this->aprobador_ti === true;
    }

    public function getFotoPerfilAttribute(): string
    {
        if ($this->profile_photo) {

            return asset(
                'storage/' . $this->profile_photo
            );
        }

        return 'https://ui-avatars.com/api/?name='
            . urlencode($this->nombre_completo)
            . '&background=1E3A8A'
            . '&color=ffffff'
            . '&size=128';
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(
            new VerificarCorreoNotification
        );
    }
}
