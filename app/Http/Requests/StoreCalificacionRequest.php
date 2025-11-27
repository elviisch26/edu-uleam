<?php

namespace App\Http\Requests;

use App\Models\Entrega;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validar la creación de calificaciones.
 */
class StoreCalificacionRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // Solo docentes pueden calificar
        if ($this->user()->rol->nombre !== 'docente') {
            return false;
        }

        $entrega = Entrega::find($this->input('entrega_id'));
        
        if (!$entrega) {
            return false;
        }

        // Solo puede calificar entregas de sus propias tareas
        if ($this->user()->id !== $entrega->tarea->user_id) {
            return false;
        }

        // Verificar que no exista ya una calificación
        if ($entrega->calificacion()->exists()) {
            return false;
        }

        return true;
    }

    /**
     * Obtiene las reglas de validación que aplican a la solicitud.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'entrega_id' => 'required|exists:entregas,id',
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
            'entrega_id.required' => 'La entrega es obligatoria.',
            'entrega_id.exists' => 'La entrega seleccionada no existe.',
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
            'entrega_id' => 'entrega',
            'calificacion' => 'calificación',
            'retroalimentacion' => 'retroalimentación',
        ];
    }

    /**
     * Maneja una autorización fallida.
     */
    protected function failedAuthorization()
    {
        $entrega = Entrega::find($this->input('entrega_id'));
        
        if ($entrega && $entrega->calificacion()->exists()) {
            throw new \Illuminate\Validation\ValidationException(
                validator: \Illuminate\Support\Facades\Validator::make([], []),
                response: redirect()->back()->with('error', 'Esta entrega ya ha sido calificada.')
            );
        }

        parent::failedAuthorization();
    }
}
