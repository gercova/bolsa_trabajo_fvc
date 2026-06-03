<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'title'       => 'required|string|max:255',
            'company'     => 'required|string|max:255',
            'location'    => 'required|string|max:255',
            'url'         => 'nullable|url|max:255',
            'source'      => 'nullable|string|max:100',
            'description' => 'required|string',
            'is_active'   => 'nullable|boolean',
        ];
    }

    public function messages(): array {
        return [
            'title.required'       => 'El título de la oferta laboral es obligatorio.',
            'title.max'            => 'El título no debe superar los 255 caracteres.',
            'company.required'     => 'El nombre de la empresa es obligatorio.',
            'company.max'          => 'La empresa no debe superar los 255 caracteres.',
            'location.required'    => 'La ubicación es obligatoria.',
            'location.max'         => 'La ubicación no debe superar los 255 caracteres.',
            'url.url'              => 'El formato del enlace o URL no es válido.',
            'url.max'              => 'La URL no debe superar los 255 caracteres.',
            'source.max'           => 'La fuente no debe superar los 100 caracteres.',
            'description.required' => 'La descripción del puesto es obligatoria.',
        ];
    }
}