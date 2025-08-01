<?php

namespace App\Models; // <-- 1. Namespace correcto

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model; // <-- 2. Importación de la clase Model
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Calificacion extends Model
{
    use HasFactory;

    /**
     * El nombre de la tabla asociada con el modelo.
     */
    protected $table = 'calificaciones';

    /**
     * ¡LA PROPIEDAD MÁS IMPORTANTE!
     * Atributos que SÍ se pueden guardar en masa.
     */
    protected $fillable = [
        'entrega_id',
        'calificacion',
        'retroalimentacion',
    ];

    /**
     * Relación: Una Calificación pertenece a una Entrega.
     */
    public function entrega(): BelongsTo
    {
        return $this->belongsTo(Entrega::class);
    }
}