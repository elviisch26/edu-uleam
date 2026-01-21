<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = ['admin', 'docente', 'estudiante'];

        foreach ($roles as $rol) {
            DB::table('roles')->updateOrInsert(
                ['nombre' => $rol],
                ['nombre' => $rol]
            );
        }
    }
}