<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\CalificacionController;
use App\Models\Tarea;

// Esta ruta está bien, redirige la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    
    Route::get('/dashboard', function () {
        $user = Auth::user();

        // Lógica para el ESTUDIANTE
        if ($user->rol->nombre === 'estudiante') {
            $tareas = Tarea::latest()->get();
            $tareasEntregadasIds = Auth::user()->entregas()->pluck('tarea_id');
            $entregas = Auth::user()->entregas()->with('calificacion')->get()->keyBy('tarea_id');
            return view('dashboard', compact('tareas', 'tareasEntregadasIds', 'entregas'));
        }

        // Lógica para el DOCENTE
        if ($user->rol->nombre === 'docente') {
            return redirect()->route('docente.tareas.index');
        }

        // Fallback por si es admin u otro rol
        return view('dashboard');

    })->name('dashboard');

    // RUTAS DEL PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


// -- GRUPO DE RUTAS PARA DOCENTES --
Route::middleware(['auth', 'rol:docente'])->prefix('docente')->name('docente.')->group(function () {
    Route::resource('tareas', TareaController::class);
    Route::post('calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
    Route::get('/entregas/{entrega}/descargar', [EntregaController::class, 'descargar'])->name('entregas.descargar');
    Route::get('calificaciones/{calificacion}/edit', [CalificacionController::class, 'edit'])->name('calificaciones.edit');
    Route::put('calificaciones/{calificacion}', [CalificacionController::class, 'update'])->name('calificaciones.update');
});



Route::middleware(['auth', 'rol:estudiante'])->prefix('estudiante')->name('estudiante.')->group(function () {
    
    // Muestra la página de detalles de una tarea (la que tiene el formulario de subida)
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');

    // Procesa el envío del formulario de entrega
   Route::post('/entregas/{tarea}', [EntregaController::class, 'store'])->name('entregas.store');

});
require __DIR__.'/auth.php';