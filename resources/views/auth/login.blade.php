@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">

        <h2 class="text-3xl font-bold text-center text-amber-700 mb-6">
            Login
        </h2>

        <!-- Session Status -->
        <x-auth-session-status
            class="mb-4"
            :status="session('status')"
        />

        <form method="POST" action="{{ route('login') }}">

            @csrf

            <!-- Email -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Email
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-2"
                />

            </div>

            <!-- Password -->
            <div class="mb-4">

                <label class="block text-gray-700 mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    required
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200"
                >

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-2"
                />

            </div>

            <!-- Remember -->
            <div class="flex items-center mb-4">

                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded border-gray-300 text-amber-700"
                >

                <label
                    for="remember_me"
                    class="ml-2 text-sm text-gray-600"
                >
                    Remember me
                </label>

            </div>

            <!-- Forgot Password -->
            <div class="mb-4 text-right">

                @if (Route::has('password.request'))

                    <a
                        href="{{ route('password.request') }}"
                        class="text-sm text-amber-700 hover:underline"
                    >
                        Forgot your password?
                    </a>

                @endif

            </div>

            <!-- Button -->
            <button
                type="submit"
                class="w-full bg-amber-700 hover:bg-amber-800 text-white py-3 rounded-lg font-semibold"
            >
                Login
            </button>

        </form>

        <!-- Register -->
        <p class="text-center text-sm text-gray-600 mt-6">

            Belum punya akun?

            <a
                href="{{ route('register') }}"
                class="text-amber-700 hover:underline"
            >
                Daftar
            </a>

        </p>

    </div>

</div>

@endsection