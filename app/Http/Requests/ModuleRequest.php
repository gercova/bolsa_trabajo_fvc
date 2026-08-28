<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_id' => [
                'required',
                'exists:courses,id',
            ],
            'name' => [
                'required',
                'string',
                'max:255',
            ],
            'credits' => [
                'nullable',
                'string',
                'max:50',
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
            'course_id.required' => 'Debe seleccionar un curso para el módulo.',
            'course_id.exists'   => 'El curso seleccionado no existe.',
            'name.required'      => 'El nombre del módulo es obligatorio.',
            'name.max'           => 'El nombre no debe superar los 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'course_id' => 'curso',
            'name'      => 'nombre del módulo',
            'credits'   => 'créditos',
            'is_active' => 'estado activo',
        ];
    }
}
