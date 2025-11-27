<?php

namespace Database\Factories;

use App\Models\Calificacion;
use App\Models\Entrega;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Calificacion>
 */
class CalificacionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Calificacion::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'entrega_id' => Entrega::factory(),
            'calificacion' => fake()->randomFloat(2, 0, 10),
            'retroalimentacion' => fake()->paragraph(),
        ];
    }

    /**
     * Indica que la calificación es aprobatoria (>= 7).
     */
    public function aprobada(): static
    {
        return $this->state(fn (array $attributes) => [
            'calificacion' => fake()->randomFloat(2, 7, 10),
        ]);
    }

    /**
     * Indica que la calificación es reprobatoria (< 7).
     */
    public function reprobada(): static
    {
        return $this->state(fn (array $attributes) => [
            'calificacion' => fake()->randomFloat(2, 0, 6.99),
        ]);
    }

    /**
     * Indica que la calificación tiene retroalimentación.
     */
    public function conRetroalimentacion(): static
    {
        return $this->state(fn (array $attributes) => [
            'retroalimentacion' => fake()->paragraphs(2, true),
        ]);
    }

    /**
     * Indica que la calificación no tiene retroalimentación.
     */
    public function sinRetroalimentacion(): static
    {
        return $this->state(fn (array $attributes) => [
            'retroalimentacion' => null,
        ]);
    }
}
