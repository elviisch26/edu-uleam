<?php

namespace App\Http\Controllers;
use App\Models\Tarea;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Entrega;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EntregaController extends Controller {
    public function create(Tarea $tarea) {
        return view('estudiante.entrega.create', compact('tarea'));
    }

    public function store(Request $request, Tarea $tarea) {
        $request->validate([
            'archivo_entrega' => 'required|file|mimes:pdf,zip|max:5120', // PDF o ZIP, max 5MB
        ]);

        $rutaArchivo = $request->file('archivo_entrega')->store('entregas', 'private');

        $tarea->entregas()->create([
            'user_id' => Auth::id(),
            'ruta_archivo' => $rutaArchivo,
        ]);

        return redirect()->route('dashboard')->with('success', '¡Tarea entregada con éxito!');
    }
    public function show(Tarea $tarea) {
    if ($tarea->user_id !== Auth::id()) {
        abort(403);
    }
    $tarea->load('entregas.usuario');
    return view('docente.tareas.show', compact('tarea'));
}
 public function descargar(Entrega $entrega)
    {
        // --- CAPA 1 DE SEGURIDAD: AUTORIZACIÓN ---
        // Nos aseguramos de que el profesor que intenta descargar
        // es el mismo que creó la tarea original.
        if (Auth::id() !== $entrega->tarea->user_id) {
            abort(403, 'Acción no autorizada.');
        }

        // --- CAPA 2 DE SEGURIDAD: VERIFICACIÓN DE ARCHIVO ---
        // Verificamos que el archivo realmente exista en nuestro disco privado.
        if (!Storage::disk('private')->exists($entrega->ruta_archivo)) {
            abort(404, 'Archivo no encontrado.');
        }
        
        // --- CONSTRUCCIÓN DEL NOMBRE DE ARCHIVO ---
        // Para que el usuario no descargue un archivo con un nombre raro como "aBc123XyZ.pdf",
        // vamos a construir un nombre de archivo descriptivo y limpio.
        $nombreOriginal = pathinfo($entrega->ruta_archivo, PATHINFO_FILENAME);
        $extension = pathinfo($entrega->ruta_archivo, PATHINFO_EXTENSION);
        $nombreAmigable = "entrega_" . Str::slug($entrega->user->name) . "_tarea_" . $entrega->tarea->id . "." . $extension;

        // --- LA DESCARGA ---
        // Le decimos a Laravel que devuelva el archivo desde el disco 'private'
        // con el nombre amigable que acabamos de construir.
        return Storage::disk('private')->download($entrega->ruta_archivo, $nombreAmigable);
    }
}
