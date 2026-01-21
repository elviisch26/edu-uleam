<?php

namespace App\Services;

use App\Models\Tarea;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

/**
 * Servicio para gestionar la lógica de negocio de Tareas.
 */
class TareaService
{
    /**
     * Crea una nueva tarea con los datos proporcionados.
     *
     * @param array $datos Datos validados de la tarea
     * @param UploadedFile|null $archivo Archivo guía opcional
     * @return Tarea
     */
    public function crearTarea(array $datos, ?UploadedFile $archivo = null): Tarea
    {
        if ($archivo) {
            $datosArchivo = $this->almacenarArchivo($archivo, 'guias_tareas');
            $datos['ruta_archivo_guia'] = $datosArchivo['path'];
            $datos['nombre_archivo_guia'] = $datosArchivo['name'];
        }

        $tarea = Auth::user()->tareas()->create($datos);

        Log::info('Tarea creada', [
            'tarea_id' => $tarea->id,
            'docente_id' => $tarea->user_id,
            'titulo' => $tarea->titulo,
        ]);

        return $tarea;
    }

    /**
     * Actualiza una tarea existente.
     *
     * @param Tarea $tarea La tarea a actualizar
     * @param array $datos Datos validados de la tarea
     * @param UploadedFile|null $archivo Nuevo archivo guía opcional
     * @return bool
     */
    public function actualizarTarea(Tarea $tarea, array $datos, ?UploadedFile $archivo = null): bool
    {
        if ($archivo) {
            // Eliminar archivo anterior si existe
            if ($tarea->ruta_archivo_guia) {
                $this->eliminarArchivo($tarea->ruta_archivo_guia);
            }

            $datosArchivo = $this->almacenarArchivo($archivo, 'guias_tareas');
            $datos['ruta_archivo_guia'] = $datosArchivo['path'];
            $datos['nombre_archivo_guia'] = $datosArchivo['name'];
        }

        $resultado = $tarea->update($datos);

        Log::info('Tarea actualizada', [
            'tarea_id' => $tarea->id,
            'docente_id' => Auth::id(),
            'titulo' => $tarea->titulo,
        ]);

        return $resultado;
    }

    /**
     * Elimina una tarea y todos sus archivos asociados.
     *
     * @param Tarea $tarea La tarea a eliminar
     * @return bool
     */
    public function eliminarTarea(Tarea $tarea): bool
    {
        // Eliminar archivo guía si existe
        if ($tarea->ruta_archivo_guia) {
            $this->eliminarArchivo($tarea->ruta_archivo_guia);
        }

        // Eliminar archivos de entregas asociadas
        foreach ($tarea->entregas as $entrega) {
            if ($entrega->ruta_archivo) {
                Storage::disk('private')->delete($entrega->ruta_archivo);
            }
        }

        Log::info('Tarea eliminada', [
            'tarea_id' => $tarea->id,
            'docente_id' => Auth::id(),
            'titulo' => $tarea->titulo,
        ]);

        return $tarea->delete();
    }

    /**
     * Obtiene las tareas del docente autenticado con conteo de entregas.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function obtenerTareasDocente()
    {
        return Auth::user()
            ->tareas()
            ->with('materia')
            ->withCount('entregas')
            ->with(['entregas' => function ($query) {
                $query->whereHas('calificacion');
            }])
            ->latest()
            ->get();
    }

    /**
     * Obtiene el detalle de una tarea con sus entregas y calificaciones.
     *
     * @param Tarea $tarea
     * @return Tarea
     */
    public function obtenerDetalleTarea(Tarea $tarea): Tarea
    {
        return $tarea->load(['entregas.user', 'entregas.calificacion', 'materia']);
    }

    /**
     * Almacena un archivo en el disco especificado.
     *
     * @param UploadedFile $archivo
     * @param string $directorio
     * @param string $disco
     * @return array
     */
    private function almacenarArchivo(UploadedFile $archivo, string $directorio, string $disco = 'public'): array
    {
        return [
            'path' => $archivo->store($directorio, $disco),
            'name' => $archivo->getClientOriginalName(),
            'size' => $archivo->getSize(),
            'mime' => $archivo->getMimeType(),
        ];
    }

    /**
     * Elimina un archivo del disco público.
     *
     * @param string $ruta
     * @return bool
     */
    private function eliminarArchivo(string $ruta): bool
    {
        if (Storage::disk('public')->exists($ruta)) {
            return Storage::disk('public')->delete($ruta);
        }
        return false;
    }
}
