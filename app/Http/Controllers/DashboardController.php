<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\Tarea;
use App\Models\Calificacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador para gestionar el Dashboard.
 */
class DashboardController extends Controller
{
    /**
     * Muestra el dashboard según el rol del usuario.
     *
     * @return View|RedirectResponse
     */
    public function index(): View|RedirectResponse
    {
        $user = Auth::user();

        return match ($user->rol->nombre) {
            'estudiante' => $this->estudianteDashboard(),
            'docente' => redirect()->route('docente.tareas.index'),
            'admin' => $this->adminDashboard(),
            default => view('dashboard'),
        };
    }

    /**
     * Dashboard para estudiantes.
     *
     * @return View
     */
    private function estudianteDashboard(): View
    {
        $user = Auth::user();

        // Obtener todas las tareas con eager loading optimizado
        $tareas = Tarea::with(['docente:id,name'])
            ->latest()
            ->get();

        // IDs de tareas ya entregadas
        $tareasEntregadasIds = $user->entregas()->pluck('tarea_id');

        // Entregas del estudiante con calificaciones
        $entregas = $user->entregas()
            ->with('calificacion')
            ->get()
            ->keyBy('tarea_id');

        // Estadísticas del estudiante
        $estadisticas = $this->obtenerEstadisticasEstudiante($user);

        return view('dashboard', compact('tareas', 'tareasEntregadasIds', 'entregas', 'estadisticas'));
    }

    /**
     * Dashboard para administradores.
     *
     * @return View
     */
    private function adminDashboard(): View
    {
        $estadisticas = [
            'total_tareas' => Tarea::count(),
            'total_entregas' => Entrega::count(),
            'total_calificaciones' => Calificacion::count(),
            'promedio_general' => Calificacion::avg('calificacion') ?? 0,
        ];

        return view('dashboard', compact('estadisticas'));
    }

    /**
     * Obtiene estadísticas para un estudiante.
     *
     * @param \App\Models\User $user
     * @return array
     */
    private function obtenerEstadisticasEstudiante($user): array
    {
        $entregas = $user->entregas()->with('calificacion')->get();
        $calificaciones = $entregas->pluck('calificacion')->filter();

        return [
            'total_entregas' => $entregas->count(),
            'entregas_calificadas' => $calificaciones->count(),
            'promedio' => $calificaciones->avg('calificacion') ?? 0,
            'tareas_pendientes' => Tarea::where('fecha_entrega', '>=', now())
                ->whereNotIn('id', $entregas->pluck('tarea_id'))
                ->count(),
        ];
    }
}
