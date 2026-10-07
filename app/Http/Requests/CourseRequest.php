<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000',
            ],
            'certificate_type' => [
                'nullable',
                'string',
                'in:capacitacion,modular',
            ],
            'study_program_id' => [
                'nullable',
                'exists:study_programs,id',
            ],
            'event_name' => [
                'nullable',
                'string',
                'max:255',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del curso es obligatorio.',
            'name.max' => 'El nombre no debe superar los 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'nombre del curso',
            'description' => 'descripción',
            'is_active' => 'estado activo',
        ];
    }
}
