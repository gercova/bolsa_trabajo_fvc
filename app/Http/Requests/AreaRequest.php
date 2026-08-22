<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreaRequest extends FormRequest
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
        $this->merge([
            'name' => trim((string) $this->input('name')),
            'program_id' => $this->input('program_id') ?: null,
            'user_id' => $this->input('user_id') ?: null,
            'description' => $this->input('description') ? trim((string) $this->input('description')) : null,
            'details' => $this->input('details') ? trim((string) $this->input('details')) : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $areaId = $this->route('area') ? ($this->route('area') instanceof \App\Models\Area ? $this->route('area')->id : $this->route('area')) : null;

        return [
            'name' => [
                'required',
                'string',
                'max:100',
                Rule::unique('area', 'name')->ignore($areaId),
            ],
            'program_id' => ['nullable', 'exists:study_programs,id'],
            'user_id' => ['nullable', 'exists:users,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'details' => ['nullable', 'string'],
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
            'name.required' => 'El nombre del área es obligatorio.',
            'name.string' => 'El nombre del área debe ser una cadena de texto.',
            'name.max' => 'El nombre del área no debe exceder los 100 caracteres.',
            'name.unique' => 'Ya existe un área registrada con este nombre.',
            'program_id.exists' => 'El programa de estudio seleccionado no es válido.',
            'user_id.exists' => 'El usuario responsable seleccionado no es válido.',
            'description.max' => 'La descripción no debe exceder los 1000 caracteres.',
        ];
    }
}
