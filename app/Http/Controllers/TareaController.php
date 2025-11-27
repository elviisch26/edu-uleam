<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTareaRequest;
use App\Http\Requests\UpdateTareaRequest;
use App\Models\Tarea;
use App\Services\TareaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador para gestionar las Tareas.
 */
class TareaController extends Controller
{
    /**
     * Servicio de tareas.
     */
    protected TareaService $tareaService;

    /**
     * Constructor del controlador.
     */
    public function __construct(TareaService $tareaService)
    {
        $this->tareaService = $tareaService;
    }

    /**
     * Muestra el listado de tareas del docente.
     */
    public function index(): View
    {
        $tareas = $this->tareaService->obtenerTareasDocente();

        return view('docente.tareas.index', compact('tareas'));
    }

    /**
     * Muestra el formulario para crear una nueva tarea.
     */
    public function create(): View
    {
        return view('docente.tareas.create');
    }

    /**
     * Almacena una nueva tarea.
     */
    public function store(StoreTareaRequest $request): RedirectResponse
    {
        $this->tareaService->crearTarea(
            $request->validated(),
            $request->file('archivo_guia')
        );

        return redirect()
            ->route('docente.tareas.index')
            ->with('success', '¡Tarea creada con éxito!');
    }

    /**
     * Muestra el detalle de una tarea.
     */
    public function show(Tarea $tarea): View|RedirectResponse
    {
        $user = Auth::user();

        // Lógica para el ESTUDIANTE
        if ($user->rol->nombre === 'estudiante') {
            $entregaRealizada = $user->entregas()
                ->where('tarea_id', $tarea->id)
                ->with('calificacion')
                ->first();

            return view('estudiante.tareas.show', compact('tarea', 'entregaRealizada'));
        }

        // Lógica para el DOCENTE
        if ($user->rol->nombre === 'docente') {
            // Verificar autorización usando Policy
            $this->authorize('view', $tarea);

            $tarea = $this->tareaService->obtenerDetalleTarea($tarea);

            return view('docente.tareas.show', ['tarea' => $tarea]);
        }

        return redirect()->route('dashboard');
    }

    /**
     * Muestra el formulario para editar una tarea.
     */
    public function edit(Tarea $tarea): View
    {
        $this->authorize('update', $tarea);

        return view('docente.tareas.edit', compact('tarea'));
    }

    /**
     * Actualiza una tarea existente.
     */
    public function update(UpdateTareaRequest $request, Tarea $tarea): RedirectResponse
    {
        $this->authorize('update', $tarea);

        $this->tareaService->actualizarTarea(
            $tarea,
            $request->validated(),
            $request->file('archivo_guia')
        );

        return redirect()
            ->route('docente.tareas.index')
            ->with('success', '¡Tarea actualizada con éxito!');
    }

    /**
     * Elimina una tarea.
     */
    public function destroy(Tarea $tarea): RedirectResponse
    {
        $this->authorize('delete', $tarea);

        $this->tareaService->eliminarTarea($tarea);

        return redirect()
            ->route('docente.tareas.index')
            ->with('success', '¡Tarea eliminada con éxito!');
    }
}
