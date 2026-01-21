<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTareaRequest;
use App\Http\Requests\UpdateTareaRequest;
use App\Models\Materia;
use App\Models\Tarea;
use App\Services\TareaService;
use Illuminate\Http\Request;
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
     * Redirige al listado de materias (nuevo flujo).
     */
    public function index(): RedirectResponse
    {
        return redirect()->route('docente.materias.index');
    }

    /**
     * Muestra el formulario para crear tarea dentro de una materia.
     */
    public function createForMateria(Materia $materia): View
    {
        // Verificar que la materia pertenece al docente
        if ($materia->docente_id !== Auth::id()) {
            abort(403);
        }

        return view('docente.materias.tareas.create', compact('materia'));
    }

    /**
     * Almacena una nueva tarea para una materia especifica.
     */
    public function storeForMateria(Request $request, Materia $materia): RedirectResponse
    {
        // Verificar que la materia pertenece al docente
        if ($materia->docente_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'titulo' => 'required|string|max:255|min:5',
            'descripcion' => 'required|string|min:10',
            'fecha_entrega' => 'required|date|after:now',
            'archivo_guia' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:5120',
        ], [
            'titulo.required' => 'El titulo es obligatorio.',
            'titulo.min' => 'El titulo debe tener al menos 5 caracteres.',
            'descripcion.required' => 'La descripcion es obligatoria.',
            'descripcion.min' => 'La descripcion debe tener al menos 10 caracteres.',
            'fecha_entrega.required' => 'La fecha de entrega es obligatoria.',
            'fecha_entrega.after' => 'La fecha de entrega debe ser futura.',
        ]);

        // Agregar materia_id a los datos validados
        $validated['materia_id'] = $materia->id;

        $this->tareaService->crearTarea(
            $validated,
            $request->file('archivo_guia')
        );

        return redirect()
            ->route('docente.materias.show', $materia)
            ->with('success', 'Tarea creada exitosamente.');
    }

    /**
     * Muestra el detalle de una tarea.
     */
    public function show(Tarea $tarea): View|RedirectResponse
    {
        $user = Auth::user();

        // Logica para el ESTUDIANTE
        if ($user->rol->nombre === 'estudiante') {
            $entregaRealizada = $user->entregas()
                ->where('tarea_id', $tarea->id)
                ->with('calificacion')
                ->first();

            return view('estudiante.tareas.show', compact('tarea', 'entregaRealizada'));
        }

        // Logica para el DOCENTE
        if ($user->rol->nombre === 'docente') {
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
        $tarea->load('materia');

        return view('docente.tareas.edit', compact('tarea'));
    }

    /**
     * Actualiza una tarea existente.
     */
    public function update(Request $request, Tarea $tarea): RedirectResponse
    {
        $this->authorize('update', $tarea);

        $validated = $request->validate([
            'titulo' => 'required|string|max:255|min:5',
            'descripcion' => 'required|string|min:10',
            'fecha_entrega' => 'required|date',
            'archivo_guia' => 'nullable|file|mimes:pdf,doc,docx,jpg,jpeg,png,zip|max:5120',
        ]);

        // Mantener la materia existente
        $validated['materia_id'] = $tarea->materia_id;

        $this->tareaService->actualizarTarea(
            $tarea,
            $validated,
            $request->file('archivo_guia')
        );

        return redirect()
            ->route('docente.materias.show', $tarea->materia_id)
            ->with('success', 'Tarea actualizada exitosamente.');
    }

    /**
     * Elimina una tarea.
     */
    public function destroy(Tarea $tarea): RedirectResponse
    {
        $this->authorize('delete', $tarea);
        
        $materiaId = $tarea->materia_id;

        $this->tareaService->eliminarTarea($tarea);

        return redirect()
            ->route('docente.materias.show', $materiaId)
            ->with('success', 'Tarea eliminada exitosamente.');
    }
}
