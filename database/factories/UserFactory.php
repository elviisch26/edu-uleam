<?php

namespace Database\Factories;

use App\Models\Rol;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Obtiene o crea un rol por nombre.
     */
    protected function getOrCreateRol(string $nombre): int
    {
        $rol = Rol::where('nombre', $nombre)->first();
        
        if (!$rol) {
            $rol = Rol::create(['nombre' => $nombre]);
        }
        
        return $rol->id;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'rol_id' => fn() => $this->getOrCreateRol('estudiante'),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indica que el usuario es un docente.
     */
    public function docente(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol_id' => $this->getOrCreateRol('docente'),
        ]);
    }

    /**
     * Indica que el usuario es un estudiante.
     */
    public function estudiante(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol_id' => $this->getOrCreateRol('estudiante'),
        ]);
    }

    /**
     * Indica que el usuario es un administrador.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'rol_id' => $this->getOrCreateRol('admin'),
        ]);
    }
}
