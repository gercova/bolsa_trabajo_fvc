<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogValidate extends FormRequest
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
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string', 'max:65535'],
            'details'      => ['nullable', 'string', 'max:65535'],
            'is_published' => ['nullable', 'boolean'],
            'image'        => ['nullable', 'image', 'mimes:jpeg,jpg,png,webp,gif', 'max:4096'],
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
            'title.required'     => 'El título de la publicación es obligatorio.',
            'title.string'       => 'El título debe ser un texto válido.',
            'title.max'          => 'El título no debe superar los 255 caracteres.',
            'content.required'   => 'El contenido del blog es obligatorio.',
            'image.image'        => 'El archivo seleccionado debe ser una imagen válida.',
            'image.mimes'        => 'La imagen debe tener un formato válido (jpeg, png, jpg, webp, gif).',
            'image.max'          => 'La imagen no debe pesar más de 4 MB.',
        ];
    }

    /**
     * Prepare inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('is_published')) {
            $this->merge([
                'is_published' => filter_var($this->input('is_published'), FILTER_VALIDATE_BOOLEAN),
            ]);
        }
    }
}
