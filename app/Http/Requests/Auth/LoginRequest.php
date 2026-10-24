<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $input = trim((string) $this->input('email'));
        if (str_contains($input, '@')) {
            $input = Str::lower($input);
        }

        $this->merge([
            'email' => $input,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'max:255'],
            'remember' => ['nullable', 'boolean'],
            '_hp_security_check' => ['nullable', 'string', 'max:255'],
        ];
    }

    /**
     * Custom validation error messages.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'El correo electrónico o N° de DNI es obligatorio.',
            'email.max' => 'El identificador de acceso no debe exceder 255 caracteres.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.max' => 'La contraseña ingresada es demasiado extensa.',
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $loginInput = (string) $this->input('email');
        $password = (string) $this->input('password');
        $remember = $this->boolean('remember');
        $hp = $this->input('_hp_security_check');

        // Trampa honeypot para scripts o bots automatizados (ignorar si es autofill del navegador idéntico al usuario)
        if (! empty($hp) && $hp !== $loginInput) {
            RateLimiter::hit($this->throttleKey(), 300);
            Log::warning('Alerta de seguridad: Trampa honeypot activada en login', [
                'ip' => $this->ip(),
                'user_agent' => $this->userAgent(),
                'email_intent' => $loginInput,
            ]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        $isEmail = str_contains($loginInput, '@');
        $primaryField = $isEmail ? 'email' : 'dni';
        $secondaryField = $isEmail ? 'dni' : 'email';

        // Verificar si el usuario existe para validar su estado activo
        $user = User::where($primaryField, $loginInput)
            ->orWhere($secondaryField, $loginInput)
            ->first();

        if ($user && ! $user->is_active) {
            RateLimiter::hit($this->throttleKey(), 60);
            RateLimiter::hit($this->ipThrottleKey(), 60);

            Log::warning('Intento de inicio de sesión de usuario inactivo', [
                'user_id' => $user->id,
                'login_input' => $loginInput,
                'role' => $user->role,
                'ip' => $this->ip(),
            ]);

            throw ValidationException::withMessages([
                'email' => 'Su cuenta se encuentra inactiva o inhabilitada. Comuníquese con la administración institucional.',
            ]);
        }

        // Intento con campo primario
        $authenticated = Auth::attempt([$primaryField => $loginInput, 'password' => $password], $remember);

        // Si falló, intentar con campo secundario (por ejemplo DNI si escribió email o viceversa)
        if (! $authenticated) {
            $authenticated = Auth::attempt([$secondaryField => $loginInput, 'password' => $password], $remember);
        }

        if (! $authenticated) {
            RateLimiter::hit($this->throttleKey(), 60);
            RateLimiter::hit($this->ipThrottleKey(), 60);

            $currentAttempts = RateLimiter::attempts($this->throttleKey());
            Log::warning('Intento de inicio de sesión fallido', [
                'login_input' => $loginInput,
                'ip' => $this->ip(),
                'intentos' => $currentAttempts,
                'user_agent' => $this->userAgent(),
            ]);

            throw ValidationException::withMessages([
                'email' => __('auth.failed'),
            ]);
        }

        // Limpiar limitadores de tasa tras autenticación exitosa
        RateLimiter::clear($this->throttleKey());
        RateLimiter::clear($this->ipThrottleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        $accountLocked = RateLimiter::tooManyAttempts($this->throttleKey(), 5);
        $ipLocked = RateLimiter::tooManyAttempts($this->ipThrottleKey(), 10);

        if (! $accountLocked && ! $ipLocked) {
            return;
        }

        event(new Lockout($this));

        $seconds = $accountLocked
            ? RateLimiter::availableIn($this->throttleKey())
            : RateLimiter::availableIn($this->ipThrottleKey());

        Log::alert('Bloqueo temporal por exceso de intentos de inicio de sesión', [
            'email' => $this->input('email'),
            'ip' => $this->ip(),
            'segundos_espera' => $seconds,
            'motivo' => $accountLocked ? 'Límite por cuenta' : 'Límite por IP',
        ]);

        throw ValidationException::withMessages([
            'email' => __('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => (int) ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }

    /**
     * Get the IP rate limiting throttle key.
     */
    public function ipThrottleKey(): string
    {
        return 'login_ip|'.$this->ip();
    }
}
