<?php

namespace App\Policies;

use App\Models\Entrega;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

/**
 * Policy para autorización de acciones sobre Entregas.
 */
class EntregaPolicy
{
    use HandlesAuthorization;

    /**
     * Determina si el usuario puede ver cualquier entrega.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->rol->nombre, ['docente', 'admin']);
    }

    /**
     * Determina si el usuario puede ver una entrega específica.
     */
    public function view(User $user, Entrega $entrega): bool
    {
        // El estudiante puede ver su propia entrega
        if ($user->rol->nombre === 'estudiante') {
            return $user->id === $entrega->user_id;
        }

        // El docente puede ver entregas de sus tareas
        if ($user->rol->nombre === 'docente') {
            return $user->id === $entrega->tarea->user_id;
        }

        return $user->rol->nombre === 'admin';
    }

    /**
     * Determina si el usuario puede crear una entrega.
     */
    public function create(User $user, Tarea $tarea): bool
    {
        // Solo estudiantes pueden crear entregas
        if ($user->rol->nombre !== 'estudiante') {
            return false;
        }

        // Verificar que no haya entregado ya esta tarea
        $yaEntrego = $user->entregas()->where('tarea_id', $tarea->id)->exists();
        if ($yaEntrego) {
            return false;
        }

        // Verificar que la fecha de entrega no haya pasado
        if (now()->gt($tarea->fecha_entrega)) {
            return false;
        }

        return true;
    }

    /**
     * Determina si el usuario puede descargar el archivo de una entrega.
     */
    public function download(User $user, Entrega $entrega): bool
    {
        // El estudiante puede descargar su propia entrega
        if ($user->rol->nombre === 'estudiante') {
            return $user->id === $entrega->user_id;
        }

        // El docente puede descargar entregas de sus tareas
        if ($user->rol->nombre === 'docente') {
            return $user->id === $entrega->tarea->user_id;
        }

        return $user->rol->nombre === 'admin';
    }

    /**
     * Determina si el usuario puede eliminar una entrega.
     */
    public function delete(User $user, Entrega $entrega): bool
    {
        // Solo admin puede eliminar entregas
        return $user->rol->nombre === 'admin';
    }
}
