<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validar la actualización de tareas.
 */
class UpdateTareaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        $tarea = $this->route('tarea');
        
        return $this->user()->id === $tarea->user_id 
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
            'titulo' => 'required|string|max:255|min:5',
            'descripcion' => 'required|string|min:10',
            'fecha_entrega' => 'required|date',
            'archivo_guia' => [
                'nullable',
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png,zip',
                'max:5120', // 5MB
            ],
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
            'titulo.required' => 'El título de la tarea es obligatorio.',
            'titulo.min' => 'El título debe tener al menos 5 caracteres.',
            'titulo.max' => 'El título no puede exceder 255 caracteres.',
            'descripcion.required' => 'La descripción de la tarea es obligatoria.',
            'descripcion.min' => 'La descripción debe tener al menos 10 caracteres.',
            'fecha_entrega.required' => 'La fecha de entrega es obligatoria.',
            'fecha_entrega.date' => 'La fecha de entrega debe ser una fecha válida.',
            'archivo_guia.file' => 'El archivo guía debe ser un archivo válido.',
            'archivo_guia.mimes' => 'El archivo guía debe ser de tipo: pdf, doc, docx, jpg, jpeg, png o zip.',
            'archivo_guia.max' => 'El archivo guía no puede superar los 5MB.',
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
            'titulo' => 'título',
            'descripcion' => 'descripción',
            'fecha_entrega' => 'fecha de entrega',
            'archivo_guia' => 'archivo guía',
        ];
    }
}
