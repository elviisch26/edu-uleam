<?php

namespace Tests\Unit;

use App\Models\Tarea;
use App\Models\User;
use App\Models\Rol;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TareaTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Crear roles base
        Rol::create(['nombre' => 'admin']);
        Rol::create(['nombre' => 'docente']);
        Rol::create(['nombre' => 'estudiante']);
    }

    public function test_tarea_pertenece_a_docente(): void
    {
        $rol = Rol::where('nombre', 'docente')->first();
        $docente = User::factory()->create(['rol_id' => $rol->id]);
        $tarea = Tarea::factory()->create(['user_id' => $docente->id]);

        $this->assertInstanceOf(User::class, $tarea->docente);
        $this->assertEquals($docente->id, $tarea->docente->id);
    }

    public function test_tarea_esta_vencida(): void
    {
        $rol = Rol::where('nombre', 'docente')->first();
        $docente = User::factory()->create(['rol_id' => $rol->id]);
        
        $tarea = Tarea::factory()->vencida()->create(['user_id' => $docente->id]);

        $this->assertTrue($tarea->esta_vencida);
    }

    public function test_tarea_no_esta_vencida(): void
    {
        $rol = Rol::where('nombre', 'docente')->first();
        $docente = User::factory()->create(['rol_id' => $rol->id]);
        
        $tarea = Tarea::factory()->create([
            'user_id' => $docente->id,
            'fecha_entrega' => now()->addDays(10),
        ]);

        $this->assertFalse($tarea->esta_vencida);
    }

    public function test_tarea_tiene_archivo_guia(): void
    {
        $rol = Rol::where('nombre', 'docente')->first();
        $docente = User::factory()->create(['rol_id' => $rol->id]);
        
        $tarea = Tarea::factory()->conArchivoGuia()->create(['user_id' => $docente->id]);

        $this->assertTrue($tarea->tiene_archivo_guia);
    }

    public function test_scope_tareas_activas(): void
    {
        $rol = Rol::where('nombre', 'docente')->first();
        $docente = User::factory()->create(['rol_id' => $rol->id]);
        
        // Crear tareas activas y vencidas
        Tarea::factory()->count(3)->create([
            'user_id' => $docente->id,
            'fecha_entrega' => now()->addDays(10),
        ]);
        
        Tarea::factory()->count(2)->vencida()->create([
            'user_id' => $docente->id,
        ]);

        $tareasActivas = Tarea::activas()->count();

        $this->assertEquals(3, $tareasActivas);
    }

    public function test_scope_tareas_vencidas(): void
    {
        $rol = Rol::where('nombre', 'docente')->first();
        $docente = User::factory()->create(['rol_id' => $rol->id]);
        
        // Crear tareas activas y vencidas
        Tarea::factory()->count(3)->create([
            'user_id' => $docente->id,
            'fecha_entrega' => now()->addDays(10),
        ]);
        
        Tarea::factory()->count(2)->vencida()->create([
            'user_id' => $docente->id,
        ]);

        $tareasVencidas = Tarea::vencidas()->count();

        $this->assertEquals(2, $tareasVencidas);
    }
}
