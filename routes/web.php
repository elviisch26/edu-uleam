<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TareaController;
use App\Http\Controllers\EntregaController;
use App\Http\Controllers\CalificacionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MateriaController;

// Redirige la raíz al login
Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('auth')->group(function () {
    // Dashboard con controlador dedicado
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // RUTAS DEL PERFIL DE USUARIO
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// -- GRUPO DE RUTAS PARA DOCENTES --
Route::middleware(['auth', 'rol:docente', 'throttle:60,1'])->prefix('docente')->name('docente.')->group(function () {
    // Materias (cards)
    Route::get('materias', [MateriaController::class, 'index'])->name('materias.index');
    Route::get('materias/create', [MateriaController::class, 'create'])->name('materias.create');
    Route::post('materias', [MateriaController::class, 'store'])->name('materias.store');
    Route::get('materias/{materia}', [MateriaController::class, 'show'])->name('materias.show');
    
    // Tareas dentro de una materia
    Route::get('materias/{materia}/tareas/create', [TareaController::class, 'createForMateria'])->name('materias.tareas.create');
    Route::post('materias/{materia}/tareas', [TareaController::class, 'storeForMateria'])->name('materias.tareas.store');
    
    // Tareas (rutas existentes para ver, editar, eliminar)
    Route::resource('tareas', TareaController::class)->except(['index', 'create', 'store']);
    
    Route::post('calificaciones', [CalificacionController::class, 'store'])->name('calificaciones.store');
    Route::get('/entregas/{entrega}/descargar', [EntregaController::class, 'descargar'])->name('entregas.descargar');
    Route::get('calificaciones/{calificacion}/edit', [CalificacionController::class, 'edit'])->name('calificaciones.edit');
    Route::put('calificaciones/{calificacion}', [CalificacionController::class, 'update'])->name('calificaciones.update');
});

// -- GRUPO DE RUTAS PARA ESTUDIANTES --
Route::middleware(['auth', 'rol:estudiante', 'throttle:60,1'])->prefix('estudiante')->name('estudiante.')->group(function () {
    // Muestra la página de detalles de una tarea
    Route::get('/tareas/{tarea}', [TareaController::class, 'show'])->name('tareas.show');

    // Procesa el envío del formulario de entrega
    Route::post('/entregas/{tarea}', [EntregaController::class, 'store'])->name('entregas.store');
});

require __DIR__ . '/auth.php';