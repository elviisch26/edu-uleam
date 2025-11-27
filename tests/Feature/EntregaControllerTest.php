<?php

namespace Tests\Feature;

use App\Models\Entrega;
use App\Models\Rol;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class EntregaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $docente;
    protected User $estudiante;
    protected Tarea $tarea;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles base
        $rolDocente = Rol::create(['nombre' => 'docente']);
        $rolEstudiante = Rol::create(['nombre' => 'estudiante']);

        // Crear usuarios de prueba
        $this->docente = User::factory()->create(['rol_id' => $rolDocente->id]);
        $this->estudiante = User::factory()->create(['rol_id' => $rolEstudiante->id]);

        // Crear tarea de prueba
        $this->tarea = Tarea::factory()->create([
            'user_id' => $this->docente->id,
            'fecha_entrega' => now()->addDays(7),
        ]);
    }

    public function test_estudiante_puede_entregar_tarea(): void
    {
        Storage::fake('private');

        $response = $this->actingAs($this->estudiante)->post(
            route('estudiante.entregas.store', $this->tarea),
            [
                'archivo_entrega' => UploadedFile::fake()->create('tarea.pdf', 1024),
            ]
        );

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('entregas', [
            'user_id' => $this->estudiante->id,
            'tarea_id' => $this->tarea->id,
        ]);
    }

    public function test_estudiante_no_puede_entregar_tarea_dos_veces(): void
    {
        Storage::fake('private');

        // Primera entrega
        Entrega::factory()->create([
            'user_id' => $this->estudiante->id,
            'tarea_id' => $this->tarea->id,
        ]);

        // Intentar segunda entrega - la app redirige con error
        $response = $this->actingAs($this->estudiante)->post(
            route('estudiante.entregas.store', $this->tarea),
            [
                'archivo_entrega' => UploadedFile::fake()->create('tarea2.pdf', 1024),
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('error', 'Ya has entregado esta tarea.');
    }

    public function test_estudiante_no_puede_entregar_tarea_vencida(): void
    {
        Storage::fake('private');

        // Crear tarea vencida
        $tareaVencida = Tarea::factory()->vencida()->create([
            'user_id' => $this->docente->id,
        ]);

        $response = $this->actingAs($this->estudiante)->post(
            route('estudiante.entregas.store', $tareaVencida),
            [
                'archivo_entrega' => UploadedFile::fake()->create('tarea.pdf', 1024),
            ]
        );

        $response->assertRedirect();
        $response->assertSessionHas('error', 'La fecha de entrega ha expirado.');
    }

    public function test_docente_puede_descargar_entrega(): void
    {
        Storage::fake('private');

        // Crear archivo falso
        $archivo = UploadedFile::fake()->create('entrega.pdf', 1024);
        $rutaArchivo = $archivo->store('entregas', 'private');

        $entrega = Entrega::factory()->create([
            'user_id' => $this->estudiante->id,
            'tarea_id' => $this->tarea->id,
            'ruta_archivo' => $rutaArchivo,
        ]);

        $response = $this->actingAs($this->docente)->get(
            route('docente.entregas.descargar', $entrega)
        );

        $response->assertStatus(200);
    }

    public function test_docente_no_puede_descargar_entrega_de_otra_tarea(): void
    {
        Storage::fake('private');

        $otroDocente = User::factory()->create(['rol_id' => $this->docente->rol_id]);
        $otraTarea = Tarea::factory()->create(['user_id' => $otroDocente->id]);

        $entrega = Entrega::factory()->create([
            'user_id' => $this->estudiante->id,
            'tarea_id' => $otraTarea->id,
        ]);

        $response = $this->actingAs($this->docente)->get(
            route('docente.entregas.descargar', $entrega)
        );

        $response->assertStatus(403);
    }

    public function test_docente_no_puede_entregar_tarea(): void
    {
        Storage::fake('private');

        $response = $this->actingAs($this->docente)->post(
            route('estudiante.entregas.store', $this->tarea),
            [
                'archivo_entrega' => UploadedFile::fake()->create('tarea.pdf', 1024),
            ]
        );

        $response->assertStatus(403);
    }
}
