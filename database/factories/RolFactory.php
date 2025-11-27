<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Rol>
 */
class RolFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Rol::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nombre' => fake()->randomElement(['admin', 'docente', 'estudiante']),
        ];
    }

    /**
     * Indica que el rol es de administrador.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'admin',
        ]);
    }

    /**
     * Indica que el rol es de docente.
     */
    public function docente(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'docente',
        ]);
    }

    /**
     * Indica que el rol es de estudiante.
     */
    public function estudiante(): static
    {
        return $this->state(fn (array $attributes) => [
            'nombre' => 'estudiante',
        ]);
    }
}
