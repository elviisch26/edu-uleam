<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEntregaRequest;
use App\Models\Entrega;
use App\Models\Tarea;
use App\Services\EntregaService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

/**
 * Controlador para gestionar las Entregas.
 */
class EntregaController extends Controller
{
    /**
     * Servicio de entregas.
     */
    protected EntregaService $entregaService;

    /**
     * Constructor del controlador.
     */
    public function __construct(EntregaService $entregaService)
    {
        $this->entregaService = $entregaService;
    }

    /**
     * Muestra el formulario para crear una entrega.
     */
    public function create(Tarea $tarea): View|RedirectResponse
    {
        // Verificar si puede entregar
        $verificacion = $this->entregaService->puedeEntregar($tarea);

        if (!$verificacion['puede']) {
            return redirect()
                ->route('dashboard')
                ->with('error', $verificacion['razon']);
        }

        return view('estudiante.entrega.create', compact('tarea'));
    }

    /**
     * Almacena una nueva entrega.
     */
    public function store(StoreEntregaRequest $request, Tarea $tarea): RedirectResponse
    {
        try {
            $this->entregaService->crearEntrega(
                $tarea,
                $request->file('archivo_entrega')
            );

            return redirect()
                ->route('dashboard')
                ->with('success', '¡Tarea entregada con éxito!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Muestra el detalle de una tarea con sus entregas.
     */
    public function show(Tarea $tarea): View|RedirectResponse
    {
        $this->authorize('view', $tarea);

        $tarea->load('entregas.user');

        return view('docente.tareas.show', compact('tarea'));
    }

    /**
     * Descarga el archivo de una entrega.
     */
    public function descargar(Entrega $entrega)
    {
        $this->authorize('download', $entrega);

        return $this->entregaService->descargarEntrega($entrega);
    }
}
