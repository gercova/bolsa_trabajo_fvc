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
    public function edit(Request $request, ?User $user = null): View
    {
        $targetUser = ($user && $user->exists) ? $user : $request->user();

        return view('profile.edit', [
            'user' => $targetUser,
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request, ?User $user = null): RedirectResponse
    {
        $targetUser = ($user && $user->exists) ? $user : $request->user();

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

        $targetUser = ($user && $user->exists) ? $user : $request->user();
        $isSelf = $targetUser->id === $request->user()->id;

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
