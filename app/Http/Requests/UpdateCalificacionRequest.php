<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validar la actualización de calificaciones.
 */
class UpdateCalificacionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        $calificacion = $this->route('calificacion');
        
        // Solo el docente dueño de la tarea puede actualizar
        return $this->user()->id === $calificacion->entrega->tarea->user_id 
            || $this->user()->rol->nombre === 'admin';
    }

    /**
     * Obtiene las reglas de validación que aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'calificacion' => 'required|numeric|min:0|max:10',
            'retroalimentacion' => 'nullable|string|min:10|max:1000',
        ];
    }

    /**
     * Obtiene los mensajes de error personalizados para las reglas de validación.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'calificacion.required' => 'La calificación es obligatoria.',
            'calificacion.numeric' => 'La calificación debe ser un número.',
            'calificacion.min' => 'La calificación mínima es 0.',
            'calificacion.max' => 'La calificación máxima es 10.',
            'retroalimentacion.min' => 'La retroalimentación debe tener al menos 10 caracteres.',
            'retroalimentacion.max' => 'La retroalimentación no puede exceder 1000 caracteres.',
        ];
    }

    /**
     * Obtiene los atributos personalizados para los errores del validador.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'calificacion' => 'calificación',
            'retroalimentacion' => 'retroalimentación',
        ];
    }
}
