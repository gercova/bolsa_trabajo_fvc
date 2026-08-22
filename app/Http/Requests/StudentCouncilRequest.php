<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentCouncilRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'user_id'          => 'required|exists:users,id',
            'study_program_id' => 'nullable|exists:study_programs,id',
            'name'             => 'nullable|string|max:255',
            'position'         => 'required|string|max:255',
            'academic_period'  => 'required|string|max:50',
            'is_active'        => 'nullable|boolean',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user_id.required'          => 'Debe seleccionar un integrante (usuario).',
            'user_id.exists'            => 'El usuario seleccionado no existe en el sistema.',
            'study_program_id.exists'   => 'El programa de estudios seleccionado no existe.',
            'position.required'         => 'El cargo o posición es obligatorio.',
            'position.string'           => 'El cargo debe ser una cadena de texto válida.',
            'position.max'              => 'El cargo no puede exceder los 255 caracteres.',
            'academic_period.required'  => 'El período académico es obligatorio (ej. 2026-2027).',
            'academic_period.string'    => 'El período académico debe ser un texto válido.',
            'academic_period.max'       => 'El período académico no debe exceder los 50 caracteres.',
        ];
    }
}
