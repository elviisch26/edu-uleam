<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'name',
        'email',
        'password',
        'rol_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relación: Un Usuario pertenece a un Rol.
     */
    public function rol(): BelongsTo
    {
        return $this->belongsTo(Rol::class);
    }

    /**
     * Relación: Un Usuario (docente) puede tener muchas Tareas.
     */
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Relación: Un Usuario (estudiante) puede tener muchas Entregas.
     */
    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class);
    }

    /**
     * Materias que imparte el docente.
     */
    public function materiasImpartidas(): HasMany
    {
        return $this->hasMany(Materia::class, 'docente_id');
    }

    /**
     * Materias en las que está inscrito el estudiante.
     */
    public function materiasInscritas(): BelongsToMany
    {
        return $this->belongsToMany(Materia::class, 'materia_usuario')
            ->withTimestamps();
    }

    /**
     * Verifica si el usuario es docente.
     */
    public function esDocente(): bool
    {
        return $this->rol->nombre === 'docente';
    }

    /**
     * Verifica si el usuario es estudiante.
     */
    public function esEstudiante(): bool
    {
        return $this->rol->nombre === 'estudiante';
    }

    /**
     * Verifica si el usuario es admin.
     */
    public function esAdmin(): bool
    {
        return $this->rol->nombre === 'admin';
    }
}