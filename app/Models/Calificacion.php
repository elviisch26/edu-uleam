<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calificacion extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * El nombre de la tabla asociada con el modelo.
     */
    protected $table = 'calificaciones';

    /**
     * Atributos que SÍ se pueden guardar en masa.
     */
    protected $fillable = [
        'entrega_id',
        'calificacion',
        'retroalimentacion',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'calificacion' => 'decimal:2',
    ];

    /**
     * Relación: Una Calificación pertenece a una Entrega.
     */
    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class);
    }

    /**
     * Accessor: Obtiene el estado de la calificación.
     */
    public function getEstadoAttribute(): string
    {
        if ($this->calificacion >= 7) {
            return 'aprobado';
        } elseif ($this->calificacion >= 5) {
            return 'regular';
        }
        return 'reprobado';
    }

    /**
     * Accessor: Obtiene la calificación formateada.
     */
    public function getCalificacionFormateadaAttribute(): string
    {
        return number_format($this->calificacion, 2) . '/10';
    }

    /**
     * Scope: Calificaciones aprobatorias (>= 7).
     */
    public function scopeAprobadas($query)
    {
        return $query->where('calificacion', '>=', 7);
    }

    /**
     * Scope: Calificaciones reprobatorias (< 7).
     */
    public function scopeReprobadas($query)
    {
        return $query->where('calificacion', '<', 7);
    }
}