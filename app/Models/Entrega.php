<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Entrega extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'ruta_archivo',
        'tarea_id',
    ];

    /**
     * Relación: Una Entrega pertenece a una Tarea.
     */
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    /**
     * Relación: Una Entrega pertenece a un Usuario (Estudiante).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una Entrega tiene una Calificación.
     */
    public function calificacion(): HasOne
    {
        return $this->hasOne(Calificacion::class);
    }

    /**
     * Accessor: Verifica si la entrega está calificada.
     */
    public function getEstaCalificadaAttribute(): bool
    {
        return $this->calificacion()->exists();
    }

    /**
     * Accessor: Verifica si la entrega fue a tiempo.
     */
    public function getFueATiempoAttribute(): bool
    {
        return $this->created_at->lte($this->tarea->fecha_entrega);
    }

    /**
     * Scope: Entregas sin calificar.
     */
    public function scopeSinCalificar($query)
    {
        return $query->whereDoesntHave('calificacion');
    }

    /**
     * Scope: Entregas calificadas.
     */
    public function scopeCalificadas($query)
    {
        return $query->whereHas('calificacion');
    }

    /**
     * Scope: Entregas de un estudiante específico.
     */
    public function scopeDeEstudiante($query, int $estudianteId)
    {
        return $query->where('user_id', $estudianteId);
    }

    /**
     * Scope: Entregas de una tarea específica.
     */
    public function scopeDeTarea($query, int $tareaId)
    {
        return $query->where('tarea_id', $tareaId);
    }
}