<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdmissionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'period'                 => 'required|string|max:20',
            'total_vacancies'        => 'required|integer|min:0',
            'observation'            => 'nullable|string',
            'exam_date'              => 'required|date',
            'inscription_start_date' => 'required|date',
            'inscription_end_date'   => 'required|date|after_or_equal:inscription_start_date',
            'url_pdf'                => 'nullable|file|mimes:pdf|max:10240',
            'price'                  => 'required|numeric|min:0',
            'type'                   => 'required|string|in:ordinario,extraordinario',
            'process'                => 'required|string|in:admisión,cepre',
            'is_active'              => 'boolean',
            'programs'               => 'nullable|array',
            'programs.*.program_id'  => 'required_with:programs|exists:study_programs,id',
            'programs.*.vacancies'   => 'required_with:programs|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'period.required'                 => 'El campo período es obligatorio.',
            'period.string'                   => 'El período debe ser un texto válido.',
            'period.max'                      => 'El período no puede exceder los 20 caracteres.',
            'total_vacancies.required'        => 'El campo total de vacantes es obligatorio.',
            'total_vacancies.integer'         => 'El total de vacantes debe ser un número entero.',
            'total_vacancies.min'             => 'El total de vacantes no puede ser menor a 0.',
            'observation.string'              => 'La observación debe ser un texto válido.',
            'exam_date.required'              => 'La fecha del examen es obligatoria.',
            'exam_date.date'                  => 'La fecha del examen debe ser una fecha válida.',
            'inscription_start_date.required' => 'La fecha de inicio de inscripción es obligatoria.',
            'inscription_start_date.date'      => 'La fecha de inicio de inscripción debe ser una fecha válida.',
            'inscription_end_date.required'   => 'La fecha de fin de inscripción es obligatoria.',
            'inscription_end_date.date'        => 'La fecha de fin de inscripción debe ser una fecha válida.',
            'inscription_end_date.after_or_equal' => 'La fecha de fin de inscripción debe ser igual o posterior a la fecha de inicio de inscripción.',
            'url_pdf.file'                    => 'El archivo de bases debe ser un documento válido.',
            'url_pdf.mimes'                   => 'El archivo de bases debe ser un documento en formato PDF.',
            'url_pdf.max'                     => 'El archivo de bases no puede superar los 10 MB.',
            'price.required'                  => 'El precio es obligatorio.',
            'price.numeric'                   => 'El precio debe ser un valor numérico.',
            'price.min'                       => 'El precio no puede ser menor a 0.',
            'type.required'                   => 'El tipo de examen es obligatorio.',
            'type.in'                         => 'El tipo de examen seleccionado no es válido.',
            'process.required'                => 'El proceso es obligatorio.',
            'process.in'                      => 'El proceso seleccionado no es válido.',
            'programs.array'                  => 'El formato de los programas seleccionados es inválido.',
            'programs.*.program_id.exists'    => 'El programa seleccionado no existe.',
            'programs.*.vacancies.integer'    => 'Las vacantes por programa deben ser un número entero.',
            'programs.*.vacancies.min'        => 'Las vacantes por programa no pueden ser menores a 0.',
        ];
    }
}
