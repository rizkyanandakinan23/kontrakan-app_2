@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">

        <h2 class="text-3xl font-bold text-center text-amber-700 mb-6">
            Daftarkan akun anda
        </h2>

        <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data">

            @csrf

            <!-- NAMA LENGKAP -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Nama Lengkap
                </label>

                <input
                    type="text"
                    name="nama_lengkap"
                    value="{{ old('nama_lengkap') }}"
                    required
                    placeholder="Masukkan nama lengkap Anda"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >

                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />

            </div>

            <!-- USERNAME -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Nama Pengguna (Username)
                </label>

                <input
                    type="text"
                    name="username"
                    value="{{ old('username') }}"
                    required
                    placeholder="Masukkan username Anda"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >

                <x-input-error :messages="$errors->get('username')" class="mt-2" />

            </div>

            <!-- EMAIL -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    placeholder="Masukkan email Anda"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >

                <x-input-error :messages="$errors->get('email')" class="mt-2" />

            </div>

            <!-- NO TELEPON -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    No Telepon
                </label>

                <input
    type="tel"
    name="no_telp"
    value="{{ old('no_telp') }}"
    required
    maxlength="15"
    inputmode="numeric"
    pattern="^(08[0-9]{8,13}|\+628[0-9]{8,13})$"
    placeholder="Contoh: 081234567890"
    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
>

                <x-input-error :messages="$errors->get('no_telp')" class="mt-2" />

            </div>

            <!-- ALAMAT -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Alamat
                </label>

                <textarea
                    name="alamat"
                    rows="3"
                    required
                    placeholder="Masukkan alamat Anda"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >{{ old('alamat') }}</textarea>

                <x-input-error :messages="$errors->get('alamat')" class="mt-2" />

            </div>

            <!-- FOTO -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Foto Profil
                </label>

                <input
                    type="file"
                    name="foto"
                    accept="image/*"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 bg-white"
                >

                <x-input-error :messages="$errors->get('foto')" class="mt-2" />

            </div>

            <!-- PASSWORD -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    kata sandi
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    placeholder="Minimal 8 karakter"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >

                <x-input-error :messages="$errors->get('password')" class="mt-2" />

            </div>

            <!-- CONFIRM PASSWORD -->
            <div class="mb-6">

                <label class="block text-gray-700 mb-2">
                    Konfirmasi kata sandi
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    required
                    placeholder="ulangi kata sandi Anda"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="w-full bg-amber-700 hover:bg-amber-800 text-white py-3 rounded-lg font-semibold"
            >
                Daftarkan
            </button>

        </form>

        <!-- LOGIN LINK -->
        <p class="text-center text-sm text-gray-600 mt-6">

            Sudah punya akun?

            <a href="{{ route('login') }}" class="text-amber-700 hover:underline">
                Login
            </a>

        </p>

    </div>

</div>

@endsection