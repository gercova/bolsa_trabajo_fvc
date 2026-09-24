<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ScholarshipBeneficiaryRequest extends FormRequest
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
        if ($this->has('academic_period')) {
            $this->merge([
                'academic_period' => trim((string) $this->academic_period),
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
        $isUpdate = $this->isMethod('PUT') || $this->isMethod('PATCH') || $this->route('beneficiary') !== null;

        return [
            'academic_period'   => 'required|string|max:50',
            'title'             => 'required|string|max:255',
            'description'       => 'nullable|string',
            'resolution_number' => 'nullable|string|max:100',
            'publication_date'  => 'nullable|date',
            'scholarship_id'    => 'nullable|exists:scholarships,id',
            'file'              => [
                $isUpdate ? 'nullable' : 'required',
                'file',
                'mimes:pdf',
                'max:25600', // max 25MB
            ],
            'sort_order'        => 'nullable|integer|min:0',
            'is_active'         => 'nullable|boolean',
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
            'academic_period.required'   => 'El periodo, ciclo o semestre académico es obligatorio (ej. 2026-I, 2026-II).',
            'academic_period.max'        => 'El periodo académico no debe exceder los 50 caracteres.',
            'title.required'             => 'El título o nombre del padrón de beneficiarios es obligatorio.',
            'title.max'                  => 'El título no debe exceder los 255 caracteres.',
            'resolution_number.max'      => 'El número de resolución no debe exceder los 100 caracteres.',
            'publication_date.date'      => 'La fecha de publicación debe ser una fecha válida.',
            'scholarship_id.exists'      => 'La modalidad de beca seleccionada no es válida.',
            'file.required'              => 'Debe adjuntar el archivo PDF con la relación de estudiantes beneficiarios.',
            'file.file'                  => 'El archivo cargado debe ser un archivo válido.',
            'file.mimes'                 => 'El archivo adjunto debe ser de formato PDF (.pdf).',
            'file.max'                   => 'El archivo PDF no debe exceder los 25 MB.',
            'sort_order.integer'         => 'El orden de clasificación debe ser un número entero.',
            'sort_order.min'             => 'El orden de clasificación no puede ser negativo.',
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
            'academic_period'   => 'periodo académico',
            'title'             => 'título del documento',
            'description'       => 'descripción u observaciones',
            'resolution_number' => 'número de resolución',
            'publication_date'  => 'fecha de publicación',
            'scholarship_id'    => 'modalidad de beca',
            'file'              => 'archivo PDF',
            'sort_order'        => 'orden de posición',
            'is_active'         => 'estado activo',
        ];
    }
}
