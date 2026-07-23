@extends('layouts.app')

@section('title', 'Profile')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8 text-center">

        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Profil Saya
        </h1>

        <p class="text-white/80 mt-2">
            Kelola informasi akun dan keamanan akun Anda
        </p>

    </div>

    <!-- PROFILE CARD -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- TOP PROFILE -->
        <div class="bg-gradient-to-r from-amber-600 to-amber-700 p-8 flex items-center gap-6">

            <!-- AVATAR -->
            @if(auth()->user()->foto)

    <img
        src="{{ asset('storage/' . auth()->user()->foto) }}"
        class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-xl"
    >

@else

    <div class="w-24 h-24 rounded-full bg-white text-amber-700 flex items-center justify-center text-4xl font-bold shadow-xl">

        {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}

    </div>

@endif


            <!-- INFO -->
            <div class="text-white">

                <h2 class="text-3xl font-bold">

                    {{ auth()->user()->nama_lengkap }}

                </h2>

               <p class="text-white/90 mt-1">

                    {{ auth()->user()->username }}

                </p>

                <p class="text-white/90 mt-1">

                    {{ auth()->user()->email }}

                </p>

                <div class="mt-3 flex flex-wrap gap-3">

                    <span class="bg-white/20 backdrop-blur px-4 py-1 rounded-full text-sm font-semibold">

                        @if(auth()->user()->is_admin)

                            Administrator

                        @else

                            User

                        @endif

                    </span>


                </div>

            </div>

        </div>

        <!-- CONTENT -->
        <div class="p-8 space-y-8">

            <!-- UPDATE PROFILE -->
            <div class="bg-gray-50 border border-gray-200 rounded-3xl p-6 shadow-sm">

                <div class="mb-6">

                    <h3 class="text-2xl font-bold text-gray-800">
                        Informasi Profil
                    </h3>

                    <p class="text-gray-500 mt-1 text-sm">
                        Perbarui nama lengkap dan username akun Anda.
                    </p>

                </div>

                <div class="max-w-2xl">

                    <form 
    method="POST"
    action="{{ route('profile.update') }}"
    enctype="multipart/form-data"
    class="space-y-6"
>

                        @csrf
                        @method('PATCH')

                        <!-- NAMA LENGKAP -->

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap
                            </label>

                            <input
                                type="text"
                                name="nama_lengkap"
                                value="{{ old('nama_lengkap', auth()->user()->nama_lengkap) }}"
                                required
                                class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
                            >

                            @error('nama_lengkap')

                                <p class="text-red-500 text-sm mt-2">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        <!-- USERNAME -->

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Username
                            </label>

                            <input
                                type="text"
                                name="username"
                                value="{{ old('username', auth()->user()->username) }}"
                                required
                                class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
                            >

                            @error('username')

                                <p class="text-red-500 text-sm mt-2">
                                    {{ $message }}
                                </p>

                            @enderror

                        </div>

                        <!-- WHATSAPP -->
<div>

    <label class="block text-sm font-semibold text-gray-700 mb-2">
        Nomor Telepon
    </label>

    <input
        type="text"
        name="no_telp"
        value="{{ old('whatsapp', auth()->user()->no_telp) }}"
        required
        class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
        placeholder="Contoh: 081234567890"
    >

    @error('no_telp')

        <p class="text-red-500 text-sm mt-2">
            {{ $message }}
        </p>

    @enderror

</div>

                        <!-- EMAIL -->

                        <div>

                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>

                            <input
                                type="email"
                                value="{{ auth()->user()->email }}"
                                disabled
                                class="w-full border border-gray-200 bg-gray-100 text-gray-500 rounded-2xl px-4 py-3 cursor-not-allowed"
                            >

                            <p class="text-xs text-gray-400 mt-2">
                                Email tidak dapat diubah.
                            </p>

                        </div>

                        <!-- INPUT -->

        <div class="flex-1">

            <input
                type="file"
                name="foto"
                accept="image/*"
                class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-white"
            >

            <p class="text-xs text-gray-400 mt-2">
                Format JPG, JPEG, PNG maksimal 2MB
            </p>

        </div>

    </div>

    @error('foto')

        <p class="text-red-500 text-sm mt-2">
            {{ $message }}
        </p>

    @enderror

</div>

                        <!-- BUTTON -->

                        <div class="pt-2">

                            <button
                                type="submit"
                                class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-3 rounded-2xl font-semibold shadow-lg transition"
                            >
                                Simpan Perubahan
                            </button>

                        </div>

                        <!-- SUCCESS -->

                        @if (session('status') === 'profile-updated')

                            <div class="bg-green-100 border border-green-200 text-green-700 px-4 py-3 rounded-2xl">

                                Profile berhasil diperbarui.

                            </div>

                        @endif

                    </form>

                </div>

            </div>

            <!-- UPDATE PASSWORD -->
            <div class="bg-gray-50 border border-gray-200 rounded-3xl p-6 shadow-sm">

                <div class="mb-6">

                    <h3 class="text-2xl font-bold text-gray-800">
                        Ubah Password
                    </h3>

                    <p class="text-gray-500 mt-1 text-sm">
                        Gunakan password yang kuat untuk menjaga keamanan akun.
                    </p>

                </div>

                <div class="max-w-2xl">

                    @include('profile.partials.update-password-form')

                </div>

            </div>

            <!-- DELETE ACCOUNT -->
            <div class="bg-red-50 border border-red-200 rounded-3xl p-6 shadow-sm">

                <div class="mb-6">

                    <h3 class="text-2xl font-bold text-red-600">
                        Hapus Akun
                    </h3>

                    <p class="text-sm text-red-500 mt-2 leading-relaxed">
                        Setelah akun dihapus, seluruh data seperti booking,
                        review, dan aktivitas lainnya tidak dapat dikembalikan.
                    </p>

                </div>

                <div class="max-w-2xl">

                    @include('profile.partials.delete-user-form')

                </div>

            </div>

        </div>

    </div>

</div>

@endsection