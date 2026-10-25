<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request, ?User $user = null): View|RedirectResponse
    {
        $authUser = $request->user();
        $targetUser = ($user && $user->exists) ? $user : $authUser;

        // Si el usuario objetivo es otro y el usuario autenticado no gestiona usuarios, restringir a su propio perfil
        if ($targetUser->id !== $authUser->id && ! $authUser->can('gestionar-usuarios')) {
            return redirect()->route('admin.profile.edit', $authUser)
                ->with('warning', 'Solo tiene acceso a la edición de su propio perfil de usuario.')
                ->with('error', 'Solo tiene acceso a la edición de su propio perfil de usuario.');
        }

        return view('profile.edit', [
            'user' => $targetUser,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ?User $user = null): RedirectResponse
    {
        $authUser = $request->user();
        $targetUser = ($user && $user->exists) ? $user : $authUser;

        if ($targetUser->id !== $authUser->id && ! $authUser->can('gestionar-usuarios')) {
            abort(403, 'No tiene permiso para modificar los datos de otro usuario.');
        }

        $validated = $request->validated();

        // Handle Photo Profile Upload
        if ($request->hasFile('photo_profile')) {
            if ($targetUser->photo_profile && Storage::disk('public')->exists($targetUser->photo_profile)) {
                Storage::disk('public')->delete($targetUser->photo_profile);
            }
            $validated['photo_profile'] = $request->file('photo_profile')->store('users/photos', 'public');
        }

        // Handle CV Document Upload
        if ($request->hasFile('cv_file')) {
            if ($targetUser->cv_file && Storage::disk('public')->exists($targetUser->cv_file)) {
                Storage::disk('public')->delete($targetUser->cv_file);
            }
            $validated['cv_file'] = $request->file('cv_file')->store('users/cvs', 'public');
        }

        $targetUser->fill($validated);

        if ($targetUser->isDirty('email')) {
            $targetUser->email_verified_at = null;
        }

        $targetUser->save();

        return Redirect::route('admin.profile.edit', $targetUser)
            ->with('status', 'profile-updated')
            ->with('success', '¡Los datos del perfil se actualizaron exitosamente!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request, ?User $user = null): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $authUser = $request->user();
        $targetUser = ($user && $user->exists) ? $user : $authUser;

        if ($targetUser->id !== $authUser->id && ! $authUser->can('gestionar-usuarios')) {
            abort(403, 'No tiene permiso para eliminar la cuenta de otro usuario.');
        }

        $isSelf = $targetUser->id === $authUser->id;

        if ($targetUser->photo_profile && Storage::disk('public')->exists($targetUser->photo_profile)) {
            Storage::disk('public')->delete($targetUser->photo_profile);
        }

        if ($targetUser->cv_file && Storage::disk('public')->exists($targetUser->cv_file)) {
            Storage::disk('public')->delete($targetUser->cv_file);
        }

        if ($isSelf) {
            Auth::logout();
            $targetUser->delete();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/');
        }

        $targetUser->delete();

        return Redirect::route('admin.partners.index')->with('success', 'El usuario ha sido eliminado correctamente.');
    }
}
