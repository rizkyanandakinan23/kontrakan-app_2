@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8 text-center">

        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Profile Saya
        </h1>

        <p class="text-white/80 mt-2">
            Kelola informasi akun dan keamanan akun Anda
        </p>

    </div>

    <!-- PROFILE CARD -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- TOP PROFILE -->
        <div class="bg-gray-100 border-b p-8 flex items-center gap-5">

            <!-- AVATAR -->
            <div class="w-20 h-20 rounded-full bg-amber-700 text-white flex items-center justify-center text-3xl font-bold shadow-lg">
                {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
            </div>

            <!-- INFO -->
            <div>

                <h2 class="text-2xl font-bold text-gray-800">
                    {{ auth()->user()->nama_lengkap }}
                </h2>

                <p class="text-gray-500">
                    {{ auth()->user()->email }}
                </p>

                <p class="text-sm text-amber-700 mt-1">
                    @if(auth()->user()->is_admin)
                        Administrator
                    @else
                        User
                    @endif
                </p>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="p-8 space-y-8">

            <!-- UPDATE PROFILE -->
            <div class="bg-gray-50 border rounded-2xl p-6 shadow-sm">

                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    Informasi Profile
                </h3>

                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>

            </div>

            <!-- UPDATE PASSWORD -->
            <div class="bg-gray-50 border rounded-2xl p-6 shadow-sm">

                <h3 class="text-xl font-bold text-gray-800 mb-4">
                    Ubah Password
                </h3>

                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>

            </div>

            <!-- DELETE ACCOUNT -->
            <div class="bg-red-50 border border-red-200 rounded-2xl p-6 shadow-sm">

                <h3 class="text-xl font-bold text-red-600 mb-4">
                    Hapus Akun
                </h3>

                <p class="text-sm text-red-500 mb-5">
                    Setelah akun dihapus, seluruh data akun tidak dapat dikembalikan.
                </p>

                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>

            </div>

        </div>

    </div>

</div>

@endsection