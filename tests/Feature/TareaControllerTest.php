<?php

namespace Tests\Feature;

use App\Models\Materia;
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
    protected Materia $materia;

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

        // Crear materia para el docente
        $this->materia = Materia::create([
            'codigo' => 'TEST-101',
            'nombre' => 'Materia de Prueba',
            'descripcion' => 'Descripcion de la materia de prueba',
            'docente_id' => $this->docente->id,
        ]);
    }

    public function test_docente_puede_ver_listado_de_materias(): void
    {
        // Crear algunas tareas para la materia
        Tarea::factory()->count(3)->create([
            'user_id' => $this->docente->id,
            'materia_id' => $this->materia->id,
        ]);

        $response = $this->actingAs($this->docente)->get(route('docente.materias.index'));

        $response->assertStatus(200);
        $response->assertViewIs('docente.materias.index');
        $response->assertViewHas('materias');
    }

    public function test_docente_puede_ver_formulario_crear_tarea_en_materia(): void
    {
        $response = $this->actingAs($this->docente)->get(route('docente.materias.tareas.create', $this->materia));

        $response->assertStatus(200);
        $response->assertViewIs('docente.materias.tareas.create');
    }

    public function test_docente_puede_crear_tarea_en_materia(): void
    {
        Storage::fake('public');

        $response = $this->actingAs($this->docente)->post(route('docente.materias.tareas.store', $this->materia), [
            'titulo' => 'Tarea de Prueba Completa',
            'descripcion' => 'Esta es una descripcion de prueba con suficientes caracteres.',
            'fecha_entrega' => now()->addDays(7)->format('Y-m-d'),
            'archivo_guia' => UploadedFile::fake()->create('guia.pdf', 1024),
        ]);

        $response->assertRedirect(route('docente.materias.show', $this->materia));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tareas', [
            'titulo' => 'Tarea de Prueba Completa',
            'user_id' => $this->docente->id,
            'materia_id' => $this->materia->id,
        ]);
    }

    public function test_docente_no_puede_crear_tarea_con_titulo_corto(): void
    {
        $response = $this->actingAs($this->docente)->post(route('docente.materias.tareas.store', $this->materia), [
            'titulo' => 'Abc', // Muy corto
            'descripcion' => 'Esta es una descripcion de prueba con suficientes caracteres.',
            'fecha_entrega' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertSessionHasErrors('titulo');
    }

    public function test_docente_puede_actualizar_tarea(): void
    {
        $tarea = Tarea::factory()->create([
            'user_id' => $this->docente->id,
            'materia_id' => $this->materia->id,
        ]);

        $response = $this->actingAs($this->docente)->put(route('docente.tareas.update', $tarea), [
            'titulo' => 'Titulo Actualizado de Tarea',
            'descripcion' => 'Esta es una descripcion actualizada con suficientes caracteres.',
            'fecha_entrega' => now()->addDays(14)->format('Y-m-d'),
        ]);

        $response->assertRedirect(route('docente.materias.show', $this->materia));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('tareas', [
            'id' => $tarea->id,
            'titulo' => 'Titulo Actualizado de Tarea',
        ]);
    }

    public function test_docente_puede_eliminar_tarea(): void
    {
        $tarea = Tarea::factory()->create([
            'user_id' => $this->docente->id,
            'materia_id' => $this->materia->id,
        ]);

        $response = $this->actingAs($this->docente)->delete(route('docente.tareas.destroy', $tarea));

        $response->assertRedirect(route('docente.materias.show', $this->materia));
        $response->assertSessionHas('success');

        // Verificar soft delete
        $this->assertSoftDeleted('tareas', ['id' => $tarea->id]);
    }

    public function test_docente_no_puede_editar_tarea_de_otro_docente(): void
    {
        $otroDocente = User::factory()->create(['rol_id' => $this->docente->rol_id]);
        $otraMateria = Materia::create([
            'codigo' => 'TEST-102',
            'nombre' => 'Otra Materia',
            'descripcion' => 'Descripcion de otra materia',
            'docente_id' => $otroDocente->id,
        ]);
        $tarea = Tarea::factory()->create([
            'user_id' => $otroDocente->id,
            'materia_id' => $otraMateria->id,
        ]);

        $response = $this->actingAs($this->docente)->get(route('docente.tareas.edit', $tarea));

        $response->assertStatus(403);
    }

    public function test_estudiante_no_puede_crear_tarea(): void
    {
        $response = $this->actingAs($this->estudiante)->post(route('docente.materias.tareas.store', $this->materia), [
            'titulo' => 'Tarea de Estudiante',
            'descripcion' => 'Esta es una descripcion de prueba con suficientes caracteres.',
            'fecha_entrega' => now()->addDays(7)->format('Y-m-d'),
        ]);

        $response->assertStatus(403);
    }

    public function test_estudiante_puede_ver_detalle_de_tarea(): void
    {
        // Inscribir estudiante en la materia
        $this->estudiante->materiasInscritas()->attach($this->materia->id);

        $tarea = Tarea::factory()->create([
            'user_id' => $this->docente->id,
            'materia_id' => $this->materia->id,
        ]);

        $response = $this->actingAs($this->estudiante)->get(route('estudiante.tareas.show', $tarea));

        $response->assertStatus(200);
        $response->assertViewIs('estudiante.tareas.show');
    }
}
