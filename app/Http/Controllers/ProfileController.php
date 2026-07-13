<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * DISPLAY PROFILE PAGE
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * UPDATE PROFILE
     */
public function update(ProfileUpdateRequest $request): RedirectResponse
{
    $validated = $request->validated();

    $user = $request->user();

    $data = [
        'nama_lengkap' => $validated['nama_lengkap'],
        'username'     => $validated['username'],
        'no_telp'      => $validated['no_telp'],
    ];

    if ($request->hasFile('foto')) {

        if ($user->foto) {
            \Storage::disk('public')->delete($user->foto);
        }

        $data['foto'] = $request->file('foto')
            ->store('profile', 'public');
    }

    $user->update($data);

    return Redirect::route('profile.edit')
        ->with('status', 'profile-updated');
}

    /**
     * DELETE ACCOUNT
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}