<?php

namespace App\Services;

use App\Models\Calificacion;
use App\Models\Entrega;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para gestionar la lógica de negocio de Calificaciones.
 */
class CalificacionService
{
    /**
     * Crea una nueva calificación para una entrega.
     *
     * @param array $datos Datos validados de la calificación
     * @return Calificacion
     * @throws \Exception Si la entrega ya está calificada
     */
    public function crearCalificacion(array $datos): Calificacion
    {
        $entrega = Entrega::findOrFail($datos['entrega_id']);

        // Verificar que no exista ya una calificación
        if ($entrega->calificacion()->exists()) {
            throw new \Exception('Esta entrega ya ha sido calificada.');
        }

        // Verificar autorización
        if ($entrega->tarea->user_id !== Auth::id()) {
            throw new \Exception('No tienes autorización para calificar esta entrega.');
        }

        $calificacion = Calificacion::create($datos);

        Log::info('Calificación creada', [
            'calificacion_id' => $calificacion->id,
            'entrega_id' => $entrega->id,
            'docente_id' => Auth::id(),
            'nota' => $calificacion->calificacion,
        ]);

        return $calificacion;
    }

    /**
     * Actualiza una calificación existente.
     *
     * @param Calificacion $calificacion La calificación a actualizar
     * @param array $datos Datos validados de la calificación
     * @return bool
     */
    public function actualizarCalificacion(Calificacion $calificacion, array $datos): bool
    {
        $resultado = $calificacion->update($datos);

        Log::info('Calificación actualizada', [
            'calificacion_id' => $calificacion->id,
            'docente_id' => Auth::id(),
            'nota' => $datos['calificacion'],
        ]);

        return $resultado;
    }

    /**
     * Obtiene estadísticas de calificaciones para un docente.
     *
     * @return array
     */
    public function obtenerEstadisticasDocente(): array
    {
        $docente = Auth::user();

        $calificaciones = Calificacion::whereHas('entrega.tarea', function ($query) use ($docente) {
            $query->where('user_id', $docente->id);
        })->get();

        return [
            'total_calificaciones' => $calificaciones->count(),
            'promedio' => $calificaciones->avg('calificacion') ?? 0,
            'nota_maxima' => $calificaciones->max('calificacion') ?? 0,
            'nota_minima' => $calificaciones->min('calificacion') ?? 0,
        ];
    }

    /**
     * Verifica si un docente puede calificar una entrega.
     *
     * @param Entrega $entrega
     * @return array ['puede' => bool, 'razon' => string|null]
     */
    public function puedeCalificar(Entrega $entrega): array
    {
        $user = Auth::user();

        if ($user->rol->nombre !== 'docente') {
            return ['puede' => false, 'razon' => 'Solo docentes pueden calificar.'];
        }

        if ($user->id !== $entrega->tarea->user_id) {
            return ['puede' => false, 'razon' => 'Solo puedes calificar entregas de tus propias tareas.'];
        }

        if ($entrega->calificacion()->exists()) {
            return ['puede' => false, 'razon' => 'Esta entrega ya ha sido calificada.'];
        }

        return ['puede' => true, 'razon' => null];
    }
}
