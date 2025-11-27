<?php

namespace App\Policies;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para autorización de acciones sobre Tareas.
 */
class TareaPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier tarea.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->rol->nombre, ['docente', 'estudiante', 'admin']);
    }

    /**
     * Determina si el usuario puede ver una tarea específica.
     */
    public function view(User $user, Tarea $tarea): bool
    {
        // Los estudiantes pueden ver cualquier tarea
        if ($user->rol->nombre === 'estudiante') {
            return true;
        }

        // Los docentes solo pueden ver sus propias tareas
        if ($user->rol->nombre === 'docente') {
            return $user->id === $tarea->user_id;
        }

        // Admin puede ver todo
        return $user->rol->nombre === 'admin';
    }

    /**
     * Determina si el usuario puede crear tareas.
     */
    public function create(User $user): bool
    {
        return in_array($user->rol->nombre, ['docente', 'admin']);
    }

    /**
     * Determina si el usuario puede actualizar una tarea.
     */
    public function update(User $user, Tarea $tarea): bool
    {
        // Solo el docente dueño puede actualizar
        if ($user->rol->nombre === 'docente') {
            return $user->id === $tarea->user_id;
        }

        return $user->rol->nombre === 'admin';
    }

    /**
     * Determina si el usuario puede eliminar una tarea.
     */
    public function delete(User $user, Tarea $tarea): bool
    {
        // Solo el docente dueño puede eliminar
        if ($user->rol->nombre === 'docente') {
            return $user->id === $tarea->user_id;
        }

        return $user->rol->nombre === 'admin';
    }

    /**
     * Determina si el usuario puede descargar el archivo guía.
     */
    public function downloadGuide(User $user, Tarea $tarea): bool
    {
        // Estudiantes y el docente dueño pueden descargar
        if ($user->rol->nombre === 'estudiante') {
            return true;
        }

        if ($user->rol->nombre === 'docente') {
            return $user->id === $tarea->user_id;
        }

        return $user->rol->nombre === 'admin';
    }
}
