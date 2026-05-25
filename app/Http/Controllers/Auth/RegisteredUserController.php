<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Show register page
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle register request
     */
    public function store(Request $request): RedirectResponse
    {
        // VALIDASI
        $request->validate([
            'nama_lengkap' => ['required', 'string', 'max:255'],
            'username'     => ['required', 'string', 'max:255', 'unique:users'],
            'email'        => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'no_telp'      => ['nullable', 'string', 'max:20'],
            'alamat'       => ['nullable', 'string'],
            'foto'         => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
            'password'     => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // BUAT USER
        $user = User::create([
            'nama_lengkap' => $request->nama_lengkap,
            'username'     => $request->username,
            'email'        => $request->email,
            'no_telp'      => $request->no_telp,
            'alamat'       => $request->alamat,
            'password'     => Hash::make($request->password),
        ]);

        // UPLOAD FOTO (JIKA ADA)
        if ($request->hasFile('foto')) {

            $file = $request->file('foto');
            $filename = time().'_'.$file->getClientOriginalName();

            $file->move(public_path('images/user'), $filename);

            $user->foto = $filename;
            $user->save();
        }

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}