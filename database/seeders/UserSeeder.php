<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Carlos Mendoza',
            'email' => 'carlos.mendoza@uleam.edu.ec',
            'password' => Hash::make('12345678'),
            'rol_id' => 1,
        ]);

        // Docente 1 con DOS materias
        $claudia = User::create([
            'name' => 'Claudia Ramirez',
            'email' => 'claudia.ramirez@uleam.edu.ec',
            'password' => Hash::make('12345678'),
            'rol_id' => 2,
        ]);

        $materia1 = Materia::create([
            'codigo' => 'TEC-401',
            'nombre' => 'Tecnologias Emergentes',
            'descripcion' => 'Estudio de tecnologias modernas y su aplicacion en el desarrollo de software.',
            'docente_id' => $claudia->id,
        ]);

        $materia2 = Materia::create([
            'codigo' => 'IA-501',
            'nombre' => 'Inteligencia Artificial',
            'descripcion' => 'Fundamentos de IA, machine learning y redes neuronales.',
            'docente_id' => $claudia->id,
        ]);

        // Docente 2 con una materia
        $roberto = User::create([
            'name' => 'Roberto Silva',
            'email' => 'roberto.silva@uleam.edu.ec',
            'password' => Hash::make('12345678'),
            'rol_id' => 2,
        ]);

        $materia3 = Materia::create([
            'codigo' => 'BD-202',
            'nombre' => 'Base de Datos II',
            'descripcion' => 'Administracion avanzada de bases de datos relacionales y NoSQL.',
            'docente_id' => $roberto->id,
        ]);

        // 10 Estudiantes
        $estudiantes = collect([
            ['name' => 'Maria Torres', 'email' => 'maria.torres@uleam.edu.ec'],
            ['name' => 'Juan Perez', 'email' => 'juan.perez@uleam.edu.ec'],
            ['name' => 'Ana Garcia', 'email' => 'ana.garcia@uleam.edu.ec'],
            ['name' => 'Luis Martinez', 'email' => 'luis.martinez@uleam.edu.ec'],
            ['name' => 'Sofia Rodriguez', 'email' => 'sofia.rodriguez@uleam.edu.ec'],
            ['name' => 'Diego Fernandez', 'email' => 'diego.fernandez@uleam.edu.ec'],
            ['name' => 'Valentina Lopez', 'email' => 'valentina.lopez@uleam.edu.ec'],
            ['name' => 'Andres Morales', 'email' => 'andres.morales@uleam.edu.ec'],
            ['name' => 'Camila Sanchez', 'email' => 'camila.sanchez@uleam.edu.ec'],
            ['name' => 'Sebastian Herrera', 'email' => 'sebastian.herrera@uleam.edu.ec'],
        ])->map(function ($data) {
            return User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make('12345678'),
                'rol_id' => 3,
            ]);
        });

        // Inscribir estudiantes en las materias
        // Materia 1 (Tecnologias Emergentes): 8 estudiantes
        $materia1->estudiantes()->attach($estudiantes->take(8)->pluck('id'));
        
        // Materia 2 (Inteligencia Artificial): 6 estudiantes
        $materia2->estudiantes()->attach($estudiantes->take(6)->pluck('id'));
        
        // Materia 3 (Base de Datos II): 10 estudiantes (todos)
        $materia3->estudiantes()->attach($estudiantes->pluck('id'));
    }
}
