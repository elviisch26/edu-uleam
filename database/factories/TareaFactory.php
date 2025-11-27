<?php

namespace Database\Factories;

use App\Models\Tarea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tarea>
 */
class TareaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Tarea::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'titulo' => fake()->sentence(4),
            'descripcion' => fake()->paragraphs(3, true),
            'fecha_entrega' => fake()->dateTimeBetween('now', '+30 days'),
            'user_id' => User::factory(),
            'ruta_archivo_guia' => null,
            'nombre_archivo_guia' => null,
        ];
    }

    /**
     * Indica que la tarea está vencida.
     */
    public function vencida(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_entrega' => fake()->dateTimeBetween('-30 days', '-1 day'),
        ]);
    }

    /**
     * Indica que la tarea vence pronto (en 3 días o menos).
     */
    public function porVencer(): static
    {
        return $this->state(fn (array $attributes) => [
            'fecha_entrega' => fake()->dateTimeBetween('now', '+3 days'),
        ]);
    }

    /**
     * Indica que la tarea tiene un archivo guía.
     */
    public function conArchivoGuia(): static
    {
        return $this->state(fn (array $attributes) => [
            'ruta_archivo_guia' => 'guias_tareas/' . fake()->uuid() . '.pdf',
            'nombre_archivo_guia' => fake()->word() . '.pdf',
        ]);
    }
}
