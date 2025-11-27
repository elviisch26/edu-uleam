<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCalificacionRequest;
use App\Http\Requests\UpdateCalificacionRequest;
use App\Models\Calificacion;
use App\Services\CalificacionService;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador para gestionar las Calificaciones.
 */
class CalificacionController extends Controller
{
    /**
     * Servicio de calificaciones.
     */
    protected CalificacionService $calificacionService;

    /**
     * Constructor del controlador.
     */
    public function __construct(CalificacionService $calificacionService)
    {
        $this->calificacionService = $calificacionService;
    }

    /**
     * Almacena una nueva calificación para una entrega.
     */
    public function store(StoreCalificacionRequest $request): RedirectResponse
    {
        try {
            $calificacion = $this->calificacionService->crearCalificacion(
                $request->validated()
            );

            $tareaId = $calificacion->entrega->tarea_id;

            return redirect()
                ->route('docente.tareas.show', $tareaId)
                ->with('success', '¡Calificación guardada con éxito!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Muestra el formulario para editar una calificación existente.
     */
    public function edit(Calificacion $calificacion): View
    {
        $this->authorize('update', $calificacion);

        return view('docente.calificaciones.edit', compact('calificacion'));
    }

    /**
     * Actualiza una calificación específica en la base de datos.
     */
    public function update(UpdateCalificacionRequest $request, Calificacion $calificacion): RedirectResponse
    {
        $this->authorize('update', $calificacion);

        $this->calificacionService->actualizarCalificacion(
            $calificacion,
            $request->validated()
        );

        $tareaId = $calificacion->entrega->tarea_id;

        return redirect()
            ->route('docente.tareas.show', $tareaId)
            ->with('success', '¡Calificación actualizada correctamente!');
    }
}