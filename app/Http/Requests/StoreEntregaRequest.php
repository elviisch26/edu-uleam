<?php

namespace App\Http\Requests;

use App\Models\Tarea;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Form Request para validar la creación de entregas.
 */
class StoreEntregaRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado para hacer esta solicitud.
     */
    public function authorize(): bool
    {
        // Solo estudiantes pueden crear entregas
        if ($this->user()->rol->nombre !== 'estudiante') {
            return false;
        }

        $tarea = $this->route('tarea');

        // Verificar que no haya entregado ya esta tarea
        $yaEntrego = $this->user()->entregas()->where('tarea_id', $tarea->id)->exists();
        if ($yaEntrego) {
            return false;
        }

        // Verificar que la fecha de entrega no haya pasado
        if (now()->gt($tarea->fecha_entrega)) {
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
            'archivo_entrega' => [
                'required',
                'file',
                'mimes:pdf,doc,docx,zip,rar',
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
            'archivo_entrega.required' => 'Debes adjuntar un archivo para entregar la tarea.',
            'archivo_entrega.file' => 'El archivo de entrega debe ser un archivo válido.',
            'archivo_entrega.mimes' => 'El archivo debe ser de tipo: pdf, doc, docx, zip o rar.',
            'archivo_entrega.max' => 'El archivo no puede superar los 5MB.',
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
            'archivo_entrega' => 'archivo de entrega',
        ];
    }

    /**
     * Maneja una autorización fallida.
     *
     * @return void
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    protected function failedAuthorization()
    {
        $tarea = $this->route('tarea');
        
        // Verificar si ya entregó
        if ($this->user()->entregas()->where('tarea_id', $tarea->id)->exists()) {
            throw new \Illuminate\Validation\ValidationException(
                validator: \Illuminate\Support\Facades\Validator::make([], []),
                response: redirect()->back()->with('error', 'Ya has entregado esta tarea.')
            );
        }

        // Verificar si la fecha expiró
        if (now()->gt($tarea->fecha_entrega)) {
            throw new \Illuminate\Validation\ValidationException(
                validator: \Illuminate\Support\Facades\Validator::make([], []),
                response: redirect()->back()->with('error', 'La fecha de entrega ha expirado.')
            );
        }

        parent::failedAuthorization();
    }
}
