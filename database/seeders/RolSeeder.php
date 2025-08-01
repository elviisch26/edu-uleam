<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB; // ¡Importante importar DB!

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserta los roles básicos de tu aplicación
        DB::table('roles')->insert([
            ['nombre' => 'admin'],
            ['nombre' => 'docente'],
            ['nombre' => 'estudiante'],
        ]);
    }
}