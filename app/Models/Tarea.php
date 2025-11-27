<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Tarea extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tareas';

    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_entrega',
        'ruta_archivo_guia',
        'nombre_archivo_guia',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'fecha_entrega' => 'datetime',
    ];

    /**
     * Relación: Una Tarea pertenece a un Docente (Usuario).
     */
    public function docente(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relación: Una Tarea tiene muchas Entregas.
     */
    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class);
    }

    /**
     * Relación: Una Tarea tiene muchas Calificaciones a través de Entregas.
     */
    public function calificaciones(): HasManyThrough
    {
        return $this->hasManyThrough(Calificacion::class, Entrega::class);
    }

    /**
     * Accessor: Verifica si la tarea está vencida.
     */
    public function getEstaVencidaAttribute(): bool
    {
        return Carbon::now()->gt($this->fecha_entrega);
    }

    /**
     * Accessor: Obtiene los días restantes para entregar.
     */
    public function getDiasRestantesAttribute(): int
    {
        return Carbon::now()->diffInDays($this->fecha_entrega, false);
    }

    /**
     * Accessor: Verifica si tiene archivo guía.
     */
    public function getTieneArchivoGuiaAttribute(): bool
    {
        return !empty($this->ruta_archivo_guia);
    }

    /**
     * Accessor: Obtiene el estado de la tarea.
     */
    public function getEstadoAttribute(): string
    {
        if ($this->esta_vencida) {
            return 'vencida';
        }

        if ($this->dias_restantes <= 3) {
            return 'por_vencer';
        }

        return 'activa';
    }

    /**
     * Scope: Tareas activas (no vencidas).
     */
    public function scopeActivas($query)
    {
        return $query->where('fecha_entrega', '>=', now());
    }

    /**
     * Scope: Tareas vencidas.
     */
    public function scopeVencidas($query)
    {
        return $query->where('fecha_entrega', '<', now());
    }

    /**
     * Scope: Tareas de un docente específico.
     */
    public function scopeDeDocente($query, int $docenteId)
    {
        return $query->where('user_id', $docenteId);
    }
}