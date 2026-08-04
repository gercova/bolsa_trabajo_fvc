<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramRequirementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'study_program_id' => 'required|exists:study_programs,id',
            'description'      => 'required|string',
            'order'            => 'nullable|integer|min:0',
            'is_active'        => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'study_program_id.required' => 'Debe seleccionar un programa de estudio.',
            'study_program_id.exists'   => 'El programa de estudio seleccionado no es válido.',
            'description.required'      => 'La descripción del requisito es obligatoria.',
            'description.string'        => 'La descripción debe ser un texto válido.',
        ];
    }
}
