<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TupaCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'      => 'required|string|max:255',
            'icon'      => 'required|string|max:100',
            'is_active' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.string'   => 'El nombre debe ser un texto válido.',
            'name.max'      => 'El nombre no puede exceder los 255 caracteres.',
            'icon.required' => 'El ícono es obligatorio.',
            'icon.string'   => 'El ícono debe ser una clase de Bootstrap Icons válida (ej: bi-journal-check).',
            'icon.max'      => 'El ícono no puede exceder los 100 caracteres.',
        ];
    }
}
