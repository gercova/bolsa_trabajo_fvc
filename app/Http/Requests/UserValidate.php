<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Password;

class UserValidate extends FormRequest
{
    public function authorize(): bool {
        return true;
    }

    public function rules(): array {
        return [
            'dni'           => 'required|string|max:20|unique:users,dni,'.$this->id,
            'names'         => 'required|string|max:255',
            'email'         => 'required|email|max:255|unique:users,email,'.$this->id,
            'role'          => 'required',
            'job_position'  => 'nullable|string|max:100',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cv_file'       => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'is_active'     => 'boolean',
        ];
    }

    public function messages(): array {
        return [
            // DNI
            'dni.required' => 'El campo DNI es obligatorio.',
            'dni.string'   => 'El DNI debe ser un texto válido.',
            'dni.max'      => 'El DNI no puede exceder los 20 caracteres.',
            'dni.unique'   => 'Este DNI ya se encuentra registrado en el sistema.',

            // Nombres
            'names.required' => 'El campo nombres es obligatorio.',
            'names.string'   => 'Los nombres deben ser un texto válido.',
            'names.max'      => 'Los nombres no pueden exceder los 255 caracteres.',

            // Email
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email'    => 'Debe ingresar un correo electrónico válido.',
            'email.max'      => 'El correo electrónico no puede exceder los 255 caracteres.',
            'email.unique'   => 'Este correo electrónico ya se encuentra registrado.',

            // Rol
            'role.required' => 'El campo rol es obligatorio.',
            // 'role.in'       => 'El rol seleccionado no es válido. Debe ser: admin, postulante o empresa.',

            // Cargo / Puesto
            'job_position.string' => 'El cargo debe ser un texto válido.',
            'job_position.max'    => 'El cargo no puede exceder los 100 caracteres.',

            // Foto de perfil
            'photo_profile.image' => 'El archivo debe ser una imagen válida.',
            'photo_profile.mimes' => 'La foto de perfil debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'photo_profile.max'   => 'La foto de perfil no puede superar los 2 MB.',

            // Archivo CV
            'cv_file.file'  => 'El archivo de CV debe ser un documento válido.',
            'cv_file.mimes' => 'El CV debe ser de tipo: pdf, doc, docx.',
            'cv_file.max'   => 'El CV no puede superar los 5 MB.',

            // Contraseña
            // 'password.required'     => 'El campo contraseña es obligatorio.',
            // 'password.confirmed'    => 'La confirmación de la contraseña no coincide.',
            // 'password'              => 'La contraseña no cumple con los requisitos de seguridad mínimos.',
        ];
    }
}
