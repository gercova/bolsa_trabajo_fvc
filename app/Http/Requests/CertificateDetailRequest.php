<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CertificateDetailRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'module_id' => [
                'required',
                'exists:modules,id',
            ],
            'score' => [
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
            'module_id.required' => 'Debe seleccionar un módulo.',
            'module_id.exists'   => 'El módulo seleccionado no existe.',
        ];
    }

    public function attributes(): array
    {
        return [
            'module_id' => 'módulo',
            'score'     => 'calificación / nota',
            'is_active' => 'estado activo',
        ];
    }
}
