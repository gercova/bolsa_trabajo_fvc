<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudyProgramValidate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $program = $this->route('program');
        $programId = is_object($program) ? $program->id : $program;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('study_programs', 'name')->ignore($programId),
            ],
            'logo_path' => 'nullable|image|max:2048',
            'description' => 'required|string',
            'details' => 'required|string',
            'is_active' => 'boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del programa es obligatorio.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'name.max' => 'El nombre no debe tener más de 255 caracteres.',
            'name.unique' => 'Ya existe un programa de estudio con este nombre.',
            'logo_path.image' => 'El archivo debe ser una imagen de tipo logo válida.',
            'logo_path.max' => 'La imagen de logo no debe pesar más de 2MB.',
            'description.required' => 'La descripción es obligatoria.',
            'description.string' => 'La descripción debe ser una cadena de texto.',
            'details.required' => 'Los detalles del programa son obligatorios.',
            'details.string' => 'Los detalles del programa deben ser una cadena de texto.',
        ];
    }
}
