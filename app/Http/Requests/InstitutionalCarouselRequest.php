<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InstitutionalCarouselRequest extends FormRequest
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
            'tag'                   => 'nullable|string|max:255',
            'tag_icon'              => 'nullable|string|max:100',
            'tag_color'             => 'nullable|string|in:amber,sky,rose,emerald,indigo,purple',
            'title'                 => 'required|string|max:255',
            'highlight_text'        => 'nullable|string|max:255',
            'description'           => 'nullable|string|max:1000',
            'primary_button_text'   => 'nullable|string|max:100',
            'primary_button_url'    => 'nullable|string|max:255',
            'primary_button_icon'   => 'nullable|string|max:100',
            'secondary_button_text' => 'nullable|string|max:100',
            'secondary_button_url'  => 'nullable|string|max:255',
            'secondary_button_icon' => 'nullable|string|max:100',
            'indicator_label'       => 'nullable|string|max:50',
            'order'                 => 'nullable|integer|min:0',
            'is_active'             => 'nullable',
            'image'                 => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     */
    public function attributes(): array
    {
        return [
            'tag'                   => 'etiqueta / distintivo',
            'tag_icon'              => 'ícono de etiqueta',
            'tag_color'             => 'color de acento',
            'title'                 => 'título principal',
            'highlight_text'        => 'texto resaltado',
            'description'           => 'descripción',
            'primary_button_text'   => 'texto del botón principal',
            'primary_button_url'    => 'enlace del botón principal',
            'primary_button_icon'   => 'ícono del botón principal',
            'secondary_button_text' => 'texto del botón secundario',
            'secondary_button_url'  => 'enlace del botón secundario',
            'secondary_button_icon' => 'ícono del botón secundario',
            'indicator_label'       => 'etiqueta de navegación',
            'order'                 => 'número de orden',
            'image'                 => 'imagen de fondo del carrusel',
        ];
    }
}
