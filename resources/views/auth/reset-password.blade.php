@extends('layouts.app')

@section('title', 'Reset Password')

@section('content')

<div class="min-h-screen flex items-center justify-center">

    <div class="w-full max-w-md bg-white shadow-xl rounded-2xl p-8">

        <h2 class="text-3xl font-bold text-center text-amber-700 mb-6">
            Reset Password
        </h2>

        <form method="POST" action="{{ route('password.store') }}">
            @csrf

            <!-- TOKEN -->
            <input type="hidden" name="token" value="{{ request()->route('token') }}">

            <!-- EMAIL -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>

                <input type="email"
                       name="email"
                       value="{{ old('email', request()->email) }}"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200">

                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- PASSWORD -->
            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Password Baru</label>

                <input type="password"
                       name="password"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200">

                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- CONFIRM -->
            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Konfirmasi Password</label>

                <input type="password"
                       name="password_confirmation"
                       required
                       class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring focus:ring-amber-200">

                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- BUTTON -->
            <button type="submit"
                class="w-full bg-amber-700 hover:bg-amber-800 text-white py-3 rounded-lg font-semibold">
                Reset Password
            </button>

        </form>

    </div>

</div>

@endsection