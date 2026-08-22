<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HistoricalReviewRequest extends FormRequest
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
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'start_year'  => 'nullable|integer|min:1900|max:2100',
            'end_year'    => 'nullable|integer|min:1900|max:2100|gte:start_year',
            'order'       => 'nullable|integer|min:0|max:255',
            'is_active'   => 'nullable|boolean',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,webp,gif|max:5120',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required'       => 'El título del hito o etapa histórica es obligatorio.',
            'title.string'         => 'El título debe ser una cadena de texto.',
            'title.max'            => 'El título no debe exceder los 255 caracteres.',
            'description.required' => 'La descripción del hito o etapa es obligatoria.',
            'description.string'   => 'La descripción debe ser un texto válido.',
            'start_year.integer'   => 'El año de inicio debe ser un número entero.',
            'start_year.min'       => 'El año de inicio no puede ser menor a 1900.',
            'start_year.max'       => 'El año de inicio no puede ser mayor a 2100.',
            'end_year.integer'     => 'El año de fin debe ser un número entero.',
            'end_year.min'         => 'El año de fin no puede ser menor a 1900.',
            'end_year.max'         => 'El año de fin no puede ser mayor a 2100.',
            'end_year.gte'         => 'El año de fin debe ser mayor o igual al año de inicio.',
            'order.integer'        => 'El orden debe ser un número entero.',
            'order.min'            => 'El orden no puede ser negativo.',
            'order.max'            => 'El orden no puede ser mayor a 255.',
            'is_active.boolean'    => 'El estado debe ser verdadero o falso.',
            'image.image'          => 'El archivo seleccionado debe ser una imagen válida.',
            'image.mimes'          => 'La imagen debe tener un formato válido: jpeg, png, jpg, webp o gif.',
            'image.max'            => 'El tamaño de la imagen no debe superar los 5MB (5120 KB).',
        ];
    }
}
