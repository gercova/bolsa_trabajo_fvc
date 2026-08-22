<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClaimValidate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'dni'       => ['required', 'string', 'regex:/^\d{8}$/'],
            'names'     => ['required', 'string', 'max:255'],
            'email'     => ['required', 'email:rfc,dns', 'max:255'],
            'subject'   => ['required', 'string', 'max:255'],
            'message'   => ['required', 'string', 'max:5000'],
            'file_path' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ];
    }

    public function messages(): array
    {
        return [
            'dni.required'     => 'El número de DNI es obligatorio.',
            'dni.regex'        => 'El DNI debe contener exactamente 8 dígitos numéricos.',
            'names.required'   => 'Nombres y Apellidos son obligatorios.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'El correo electrónico debe ser una dirección válida.',
            'subject.required' => 'El asunto/tipo de reclamo es obligatorio.',
            'message.required' => 'El detalle del reclamo o queja es obligatorio.',
            'message.max'      => 'El detalle no puede exceder los 5000 caracteres.',
            'file_path.file'   => 'El archivo adjunto debe ser un archivo válido.',
            'file_path.mimes'  => 'El formato del archivo adjunto debe ser: pdf, jpg, jpeg, png.',
            'file_path.max'    => 'El archivo no debe superar los 10 MB.',
        ];
    }
}
