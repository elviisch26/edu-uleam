<?php

namespace Tests\Feature;

use App\Models\Rol;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class TareaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $docente;
    protected User $estudiante;

    protected function setUp(): void
    {
        parent::setUp();

        // Crear roles base
        $rolAdmin = Rol::create(['nombre' => 'admin']);
        $rolDocente = Rol::create(['nombre' => 'docente']);
        $rolEstudiante = Rol::create(['nombre' => 'estudiante']);

        // Crear usuarios de prueba
        $this->docente = User::factory()->create(['rol_id' => $rolDocente->id]);
        $this->estudiante = User::factory()->create(['rol_id' => $rolEstudiante->id]);
    }

    public function test_docente_puede_ver_listado_de_tareas(): void
    {
        // Crear algunas tareas
        Tarea::factory()->count(3)->create(['user_id' => $this->docente->id]);

        $response = $this->actingAs($this->docente)->get(route('docente.tareas.index'));

        $response->assertStatus(200);
        $response->assertViewIs('docente.tareas.index');
        $response->assertViewHas('tareas');
    }

    public function test_docente_puede_ver_formulario_crear_tarea(): void
    {
        $response = $this->actingAs($this->docente)->get(route('docente.tareas.create'));

        $response->assertStatus(200);
        $response->assertViewIs('docente.tareas.create');
    }

    public function test_docente_puede_crear_tarea(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->docente)->post(route('docente.tareas.store'), [
            'titulo' => 'Tarea de Prueba Completa',
            'descripcion' => 'Esta es una descripción de prueba con suficientes caracteres.',
            'fecha_entrega' => now()->addDays(7)->format('Y-m-d'),
            'archivo_guia' => UploadedFile::fake()->create('guia.pdf', 1024),
        ]);

        $response->assertRedirect(route('docente.tareas.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tareas', [
            'titulo' => 'Tarea de Prueba Completa',
            'user_id' => $this->docente->id,
        ]);
    }

    public function test_docente_no_puede_crear_tarea_con_titulo_corto(): void
    {
        $response = $this->actingAs($this->docente)->post(route('docente.tareas.store'), [
            'titulo' => 'Abc', // Muy corto
            'descripcion' => 'Esta es una descripción de prueba con suficientes caracteres.',
            'fecha_entrega' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('titulo');
    }

    public function test_docente_puede_actualizar_tarea(): void
    {
        $tarea = Tarea::factory()->create(['user_id' => $this->docente->id]);

        $response = $this->actingAs($this->docente)->put(route('docente.tareas.update', $tarea), [
            'titulo' => 'Título Actualizado de Tarea',
            'descripcion' => 'Esta es una descripción actualizada con suficientes caracteres.',
            'fecha_entrega' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('docente.tareas.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tareas', [
            'id' => $tarea->id,
            'titulo' => 'Título Actualizado de Tarea',
        ]);
    }

    public function test_docente_puede_eliminar_tarea(): void
    {
        $tarea = Tarea::factory()->create(['user_id' => $this->docente->id]);

        $response = $this->actingAs($this->docente)->delete(route('docente.tareas.destroy', $tarea));

        $response->assertRedirect(route('docente.tareas.index'));
        $response->assertSessionHas('success');

        // Verificar soft delete
        $this->assertSoftDeleted('tareas', ['id' => $tarea->id]);
    }

    public function test_docente_no_puede_editar_tarea_de_otro_docente(): void
    {
        $otroDocente = User::factory()->create(['rol_id' => $this->docente->rol_id]);
        $tarea = Tarea::factory()->create(['user_id' => $otroDocente->id]);

        $response = $this->actingAs($this->docente)->get(route('docente.tareas.edit', $tarea));

        $response->assertStatus(403);
    }

    public function test_estudiante_no_puede_crear_tarea(): void
    {
        $response = $this->actingAs($this->estudiante)->post(route('docente.tareas.store'), [
            'titulo' => 'Tarea de Estudiante',
            'descripcion' => 'Esta es una descripción de prueba.',
            'fecha_entrega' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertStatus(403);
    }

    public function test_estudiante_puede_ver_detalle_de_tarea(): void
    {
        $tarea = Tarea::factory()->create(['user_id' => $this->docente->id]);

        $response = $this->actingAs($this->estudiante)->get(route('estudiante.tareas.show', $tarea));

        $response->assertStatus(200);
        $response->assertViewIs('estudiante.tareas.show');
    }
}
