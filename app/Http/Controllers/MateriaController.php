<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MateriaController extends Controller
{
    /**
     * Muestra las materias del docente como cards.
     */
    public function index(): View
    {
        $materias = Auth::user()->materiasImpartidas()
            ->withCount('tareas')
            ->withCount('estudiantes')
            ->get();

        return view('docente.materias.index', compact('materias'));
    }

    /**
     * Muestra el detalle de una materia con sus tareas.
     */
    public function show(Materia $materia): View|RedirectResponse
    {
        // Verificar que la materia pertenece al docente
        if ($materia->docente_id !== Auth::id()) {
            abort(403);
        }

        $materia->load(['tareas' => function ($query) {
            $query->withCount('entregas')->latest();
        }]);

        return view('docente.materias.show', compact('materia'));
    }

    /**
     * Formulario para crear una nueva materia.
     */
    public function create(): View
    {
        return view('docente.materias.create');
    }

    /**
     * Almacena una nueva materia.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'codigo' => 'required|string|max:20|unique:materias,codigo',
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:500',
        ], [
            'codigo.required' => 'El codigo de la materia es obligatorio.',
            'codigo.unique' => 'Este codigo ya existe.',
            'nombre.required' => 'El nombre de la materia es obligatorio.',
        ]);

        Auth::user()->materiasImpartidas()->create([
            'codigo' => strtoupper($request->codigo),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'docente_id' => Auth::id(),
        ]);

        return redirect()
            ->route('docente.materias.index')
            ->with('success', 'Materia creada exitosamente.');
    }
}
