<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DegreeRecordImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'Debe seleccionar un archivo para importar.',
            'file.file'     => 'El campo debe ser un archivo válido.',
            'file.mimes'    => 'Solo se permiten archivos Excel (.xlsx, .xls) o CSV (.csv).',
            'file.max'      => 'El archivo no debe superar los 10 MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'file' => 'archivo de grados y títulos',
        ];
    }
}
