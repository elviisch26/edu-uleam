<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; 
class Tarea extends Model
{
    use HasFactory;


    protected $table = 'tareas'; 
    protected $fillable = [
        'titulo',
        'descripcion',
        'fecha_entrega',
        'ruta_archivo_guia',
    ];
    public function entregas(): HasMany
    {
        return $this->hasMany(Entrega::class);
    }
}