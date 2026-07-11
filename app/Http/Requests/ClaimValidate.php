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
            'dni'       => 'required|string|min:8|max:11',
            'names'     => 'required|string|max:255',
            'email'     => 'required|email|max:255',
            'subject'   => 'required|string|max:255',
            'message'   => 'required|string',
            'file_path' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'dni.required'     => 'El número de documento es obligatorio.',
            'dni.min'          => 'El documento debe tener al menos 8 caracteres.',
            'dni.max'          => 'El documento no debe exceder los 11 caracteres.',
            'names.required'   => 'Nombres y Apellidos son obligatorios.',
            'email.required'   => 'El correo electrónico es obligatorio.',
            'email.email'      => 'El correo electrónico debe ser una dirección válida.',
            'subject.required' => 'El asunto/tipo de reclamo es obligatorio.',
            'message.required' => 'El detalle del reclamo o queja es obligatorio.',
            'file_path.file'   => 'El archivo adjunto debe ser un archivo válido.',
            'file_path.mimes'  => 'El formato del archivo adjunto debe ser: pdf, jpg, jpeg, png.',
            'file_path.max'    => 'El archivo no debe superar los 10 MB.',
        ];
    }
}
