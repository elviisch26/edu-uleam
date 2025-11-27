<?php

namespace App\Policies;

use App\Models\Calificacion;
use App\Models\Entrega;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para autorización de acciones sobre Calificaciones.
 */
class CalificacionPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier calificación.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->rol->nombre, ['docente', 'estudiante', 'admin']);
    }

    /**
     * Determina si el usuario puede ver una calificación específica.
     */
    public function view(User $user, Calificacion $calificacion): bool
    {
        // El estudiante puede ver la calificación de su entrega
        if ($user->rol->nombre === 'estudiante') {
            return $user->id === $calificacion->entrega->user_id;
        }

        // El docente puede ver calificaciones de sus tareas
        if ($user->rol->nombre === 'docente') {
            return $user->id === $calificacion->entrega->tarea->user_id;
        }

        return $user->rol->nombre === 'admin';
    }

    /**
     * Determina si el usuario puede crear una calificación.
     */
    public function create(User $user, Entrega $entrega): bool
    {
        // Solo docentes pueden calificar
        if ($user->rol->nombre !== 'docente') {
            return false;
        }

        // Solo puede calificar entregas de sus propias tareas
        if ($user->id !== $entrega->tarea->user_id) {
            return false;
        }

        // Verificar que no exista ya una calificación
        if ($entrega->calificacion()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Determina si el usuario puede actualizar una calificación.
     */
    public function update(User $user, Calificacion $calificacion): bool
    {
        // Solo el docente dueño de la tarea puede actualizar
        if ($user->rol->nombre === 'docente') {
            return $user->id === $calificacion->entrega->tarea->user_id;
        }

        return $user->rol->nombre === 'admin';
    }

    /**
     * Determina si el usuario puede eliminar una calificación.
     */
    public function delete(User $user, Calificacion $calificacion): bool
    {
        // Solo admin puede eliminar calificaciones
        return $user->rol->nombre === 'admin';
    }
}
