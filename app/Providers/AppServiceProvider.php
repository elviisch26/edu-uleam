<?php

namespace App\Providers;

use App\Models\Calificacion;
use App\Models\Entrega;
use App\Models\Tarea;
use App\Policies\CalificacionPolicy;
use App\Policies\EntregaPolicy;
use App\Policies\TareaPolicy;
use App\Services\CalificacionService;
use App\Services\EntregaService;
use App\Services\TareaService;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Las Policies del modelo.
     *
     * @var array<class-string, class-string>
     */
    protected array $policies = [
        Tarea::class => TareaPolicy::class,
        Entrega::class => EntregaPolicy::class,
        Calificacion::class => CalificacionPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Registrar servicios como singletons
        $this->app->singleton(TareaService::class, function ($app) {
            return new TareaService();
        });

        $this->app->singleton(EntregaService::class, function ($app) {
            return new EntregaService();
        });

        $this->app->singleton(CalificacionService::class, function ($app) {
            return new CalificacionService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar Policies
        foreach ($this->policies as $model => $policy) {
            Gate::policy($model, $policy);
        }

        // Logging de eventos de modelos
        Tarea::created(function ($tarea) {
            Log::info('Tarea creada', [
                'tarea_id' => $tarea->id,
                'docente_id' => $tarea->user_id,
                'titulo' => $tarea->titulo,
            ]);
        });

        Entrega::created(function ($entrega) {
            Log::info('Entrega realizada', [
                'entrega_id' => $entrega->id,
                'estudiante_id' => $entrega->user_id,
                'tarea_id' => $entrega->tarea_id,
            ]);
        });

        Calificacion::created(function ($calificacion) {
            Log::info('Calificación asignada', [
                'calificacion_id' => $calificacion->id,
                'entrega_id' => $calificacion->entrega_id,
                'nota' => $calificacion->calificacion,
            ]);
        });
    }
}
