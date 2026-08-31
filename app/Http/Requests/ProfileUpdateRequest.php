<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $targetUser = $this->route('user');
        $userId = is_object($targetUser) ? $targetUser->id : ($targetUser ?: $this->user()?->id);

        return [
            'names'         => ['required', 'string', 'max:255'],
            'email'         => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'email')->ignore($userId),
            ],
            'dni'           => [
                'required',
                'string',
                'max:15',
                Rule::unique(User::class, 'dni')->ignore($userId),
            ],
            'phone'         => ['nullable', 'string', 'max:20'],
            'address'       => ['nullable', 'string', 'max:255'],
            'birthdate'     => ['nullable', 'date'],
            'sex'           => ['nullable', 'in:M,F'],
            'mother_tongue' => ['nullable', 'string', 'max:50'],
            'job_position'  => ['nullable', 'string', 'max:100'],
            'photo_profile' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'cv_file'       => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'names.required'         => 'El nombre completo es obligatorio.',
            'email.required'         => 'El correo electrónico es obligatorio.',
            'email.email'            => 'Ingrese una dirección de correo electrónico válida.',
            'email.unique'           => 'Este correo electrónico ya está registrado por otro usuario.',
            'dni.required'           => 'El número de DNI o documento es obligatorio.',
            'dni.unique'             => 'Este número de DNI ya se encuentra registrado.',
            'photo_profile.image'    => 'El archivo de foto de perfil debe ser una imagen.',
            'photo_profile.mimes'    => 'La foto debe estar en formato JPEG, PNG, JPG, GIF o WEBP.',
            'photo_profile.max'      => 'La foto no debe superar los 2MB de tamaño.',
            'cv_file.mimes'          => 'El archivo de CV debe ser un documento PDF.',
            'cv_file.max'            => 'El archivo de CV no debe exceder los 5MB.',
        ];
    }
}
