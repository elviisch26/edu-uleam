<?php

namespace App\Services;

use App\Models\Entrega;
use App\Models\Tarea;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Servicio para gestionar la lógica de negocio de Entregas.
 */
class EntregaService
{
    /**
     * Crea una nueva entrega para una tarea.
     *
     * @param Tarea $tarea La tarea para la cual se crea la entrega
     * @param UploadedFile $archivo El archivo de la entrega
     * @return Entrega
     * @throws \Exception Si el estudiante ya entregó o la fecha expiró
     */
    public function crearEntrega(Tarea $tarea, UploadedFile $archivo): Entrega
    {
        $user = Auth::user();

        // Verificar si ya existe una entrega
        if ($user->entregas()->where('tarea_id', $tarea->id)->exists()) {
            throw new \Exception('Ya has entregado esta tarea.');
        }

        // Verificar que la fecha de entrega no haya pasado
        if (now()->gt($tarea->fecha_entrega)) {
            throw new \Exception('La fecha de entrega ha expirado.');
        }

        // Almacenar el archivo
        $rutaArchivo = $archivo->store('entregas', 'private');

        // Crear la entrega
        $entrega = $tarea->entregas()->create([
            'user_id' => $user->id,
            'ruta_archivo' => $rutaArchivo,
        ]);

        Log::info('Entrega realizada', [
            'entrega_id' => $entrega->id,
            'estudiante_id' => $user->id,
            'tarea_id' => $tarea->id,
            'archivo' => $rutaArchivo,
        ]);

        return $entrega;
    }

    /**
     * Descarga el archivo de una entrega.
     *
     * @param Entrega $entrega
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function descargarEntrega(Entrega $entrega)
    {
        // Verificar que el archivo existe
        if (!Storage::disk('private')->exists($entrega->ruta_archivo)) {
            abort(404, 'Archivo no encontrado.');
        }

        // Construir nombre amigable para la descarga
        $extension = pathinfo($entrega->ruta_archivo, PATHINFO_EXTENSION);
        $nombreAmigable = sprintf(
            'entrega_%s_tarea_%d.%s',
            Str::slug($entrega->user->name),
            $entrega->tarea->id,
            $extension
        );

        Log::info('Descarga de entrega', [
            'entrega_id' => $entrega->id,
            'descargado_por' => Auth::id(),
        ]);

        return Storage::disk('private')->download($entrega->ruta_archivo, $nombreAmigable);
    }

    /**
     * Obtiene las entregas del estudiante autenticado.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerEntregasEstudiante()
    {
        return Auth::user()
            ->entregas()
            ->with(['tarea', 'calificacion'])
            ->latest()
            ->get();
    }

    /**
     * Verifica si un estudiante puede entregar una tarea.
     *
     * @param Tarea $tarea
     * @return array ['puede' => bool, 'razon' => string|null]
     */
    public function puedeEntregar(Tarea $tarea): array
    {
        $user = Auth::user();

        if ($user->rol->nombre !== 'estudiante') {
            return ['puede' => false, 'razon' => 'Solo estudiantes pueden realizar entregas.'];
        }

        if ($user->entregas()->where('tarea_id', $tarea->id)->exists()) {
            return ['puede' => false, 'razon' => 'Ya has entregado esta tarea.'];
        }

        if (now()->gt($tarea->fecha_entrega)) {
            return ['puede' => false, 'razon' => 'La fecha de entrega ha expirado.'];
        }

        return ['puede' => true, 'razon' => null];
    }
}
