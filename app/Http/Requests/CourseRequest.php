<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $courseId = $this->route('course')?->id ?? $this->course;

        return [
            'code' => [
                'required',
                'string',
                'max:50',
                Rule::unique('courses', 'code')->ignore($courseId),
            ],
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
            'modality' => [
                'required',
                'string',
                Rule::in(['Presencial', 'Semipresencial', 'Virtual']),
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
            'code.required' => 'El código del curso es obligatorio.',
            'code.unique'   => 'Este código de curso ya está registrado.',
            'code.max'      => 'El código no debe superar los 50 caracteres.',
            'name.required' => 'El nombre del curso es obligatorio.',
            'name.max'      => 'El nombre no debe superar los 255 caracteres.',
            'modality.required' => 'Debe seleccionar una modalidad.',
            'modality.in'       => 'La modalidad seleccionada no es válida.',
        ];
    }

    public function attributes(): array
    {
        return [
            'code'        => 'código del curso',
            'name'        => 'nombre del curso',
            'description' => 'descripción',
            'modality'    => 'modalidad',
            'is_active'   => 'estado activo',
        ];
    }
}
