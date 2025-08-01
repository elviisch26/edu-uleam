<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskRequest extends FormRequest {
    public function authorize(): bool {
        return true; // Permite a cualquier usuario autenticado intentar validar
    }

    public function rules(): array {
        return [
            'titulo' => 'required|string|max:255',
            'descripcion' => 'required|string',
            'fecha_entrega' => 'required|date',
            'adjunto' => 'nullable|file|mimes:pdf,doc,docx,zip|max:2048' // PDF, Word o ZIP, máx 2MB
        ];
    }
}
