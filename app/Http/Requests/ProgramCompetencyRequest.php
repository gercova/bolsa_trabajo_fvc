<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProgramCompetencyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'study_program_id' => 'required|exists:study_programs,id',
            'title'            => 'required|string|max:255',
            'description'      => 'required|string',
            'icon'             => 'nullable|string|max:100',
            'order'            => 'nullable|integer|min:0',
            'is_active'        => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'study_program_id.required' => 'Debe seleccionar un programa de estudio.',
            'study_program_id.exists'   => 'El programa de estudio seleccionado no es válido.',
            'title.required'            => 'El título de la competencia es obligatorio.',
            'title.string'              => 'El título debe ser un texto válido.',
            'title.max'                 => 'El título no puede exceder los 255 caracteres.',
            'description.required'      => 'La descripción de la competencia es obligatoria.',
            'description.string'        => 'La descripción debe ser un texto válido.',
        ];
    }
}
