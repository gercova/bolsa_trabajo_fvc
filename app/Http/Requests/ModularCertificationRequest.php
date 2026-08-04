<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModularCertificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'program_id' => 'required|exists:study_programs,id',
            'module'     => 'required|string|max:255',
            'is_active'  => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'program_id.required' => 'Debe seleccionar un programa de estudio.',
            'program_id.exists'   => 'El programa de estudio seleccionado no es válido.',
            'module.required'     => 'El nombre del módulo de certificación es obligatorio.',
            'module.string'       => 'El nombre del módulo debe ser un texto válido.',
            'module.max'          => 'El nombre del módulo no puede exceder los 255 caracteres.',
        ];
    }
}
