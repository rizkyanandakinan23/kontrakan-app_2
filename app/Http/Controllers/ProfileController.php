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
    $validated = $request->validate([

        'nama_lengkap' => [
            'required',
            'string',
            'max:255',
        ],

        'username' => [
            'required',
            'string',
            'max:255',
            'unique:users,username,' . auth()->id(),
        ],

        'foto' => [
            'nullable',
            'image',
            'mimes:jpg,jpeg,png',
            'max:2048',
        ],

    ]);

    $user = $request->user();

    $data = [
        'nama_lengkap' => $validated['nama_lengkap'],
        'username' => $validated['username'],
    ];

    /*
    |--------------------------------------------------------------------------
    | UPLOAD FOTO PROFILE
    |--------------------------------------------------------------------------
    */

    if ($request->hasFile('foto')) {

        // hapus foto lama
        if ($user->foto) {

            \Storage::disk('public')->delete($user->foto);
        }

        // upload foto baru
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