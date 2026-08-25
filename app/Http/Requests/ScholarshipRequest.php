<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScholarshipRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if (!$this->has('slug') || empty($this->slug)) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->name),
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
        $scholarshipId = $this->scholarship ? $this->scholarship->id : null;

        return [
            'name'                => 'required|string|max:100|unique:scholarships,name,' . $scholarshipId,
            'slug'                => 'required|string|max:120|unique:scholarships,slug,' . $scholarshipId,
            'description'         => 'nullable|string',
            'vacancies'           => 'nullable|integer|min:0',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_details'    => 'nullable|string|max:255',
            'requirements'        => 'nullable|string',
            'icon'                => 'nullable|string|max:50',
            'sort_order'          => 'nullable|integer|min:0',
            'is_active'           => 'nullable|boolean',
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required'                => 'El nombre de la beca o modalidad es obligatorio.',
            'name.string'                  => 'El nombre debe ser un texto válido.',
            'name.max'                     => 'El nombre no puede exceder los 100 caracteres.',
            'name.unique'                  => 'Ya existe una beca con este nombre.',
            'slug.required'                => 'El slug de enlace es obligatorio.',
            'slug.unique'                  => 'Ya existe una beca con este slug.',
            'vacancies.integer'            => 'El número de vacantes debe ser un número entero.',
            'vacancies.min'                => 'El número de vacantes no puede ser negativo.',
            'discount_percentage.numeric'  => 'El porcentaje de descuento debe ser un valor numérico.',
            'discount_percentage.min'      => 'El porcentaje de descuento no puede ser menor a 0.',
            'discount_percentage.max'      => 'El porcentaje de descuento no puede ser mayor a 100.',
            'discount_details.max'         => 'El detalle del descuento no puede exceder los 255 caracteres.',
            'icon.max'                     => 'El nombre del icono no debe exceder los 50 caracteres.',
            'sort_order.integer'           => 'El orden de clasificación debe ser un número entero.',
            'sort_order.min'               => 'El orden de clasificación no puede ser negativo.',
        ];
    }

    /**
     * Custom attributes names.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name'                => 'nombre de la modalidad',
            'slug'                => 'slug',
            'description'         => 'descripción',
            'vacancies'           => 'vacantes disponibles',
            'discount_percentage' => 'porcentaje de descuento',
            'discount_details'    => 'detalle del descuento',
            'requirements'        => 'requisitos específicos',
            'icon'                => 'icono representativo',
            'sort_order'          => 'orden de posición',
            'is_active'           => 'estado activo',
        ];
    }
}
