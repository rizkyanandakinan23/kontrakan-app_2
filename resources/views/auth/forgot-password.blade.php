@extends('layouts.app')

@section('title', 'Forgot Password')

@section('content')

<div class="max-w-md mx-auto bg-white p-8 rounded-2xl shadow mt-10">

    <h2 class="text-2xl font-bold text-center text-amber-700 mb-6">
        Lupa kata sandi
    </h2>

    <div class="mb-4 text-sm text-gray-600">
        Lupa kata sandi? Masukkan alamat email yang terdaftar pada akun Anda.
         Kami akan mengirimkan tautan untuk mengatur ulang kata sandi agar Anda dapat kembali mengakses akun.
    </div>

    <!-- Session Status -->
    <x-auth-session-status
        class="mb-4"
        :status="session('status')"
    />

    <form method="POST" action="{{ route('password.email') }}">

        @csrf

        <!-- Email Address -->
        <div class="mb-6">

            <label
                for="email"
                class="block text-sm font-medium text-gray-700"
            >
                Email
            </label>

            <input
                id="email"
                class="block mt-1 w-full rounded-lg border-gray-300 shadow-sm"
                type="email"
                name="email"
                value="{{ old('email') }}"
                required
                autofocus
                placeholder="Masukkan email Anda"
            >

            <x-input-error
                :messages="$errors->get('email')"
                class="mt-2"
            />

        </div>

        <!-- Button -->
        <div class="flex items-center justify-end">

            <button
                type="submit"
                class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-2 rounded-lg"
            >
                Email Password Reset Link
            </button>

        </div>

    </form>

</div>

@endsection