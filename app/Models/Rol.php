<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Rol extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = ['nombre'];

    /**
     * Relación: Un Rol tiene muchos Usuarios.
     */
    public function usuarios(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Verifica si el rol es de administrador.
     */
    public function esAdmin(): bool
    {
        return $this->nombre === 'admin';
    }

    /**
     * Verifica si el rol es de docente.
     */
    public function esDocente(): bool
    {
        return $this->nombre === 'docente';
    }

    /**
     * Verifica si el rol es de estudiante.
     */
    public function esEstudiante(): bool
    {
        return $this->nombre === 'estudiante';
    }
}
