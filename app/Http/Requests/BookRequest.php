<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare inputs before validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('title')) {
            $this->merge([
                'title'  => trim((string) $this->title),
                'author' => trim((string) $this->author),
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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH') || $this->route('book') !== null;
        $maxYear = (int) date('Y') + 1;

        return [
            'title'            => 'required|string|max:255',
            'author'           => 'required|string|max:255',
            'study_program_id' => 'nullable|exists:study_programs,id',
            'category'         => 'required|string|max:50',
            'description'      => 'nullable|string',
            'publisher'        => 'nullable|string|max:150',
            'publication_year' => "nullable|integer|min:1900|max:{$maxYear}",
            'edition'          => 'nullable|string|max:50',
            'pages'            => 'nullable|integer|min:1',
            'isbn'             => 'nullable|string|max:50',
            'language'         => 'nullable|string|max:50',
            'rating'           => 'nullable|numeric|min:1|max:5',
            'cover'            => 'nullable|image|mimes:jpeg,png,webp,jpg|max:4096',
            'file'             => 'nullable|file|mimes:pdf|max:51200', // 50MB max
            'external_url'     => 'nullable|url|max:500',
            'is_active'        => 'nullable|boolean',
        ];
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH') || $this->route('book') !== null;

            if (!$isUpdate) {
                if (!$this->hasFile('file') && empty($this->input('external_url'))) {
                    $validator->errors()->add('file', 'Debe adjuntar un archivo PDF o proporcionar un enlace web / URL al documento.');
                }
            }
        });
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required'        => 'El título del libro o documento es obligatorio.',
            'title.max'             => 'El título no debe exceder los 255 caracteres.',
            'author.required'       => 'El nombre del autor o autores es obligatorio.',
            'author.max'            => 'El autor no debe exceder los 255 caracteres.',
            'study_program_id.exists' => 'La carrera o programa seleccionado no es válido.',
            'category.required'     => 'Debe especificar el tipo de contenido (Libro, Revista, Paper, etc.).',
            'cover.image'           => 'La portada debe ser una imagen válida.',
            'cover.mimes'           => 'La portada debe estar en formato JPEG, PNG o WebP.',
            'cover.max'             => 'La imagen de portada no debe superar los 4 MB.',
            'file.mimes'            => 'El archivo principal debe ser un documento PDF (.pdf).',
            'file.max'              => 'El archivo PDF no debe exceder los 50 MB.',
            'external_url.url'      => 'El enlace web externo debe ser una dirección URL válida (ej. https://...).',
        ];
    }
}
