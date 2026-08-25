<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Datos personales
            'document_type_id'      => ['nullable', 'exists:document_type,id'],
            'document'              => ['nullable', 'string', 'max:20'],
            'last_name_father'      => ['nullable', 'string', 'max:100'],
            'last_name_mother'      => ['nullable', 'string', 'max:100'],
            'names'                 => ['required', 'string', 'max:150'],
            'birthdate'             => ['nullable', 'date'],
            'gender'                => ['nullable', 'in:MASCULINO,FEMENINO'],
            'mother_tongue'         => ['nullable', 'string', 'max:50'],
            'email'                 => ['nullable', 'email', 'max:150'],
            'phone'                 => ['nullable', 'string', 'max:20'],
            'pais_procedencia'      => ['nullable', 'string', 'max:50'],

            // Institución de procedencia
            'ubigeo_ie'             => ['nullable', 'string', 'max:20'],
            'region_ie'             => ['nullable', 'string', 'max:100'],
            'province_ie'           => ['nullable', 'string', 'max:100'],
            'district_ie'           => ['nullable', 'string', 'max:100'],
            'institution_type_ie'   => ['nullable', 'string', 'max:50'],
            'modular_code_ie'       => ['nullable', 'string', 'max:20'],
            'institution_name_ie'   => ['nullable', 'string', 'max:200'],
            'management_type_ie'    => ['nullable', 'string', 'max:50'],
            'year_graduation'       => ['nullable', 'integer', 'min:1950', 'max:2100'],

            // Institución destino
            'region'                => ['nullable', 'string', 'max:100'],
            'province'              => ['nullable', 'string', 'max:100'],
            'district'              => ['nullable', 'string', 'max:100'],
            'codigo_modular'        => ['nullable', 'string', 'max:20'],
            'nombre_institucion'    => ['nullable', 'string', 'max:200'],
            'tipo_gestion'          => ['nullable', 'string', 'max:50'],

            // Proceso académico
            'academic_period'       => ['required', 'string', 'max:20'],
            'study_program'         => ['required', 'string', 'max:150'],
            'modality'              => ['nullable', 'string', 'max:50'],
            'modality_type'         => ['nullable', 'string', 'max:50'],
            'headquarters'          => ['nullable', 'string', 'max:150'],
            'route_type'            => ['nullable', 'string', 'max:50'],
            'shift'                 => ['nullable', 'string', 'max:20'],
            'score'                 => ['nullable', 'numeric', 'min:0', 'max:100'],
            'situation'             => ['nullable', 'string', 'max:100'],
            'cycle'                 => ['nullable', 'string', 'max:20'],
            'enrollment_status'     => ['nullable', 'string', 'max:50'],
            'period_status'         => ['nullable', 'string', 'max:50'],
            'record_type'           => ['required', 'in:ADMISION,MATRICULA'],
            'registration_date'     => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'names.required'            => 'Los nombres del estudiante son obligatorios.',
            'names.max'                 => 'Los nombres no pueden exceder 150 caracteres.',
            'academic_period.required'  => 'El período académico (ej. 2026-I) es obligatorio.',
            'study_program.required'    => 'El programa de estudios es obligatorio.',
            'record_type.required'      => 'El tipo de registro (ADMISION o MATRICULA) es obligatorio.',
            'record_type.in'            => 'El tipo de registro debe ser ADMISION o MATRICULA.',
            'score.numeric'             => 'El puntaje debe ser un valor numérico.',
            'year_graduation.integer'   => 'El año de egreso debe ser un número entero.',
            'email.email'               => 'El correo electrónico debe ser válido.',
        ];
    }

    public function attributes(): array
    {
        return [
            'document_type_id'      => 'tipo de documento',
            'document'              => 'número de documento',
            'last_name_father'      => 'apellido paterno',
            'last_name_mother'      => 'apellido materno',
            'names'                 => 'nombres',
            'academic_period'       => 'período académico',
            'study_program'         => 'programa de estudios',
            'record_type'           => 'tipo de registro',
            'score'                 => 'puntaje',
            'situation'             => 'situación',
            'cycle'                 => 'ciclo',
        ];
    }
}
