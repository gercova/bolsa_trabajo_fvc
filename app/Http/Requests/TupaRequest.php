<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TupaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH');

        return [
            'title'                => 'required|string|max:255',
            'description'          => 'required|string',
            'file_path'            => $isUpdate ? 'nullable|file|mimes:pdf|max:20480' : 'required|file|mimes:pdf|max:20480',
            'effective_start_date' => 'required|date',
            'effective_end_date'   => 'nullable|date|after_or_equal:effective_start_date',
            'is_active'            => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'                => 'El título del TUPA es obligatorio.',
            'title.string'                  => 'El título debe ser un texto válido.',
            'title.max'                     => 'El título no puede exceder los 255 caracteres.',
            'description.required'          => 'La descripción del TUPA es obligatoria.',
            'file_path.required'            => 'El archivo PDF del TUPA es obligatorio.',
            'file_path.file'                => 'Debe seleccionar un archivo válido.',
            'file_path.mimes'               => 'El archivo debe estar en formato PDF.',
            'file_path.max'                 => 'El archivo PDF no debe pesar más de 20MB.',
            'effective_start_date.required' => 'La fecha de inicio de vigencia es obligatoria.',
            'effective_start_date.date'     => 'La fecha de inicio debe ser una fecha válida.',
            'effective_end_date.date'       => 'La fecha de fin debe ser una fecha válida.',
            'effective_end_date.after_or_equal' => 'La fecha de fin debe ser posterior o igual a la fecha de inicio.',
        ];
    }
}
