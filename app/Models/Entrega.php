<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory; 
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; 
use Illuminate\Database\Eloquent\Relations\HasOne; 
class Entrega extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'ruta_archivo',
        'tarea_id',
    ];
    public function tarea(): BelongsTo
    {
        return $this->belongsTo(Tarea::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
     public function calificacion()
    {
        return $this->hasOne(Calificacion::class);
    }
}