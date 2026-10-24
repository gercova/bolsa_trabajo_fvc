<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UserValidate extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('dni')) {
            $this->merge(['dni' => trim((string) $this->dni)]);
        }
        if ($this->has('email')) {
            $this->merge(['email' => trim((string) $this->email)]);
        }
    }

    public function rules(): array
    {
        // On update routes the User model is bound via route model binding.
        // On store (create) $userId is null, so the unique rule applies without exception.
        $userId = $this->route('user')?->id;

        return [
            'dni' => 'required|string|max:20|unique:users,dni,'.$userId,
            'names' => 'required|string|max:255',
            'email' => 'required|email:rfc|max:255|unique:users,email,'.$userId,
            'role' => 'required',
            'job_position' => 'nullable|string|max:100',
            'photo_profile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'cv_file' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'is_active' => 'nullable|boolean',
            // Password is optional: if omitted on create it defaults to DNI in controller; optional on update
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            // DNI
            'dni.required' => 'El campo DNI es obligatorio.',
            'dni.string' => 'El DNI debe ser un texto válido.',
            'dni.max' => 'El DNI no puede exceder los 20 caracteres.',
            'dni.unique' => 'Este DNI ya se encuentra registrado en el sistema.',
            // Nombres
            'names.required' => 'El campo nombres es obligatorio.',
            'names.string' => 'Los nombres deben ser un texto válido.',
            'names.max' => 'Los nombres no pueden exceder los 255 caracteres.',
            // Email
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.email' => 'Debe ingresar un correo electrónico válido.',
            'email.max' => 'El correo electrónico no puede exceder los 255 caracteres.',
            'email.unique' => 'Este correo electrónico ya se encuentra registrado.',
            // Rol
            'role.required' => 'El campo rol es obligatorio.',
            // Cargo / Puesto
            'job_position.string' => 'El cargo debe ser un texto válido.',
            'job_position.max' => 'El cargo no puede exceder los 100 caracteres.',
            // Foto de perfil
            'photo_profile.image' => 'El archivo debe ser una imagen válida.',
            'photo_profile.mimes' => 'La foto de perfil debe ser de tipo: jpeg, png, jpg, gif, webp.',
            'photo_profile.max' => 'La foto de perfil no puede superar los 2 MB.',
            // Archivo CV
            'cv_file.file' => 'El archivo de CV debe ser un documento válido.',
            'cv_file.mimes' => 'El CV debe ser de tipo: pdf, doc, docx.',
            'cv_file.max' => 'El CV no puede superar los 5 MB.',
            // Contraseña
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ];
    }
}
