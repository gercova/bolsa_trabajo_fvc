<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Enterprise;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): Response
    {
        $enterprise = Enterprise::first() ?? Enterprise::getDefault();
        $initialDni = trim((string) $request->input('dni'));
        $matchedUser = null;

        if ($initialDni !== '' && preg_match('/^[0-9]{8}$/', $initialDni)) {
            $matchedUser = User::where('dni', $initialDni)->first();
        }

        return Inertia::render('Auth/Register', compact('enterprise', 'matchedUser', 'initialDni'));
    }

    /**
     * Verify DNI presence in the institutional users census via AJAX/JSON.
     */
    public function verifyDni(Request $request): JsonResponse
    {
        $dni = trim((string) ($request->input('dni') ?: $request->query('dni')));

        if (empty($dni) || ! preg_match('/^[0-9]{8}$/', $dni)) {
            return response()->json([
                'success' => false,
                'message' => 'El número de DNI debe contener exactamente 8 dígitos numéricos.',
            ], 422);
        }

        $user = User::where('dni', $dni)->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'registered' => false,
                'message' => 'El DNI ingresado no se encuentra en el padrón institucional. El registro está restringido exclusivamente a estudiantes, egresados, docentes y personal del IESTP Francisco Vigo Caballero.',
            ], 404);
        }

        $alreadyRegistered = ! empty($user->personal_email);

        $birthdateFormatted = null;
        if ($user->birthdate) {
            try {
                $birthdateFormatted = $user->birthdate instanceof Carbon
                    ? $user->birthdate->format('Y-m-d')
                    : Carbon::parse($user->birthdate)->format('Y-m-d');
            } catch (\Throwable) {
                $birthdateFormatted = substr((string) $user->birthdate, 0, 10);
            }
        }

        return response()->json([
            'success' => true,
            'already_registered' => $alreadyRegistered,
            'message' => $alreadyRegistered
                ? 'Este DNI ya completó su registro institucional previamente. Puede iniciar sesión directamente.'
                : '¡Identidad institucional verificada con éxito!',
            'user' => [
                'id' => $user->id,
                'dni' => $user->dni,
                'names' => $user->names,
                'birthdate' => $birthdateFormatted,
                'phone' => $user->phone,
                'sex' => $user->sex,
                'address' => $user->address,
                'institutional_email' => $user->email,
                'personal_email' => $user->personal_email,
                'role' => $user->role,
                'job_position' => $user->job_position,
                'has_cv' => ! empty($user->cv_file),
            ],
        ]);
    }

    /**
     * Handle an incoming registration request for an existing institutional member.
     */
    public function store(Request $request): RedirectResponse
    {
        $dni = trim((string) $request->input('dni'));
        $user = User::where('dni', $dni)->first();

        if (! $user) {
            throw ValidationException::withMessages([
                'dni' => 'El DNI ingresado no se encuentra en el padrón institucional. El registro está restringido exclusivamente a estudiantes, egresados, docentes y personal del IESTP Francisco Vigo Caballero.',
            ]);
        }

        // Support both 'personal_email' and 'email' field names gracefully
        $personalEmailInput = $request->input('personal_email') ?: $request->input('email');
        $request->merge(['personal_email' => $personalEmailInput]);

        $namesInput = $request->input('names') ?: $request->input('name');
        $request->merge(['names' => $namesInput ?: $user->names]);

        $request->validate([
            'dni' => ['required', 'string', 'digits:8'],
            'personal_email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class, 'personal_email')->ignore($user->id),
            ],
            'names' => ['required', 'string', 'max:255'],
            'birthdate' => ['nullable', 'date'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:255'],
            'sex' => ['nullable', 'in:M,F'],
            'cv_file' => ['nullable', 'file', 'mimes:pdf', 'max:5120'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'dni.required' => 'El número de DNI es obligatorio.',
            'dni.digits' => 'El DNI debe contener exactamente 8 dígitos.',
            'personal_email.required' => 'El correo electrónico personal es obligatorio.',
            'personal_email.email' => 'Ingrese un correo electrónico personal válido.',
            'personal_email.unique' => 'Este correo personal ya ha sido registrado por otro usuario.',
            'names.required' => 'Los nombres completos son obligatorios.',
            'cv_file.mimes' => 'El currículum vítae debe ser un documento en formato PDF.',
            'cv_file.max' => 'El archivo de CV no debe exceder los 5MB.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
        ]);

        // Process optional CV upload
        $cvPath = $user->cv_file;
        if ($request->hasFile('cv_file')) {
            if ($user->cv_file && Storage::disk('public')->exists($user->cv_file)) {
                Storage::disk('public')->delete($user->cv_file);
            }
            $cvPath = $request->file('cv_file')->store('users/cvs', 'public');
        }

        $updateData = [
            'personal_email' => $request->personal_email,
            'names' => $request->names ?: $user->names,
            'password' => Hash::make($request->password),
            'cv_file' => $cvPath,
            'is_active' => true,
        ];

        if ($request->filled('birthdate')) {
            $updateData['birthdate'] = $request->birthdate;
        }

        if ($request->filled('phone')) {
            $updateData['phone'] = $request->phone;
        }

        if ($request->filled('address')) {
            $updateData['address'] = $request->address;
        }

        if ($request->filled('sex')) {
            $updateData['sex'] = $request->sex;
        }

        $user->update($updateData);

        // Ensure Spatie role assignment
        $roleName = $user->role ?: 'Estudiante';
        if (! $user->roles()->exists()) {
            $user->assignRole($roleName);
        }

        event(new Registered($user));

        Auth::login($user);

        if ($user->isStudentOrApplicant()) {
            return redirect(route('biblioteca.index', absolute: false))
                ->with('success', '¡Registro completado exitosamente! Bienvenido(a) al IESTP Francisco Vigo Caballero.');
        }

        return redirect(route('admin.dashboard.index', absolute: false))
            ->with('success', '¡Registro completado exitosamente!');
    }
}
