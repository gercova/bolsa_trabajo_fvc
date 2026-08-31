<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CertificateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $certId = $this->route('certificate')?->id ?? $this->certificate;

        return [
            'user_id' => [
                'required',
                'exists:users,id',
            ],
            'course_id' => [
                'required',
                'exists:courses,id',
            ],
            'certificate_code' => [
                'required',
                'string',
                'max:100',
                Rule::unique('certificates', 'certificate_code')->ignore($certId),
            ],
            'description' => [
                'nullable',
                'string',
                'max:500',
            ],
            'start_date' => [
                'nullable',
                'date',
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
            'duration' => [
                'nullable',
                'string',
                'max:100',
            ],
            'modality' => [
                'required',
                'string',
                Rule::in(['Presencial', 'Semipresencial', 'Virtual']),
            ],
            'issue_date' => [
                'required',
                'date',
            ],
            'is_active' => [
                'nullable',
                'boolean',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required'          => 'Debe seleccionar un estudiante/usuario.',
            'user_id.exists'            => 'El usuario seleccionado no existe.',
            'course_id.required'        => 'Debe seleccionar un curso.',
            'course_id.exists'          => 'El curso seleccionado no existe.',
            'certificate_code.required' => 'El código del certificado es obligatorio.',
            'certificate_code.unique'   => 'Este código de certificado ya está registrado.',
            'issue_date.required'       => 'La fecha de emisión es obligatoria.',
            'issue_date.date'           => 'La fecha de emisión no es válida.',
            'end_date.after_or_equal'   => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'user_id'          => 'estudiante / usuario',
            'course_id'        => 'curso',
            'certificate_code' => 'código de certificado',
            'description'      => 'descripción',
            'start_date'       => 'fecha de inicio',
            'end_date'         => 'fecha de fin',
            'duration'         => 'duración / horas académicas',
            'issue_date'       => 'fecha de emisión',
            'is_active'        => 'estado activo',
        ];
    }
}
