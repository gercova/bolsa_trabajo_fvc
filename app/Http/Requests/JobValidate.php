<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobValidate extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare inputs for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_active')) {
            $this->merge([
                'is_active' => filter_var($this->is_active, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? false,
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'title'       => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'url'         => 'nullable|url|max:500',
            'source'      => 'nullable|string|max:100',
            'description' => 'required|string|max:65535',
            'is_active'   => 'nullable|boolean',
        ];
    }

    /**
     * Custom error messages in Spanish.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'       => 'El título del puesto u oferta laboral es obligatorio.',
            'title.string'         => 'El título del puesto debe ser un texto válido.',
            'title.max'            => 'El título no debe superar los 255 caracteres.',
            'company.required'     => 'El nombre de la empresa o institución es obligatorio.',
            'company.string'       => 'La empresa debe ser un texto válido.',
            'company.max'          => 'El nombre de la empresa no debe superar los 255 caracteres.',
            'location.required'    => 'La ubicación de la oferta es obligatoria.',
            'location.string'      => 'La ubicación debe ser un texto válido.',
            'location.max'         => 'La ubicación no debe superar los 255 caracteres.',
            'url.url'              => 'El formato del enlace o URL debe ser válido (ejemplo: https://ejemplo.com).',
            'url.max'              => 'La URL de postulación no debe superar los 255 caracteres.',
            'source.string'        => 'La fuente debe ser un texto válido.',
            'source.max'           => 'La fuente no debe superar los 100 caracteres.',
            'description.required' => 'La descripción detallada del puesto es obligatoria.',
            'description.string'   => 'La descripción debe ser un texto válido.',
        ];
    }
}