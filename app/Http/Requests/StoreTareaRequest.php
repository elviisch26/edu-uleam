<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validar la creación de tareas.
 */
class StoreTareaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        return $this->user()->rol->nombre === 'docente' 
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
            'materia_id' => 'required|exists:materias,id',
            'titulo' => 'required|string|max:255|min:5',
            'descripcion' => 'required|string|min:10',
            'fecha_entrega' => 'required|date|after:now',
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
            'materia_id.required' => 'Debe seleccionar una materia.',
            'materia_id.exists' => 'La materia seleccionada no es valida.',
            'titulo.required' => 'El titulo de la tarea es obligatorio.',
            'titulo.min' => 'El titulo debe tener al menos 5 caracteres.',
            'titulo.max' => 'El titulo no puede exceder 255 caracteres.',
            'descripcion.required' => 'La descripcion de la tarea es obligatoria.',
            'descripcion.min' => 'La descripcion debe tener al menos 10 caracteres.',
            'fecha_entrega.required' => 'La fecha de entrega es obligatoria.',
            'fecha_entrega.date' => 'La fecha de entrega debe ser una fecha valida.',
            'fecha_entrega.after' => 'La fecha de entrega debe ser una fecha futura.',
            'archivo_guia.file' => 'El archivo guia debe ser un archivo valido.',
            'archivo_guia.mimes' => 'El archivo guia debe ser de tipo: pdf, doc, docx, jpg, jpeg, png o zip.',
            'archivo_guia.max' => 'El archivo guia no puede superar los 5MB.',
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
