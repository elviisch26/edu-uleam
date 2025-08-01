<?php
namespace App\Http\Controllers;

use App\Models\Tarea; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
class TareaController extends Controller
{
    /**
     * Almacena una nueva entrega de tarea subida por un estudiante.
     */
    public function store(Request $request)
{
    $datosValidados = $request->validate([
        'titulo' => 'required|string|max:255',
        'descripcion' => 'required|string',
        'fecha_entrega' => 'required|date|after:now',
        'archivo_guia' => 'nullable|file|mimes:pdf,doc,docx,jpg,png,zip|max:5120', // Max 5MB
        'nombre_archivo_guia',
    ]);

    if ($request->hasFile('archivo_guia')) {
            // Obtenemos el nombre original del archivo
            $nombreOriginal = $request->file('archivo_guia')->getClientOriginalName();
            
            // Guardamos el archivo con un nombre hasheado y seguro
            $rutaArchivo = $request->file('archivo_guia')->store('guias_tareas', 'public');
            
            // Añadimos AMBOS datos para guardarlos
            $datosValidados['ruta_archivo_guia'] = $rutaArchivo;
            $datosValidados['nombre_archivo_guia'] = $nombreOriginal;
        }

        Auth::user()->tareas()->create($datosValidados);

        return redirect()->route('docente.tareas.index')->with('success', '¡Tarea creada con éxito!');
    }

    public function show(Tarea $tarea)
{
    $user = Auth::user();

    // Lógica para el ESTUDIANTE
    if ($user->rol->nombre === 'estudiante') {
        $entregaRealizada = $user->entregas()->where('tarea_id', $tarea->id)->first();
        return view('estudiante.tareas.show', compact('tarea', 'entregaRealizada'));
    }

    // Lógica para el DOCENTE
    if ($user->rol->nombre === 'docente') {
        // Primero, una medida de seguridad: un docente solo puede ver SUS tareas.
        if ($user->id !== $tarea->user_id) {
            abort(403, 'Acción no autorizada.');
        }

        $tarea = $tarea->fresh(['entregas.user', 'entregas.calificacion']);

    // Pasamos la versión fresca a la vista.
    return view('docente.tareas.show', ['tarea' => $tarea]);
}

    // Si por alguna razón llega otro rol, lo mandamos fuera.
    return redirect()->route('dashboard');
}
    public function create()
    {
        // La única responsabilidad de este método es mostrar la vista
        // con el formulario de creación.
        return view('docente.tareas.create');
    }

    public function index()
    {
        // 1. Obtener solo las tareas del docente que ha iniciado sesión
        $tareas = Auth::user()->tareas()->latest()->get();

        // 2. Devolver la vista del panel del docente, pasándole las tareas
        return view('docente.tareas.index', compact('tareas'));
    }
    public function edit(Tarea $tarea)
    {
        // Medida de seguridad: solo el dueño puede editar.
        if (Auth::id() !== $tarea->user_id) {
            abort(403);
        }
        return view('docente.tareas.edit', compact('tarea'));
    }
    public function update(Request $request, Tarea $tarea)
    {
        // Medida de seguridad
        if (Auth::id() !== $tarea->user_id) {
            abort(403);
        }

        $datosValidados = $request->validate([
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_entrega' => 'required|date',
        ]);

        $tarea->update($datosValidados);

        return redirect()->route('docente.tareas.index')->with('success', '¡Tarea actualizada con éxito!');
    }
    public function destroy(Tarea $tarea)
    {
        // Medida de seguridad
        if (Auth::id() !== $tarea->user_id) {
            abort(403);
        }

        // Antes de borrar la tarea, borraremos los archivos asociados a sus entregas
        foreach ($tarea->entregas as $entrega) {
            Storage::disk('private')->delete($entrega->ruta_archivo);
        }
        
        $tarea->delete();

        return redirect()->route('docente.tareas.index')->with('success', '¡Tarea eliminada con éxito!');
    }
}
