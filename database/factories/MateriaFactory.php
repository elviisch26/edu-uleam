<?php

namespace Database\Factories;

use App\Models\Materia;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Materia>
 */
class MateriaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Materia::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $prefijos = ['MAT', 'FIS', 'PRG', 'BD', 'TEC', 'DWB'];
        $codigo = fake()->randomElement($prefijos) . '-' . fake()->numberBetween(100, 499);

        return [
            'codigo' => $codigo,
            'nombre' => fake()->unique()->words(3, true),
            'descripcion' => fake()->paragraph(),
            'docente_id' => function () {
                $rolDocente = Rol::firstOrCreate(['nombre' => 'docente']);
                return User::factory()->create(['rol_id' => $rolDocente->id])->id;
            },
        ];
    }

    /**
     * Asigna un docente específico a la materia.
     */
    public function paraDocente(User $docente): static
    {
        return $this->state(fn (array $attributes) => [
            'docente_id' => $docente->id,
        ]);
    }
}
