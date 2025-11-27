<?php

namespace Database\Factories;

use App\Models\Entrega;
use App\Models\Tarea;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Entrega>
 */
class EntregaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Entrega::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'tarea_id' => Tarea::factory(),
            'ruta_archivo' => 'entregas/' . fake()->uuid() . '.pdf',
        ];
    }

    /**
     * Indica que la entrega fue tardía (después de la fecha límite).
     */
    public function tardia(): static
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => now()->addDays(5),
            ];
        });
    }
}
