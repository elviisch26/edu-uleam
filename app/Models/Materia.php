<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Materia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'codigo',
        'descripcion',
        'docente_id',
    ];

    /**
     * El docente que imparte la materia.
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'docente_id');
    }

    /**
     * Estudiantes inscritos en la materia.
     */
    public function estudiantes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'materia_usuario')
            ->withTimestamps();
    }

    /**
     * Tareas de la materia.
     */
    public function tareas(): HasMany
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Cantidad de estudiantes inscritos.
     */
    public function getCantidadEstudiantesAttribute(): int
    {
        return $this->estudiantes()->count();
    }
}
