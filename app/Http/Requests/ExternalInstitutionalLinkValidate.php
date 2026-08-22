<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExternalInstitutionalLinkValidate extends FormRequest
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
            'name'      => ['required', 'string', 'max:255'],
            'link'      => ['required', 'url', 'max:500'],
            'icon'      => ['nullable', 'string', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre de la plataforma o enlace institucional es obligatorio.',
            'name.string'   => 'El nombre debe ser una cadena de texto válida.',
            'name.max'      => 'El nombre no debe exceder los 255 caracteres.',
            'link.required' => 'La URL del enlace institucional es obligatoria.',
            'link.url'      => 'Ingrese una dirección URL válida (ej. https://registra.minedu.gob.pe).',
            'link.max'      => 'La URL no debe exceder los 500 caracteres.',
            'icon.string'   => 'El nombre del icono debe ser texto.',
            'icon.max'      => 'El nombre del icono no debe exceder los 100 caracteres.',
        ];
    }

    /**
     * Prepare inputs before validation if needed.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->input('is_active'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE),
            ]);
        }
    }
}
