<?php

namespace App\Http\Controllers;

use App\Models\Calificacion;
use App\Models\Entrega;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class CalificacionController extends Controller
{
    /**
     * Almacena una nueva calificación para una entrega.
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'entrega_id' => 'required|exists:entregas,id',
            'calificacion' => 'required|numeric|between:0,10',
            'retroalimentacion' => 'nullable|string|max:1000',
        ]);
        
        $entrega = Entrega::findOrFail($datos['entrega_id']);

        if ($entrega->tarea->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        if ($entrega->calificacion) {
            return back()->with('error', 'Esta tarea ya ha sido calificada.');
        }

        Calificacion::create($datos);

        $tareaId = $entrega->tarea_id;
        return redirect()->route('docente.tareas.show', $tareaId)
                         ->with('success', '¡Calificación guardada con éxito!');
    }

    /**
     * Muestra el formulario para editar una calificación existente.
     */
    public function edit(Calificacion $calificacion): View
    {
        if ($calificacion->entrega->tarea->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        return view('docente.calificaciones.edit', compact('calificacion'));
    }

    /**
     * Actualiza una calificación específica en la base de datos.
     */
    public function update(Request $request, Calificacion $calificacion): RedirectResponse
    {
        if ($calificacion->entrega->tarea->user_id !== Auth::id()) {
            abort(403, 'Acción no autorizada.');
        }

        $datos = $request->validate([
            'calificacion' => 'required|numeric|between:0,10',
            'retroalimentacion' => 'nullable|string|max:1000',
        ]);

        $calificacion->update($datos);

        $tareaId = $calificacion->entrega->tarea_id;
        return redirect()->route('docente.tareas.show', $tareaId)
                         ->with('success', '¡Calificación actualizada correctamente!');
    }
}