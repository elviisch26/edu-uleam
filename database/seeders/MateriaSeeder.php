<?php

namespace Database\Seeders;

use App\Models\Materia;
use App\Models\User;
use Illuminate\Database\Seeder;

class MateriaSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener docentes
        $claudia = User::where('email', 'claudia.ramirez@uleam.edu.ec')->first();
        $roberto = User::where('email', 'roberto.silva@uleam.edu.ec')->first();

        // Materias de Claudia Ramirez
        $tecnologiasEmergentes = Materia::create([
            'nombre' => 'Tecnologias Emergentes',
            'codigo' => 'TEC-401',
            'descripcion' => 'Estudio de las tecnologias emergentes y su impacto en el desarrollo de software moderno.',
            'docente_id' => $claudia->id,
        ]);

        $desarrolloWeb = Materia::create([
            'nombre' => 'Desarrollo Web Avanzado',
            'codigo' => 'DWA-302',
            'descripcion' => 'Desarrollo de aplicaciones web con frameworks modernos como Laravel y Vue.js.',
            'docente_id' => $claudia->id,
        ]);

        // Materias de Roberto Silva
        $baseDatos = Materia::create([
            'nombre' => 'Base de Datos II',
            'codigo' => 'BD-202',
            'descripcion' => 'Diseño avanzado de bases de datos, optimizacion y administracion.',
            'docente_id' => $roberto->id,
        ]);

        $programacion = Materia::create([
            'nombre' => 'Programacion Orientada a Objetos',
            'codigo' => 'POO-201',
            'descripcion' => 'Fundamentos y aplicacion de la programacion orientada a objetos.',
            'docente_id' => $roberto->id,
        ]);

        // Obtener estudiantes
        $estudiantes = User::whereHas('rol', function ($query) {
            $query->where('nombre', 'estudiante');
        })->get();

        // Inscribir estudiantes en materias
        foreach ($estudiantes as $estudiante) {
            // Todos los estudiantes en Tecnologias Emergentes
            $tecnologiasEmergentes->estudiantes()->attach($estudiante->id);
            
            // Maria y Juan en Desarrollo Web
            if (in_array($estudiante->email, ['maria.torres@uleam.edu.ec', 'juan.perez@uleam.edu.ec'])) {
                $desarrolloWeb->estudiantes()->attach($estudiante->id);
            }
            
            // Juan y Ana en Base de Datos
            if (in_array($estudiante->email, ['juan.perez@uleam.edu.ec', 'ana.garcia@uleam.edu.ec'])) {
                $baseDatos->estudiantes()->attach($estudiante->id);
            }
            
            // Todos en POO
            $programacion->estudiantes()->attach($estudiante->id);
        }
    }
}
