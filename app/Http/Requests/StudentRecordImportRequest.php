<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRecordImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file'            => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
            'academic_period' => ['required', 'string', 'max:20', 'regex:/^\d{4}-(I|II)$/'],
            'record_type'     => ['required', 'string', 'in:ADMISION,MATRICULA,AUTO'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required'            => 'Debe seleccionar un archivo para importar.',
            'file.file'                => 'El campo debe ser un archivo válido.',
            'file.mimes'               => 'Solo se permiten archivos Excel (.xlsx, .xls) o CSV (.csv).',
            'file.max'                 => 'El archivo no debe superar los 10 MB.',
            'academic_period.required' => 'El período académico es obligatorio (ej. 2026-I).',
            'academic_period.regex'    => 'El período debe tener el formato correcto: 2026-I o 2026-II.',
            'record_type.required'     => 'Debe especificar el tipo de registro.',
            'record_type.in'           => 'El tipo de registro debe ser ADMISION, MATRICULA o AUTO.',
        ];
    }

    public function attributes(): array
    {
        return [
            'file'            => 'archivo',
            'academic_period' => 'período académico',
            'record_type'     => 'tipo de registro',
        ];
    }
}
