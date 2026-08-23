<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagementDocumentRequest extends FormRequest
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
        $isFileRequired = $this->isMethod('post');

        return [
            'title'           => 'required|string|max:255',
            'description'     => 'nullable|string',
            'details'         => 'nullable|string',
            'file_path'       => [
                $isFileRequired ? 'required' : 'nullable',
                'file',
                'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,webp',
                'max:20480', // 20 MB max
            ],
            'resolution_document_path' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:20480', // 20 MB max
            ],
            'validity_period' => 'nullable|date',
            'is_active'       => 'nullable|boolean',
        ];
    }

    /**
     * Custom attribute names for validation errors.
     */
    public function attributes(): array
    {
        return [
            'title'                    => 'título',
            'description'              => 'descripción',
            'details'                  => 'detalles',
            'file_path'                => 'archivo del documento',
            'resolution_document_path' => 'documento de resolución',
            'validity_period'          => 'periodo de vigencia',
            'is_active'                => 'estado activo',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'title.required'                 => 'El título es obligatorio.',
            'file_path.required'             => 'Debe adjuntar un archivo para el documento.',
            'file_path.mimes'                => 'El archivo debe ser de tipo PDF, Word, Excel, PowerPoint o imagen (JPG, PNG, WEBP).',
            'file_path.max'                  => 'El tamaño máximo del archivo es de 20 MB.',
            'resolution_document_path.mimes' => 'El documento de resolución debe ser de formato PDF.',
            'resolution_document_path.max'   => 'El tamaño máximo del documento de resolución es de 20 MB.',
            'validity_period.date'           => 'Ingrese una fecha de vigencia válida.',
        ];
    }
}
