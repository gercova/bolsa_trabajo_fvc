<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ItineraryRequest extends FormRequest
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
            'module_id' => [
                'required',
                'exists:modules,id',
            ],
            'name' => [
                'required',
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
            'course_id.required' => 'Debe seleccionar un curso.',
            'course_id.exists'   => 'El curso seleccionado no existe.',
            'module_id.required' => 'Debe seleccionar un módulo.',
            'module_id.exists'   => 'El módulo seleccionado no existe.',
            'name.required'      => 'El nombre del itinerario es obligatorio.',
            'name.max'           => 'El nombre no debe superar los 255 caracteres.',
        ];
    }

    public function attributes(): array
    {
        return [
            'course_id' => 'curso',
            'module_id' => 'módulo',
            'name'      => 'nombre del itinerario / contenido',
            'is_active' => 'estado activo',
        ];
    }
}
