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

    'username' => [
        'required',
        'string',
        'max:255',
        'unique:users'
    ],

    'email' => [
        'required',
        'string',
        'email',
        'max:255',
        'unique:users'
    ],

    'no_telp' => [
        'required',
        'string',
        'regex:/^08[0-9]{8,13}$/',
    ],

    'alamat' => [
        'required',
        'string'
    ],

    'foto' => [
        'nullable',
        'image',
        'mimes:jpg,jpeg,png',
        'max:2048'
    ],

    'password' => [
        'required',
        'confirmed',
        Rules\Password::defaults()
    ],

], [

    'no_telp.regex' =>
        'Nomor telepon harus diawali 08 dan hanya berisi angka.',

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

    $user->foto = $request->file('foto')
        ->store('profile', 'public');

    $user->save();
}

       event(new Registered($user));

return redirect()
    ->route('login')
    ->with('success', 'Registrasi berhasil. Silakan login menggunakan akun Anda.');
    }
}