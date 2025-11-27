<?php

namespace App\Enums;

/**
 * Enum para los roles de usuario.
 */
enum RolEnum: string
{
    case ADMIN = 'admin';
    case DOCENTE = 'docente';
    case ESTUDIANTE = 'estudiante';

    /**
     * Obtiene la etiqueta legible del rol.
     */
    public function label(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador',
            self::DOCENTE => 'Docente',
            self::ESTUDIANTE => 'Estudiante',
        };
    }

    /**
     * Obtiene la descripción del rol.
     */
    public function descripcion(): string
    {
        return match ($this) {
            self::ADMIN => 'Acceso completo al sistema',
            self::DOCENTE => 'Puede crear tareas, ver entregas y calificar',
            self::ESTUDIANTE => 'Puede ver tareas, realizar entregas y consultar calificaciones',
        };
    }

    /**
     * Verifica si el rol puede crear tareas.
     */
    public function puedeCrearTareas(): bool
    {
        return in_array($this, [self::ADMIN, self::DOCENTE]);
    }

    /**
     * Verifica si el rol puede calificar.
     */
    public function puedeCalificar(): bool
    {
        return in_array($this, [self::ADMIN, self::DOCENTE]);
    }

    /**
     * Verifica si el rol puede realizar entregas.
     */
    public function puedeRealizarEntregas(): bool
    {
        return $this === self::ESTUDIANTE;
    }

    /**
     * Obtiene todos los roles disponibles.
     *
     * @return array<string, string>
     */
    public static function options(): array
    {
        return array_combine(
            array_column(self::cases(), 'value'),
            array_map(fn($case) => $case->label(), self::cases())
        );
    }
}
