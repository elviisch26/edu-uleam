<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llama al seeder de roles que acabas de crear
        $this->call([
            RolSeeder::class,
            // Aquí puedes añadir otros seeders en el futuro
        ]);
    }
}