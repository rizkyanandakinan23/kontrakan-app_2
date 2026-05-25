@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Admin Panel
        </h1>

        <p class="text-white/80 mt-2">
            Kelola sistem kontrakan, kamar, dan pengguna
        </p>

    </div>

    <!-- STATS -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">

        <!-- USER -->
        <div class="bg-white/95 backdrop-blur rounded-3xl shadow-2xl p-7 border border-white/30">

            <div class="flex items-center justify-between mb-4">

                <div>

                    <p class="text-gray-500 text-sm">
                        Total User
                    </p>

                    <h2 class="text-4xl font-bold text-blue-600 mt-2">
                        {{ $totalUser ?? 0 }}
                    </h2>

                </div>

                <div class="w-16 h-16 rounded-2xl bg-blue-100 flex items-center justify-center text-3xl">

                    👤

                </div>

            </div>

        </div>

        <!-- KAMAR -->
        <div class="bg-white/95 backdrop-blur rounded-3xl shadow-2xl p-7 border border-white/30">

            <div class="flex items-center justify-between mb-4">

                <div>

                    <p class="text-gray-500 text-sm">
                        Total Kamar
                    </p>

                    <h2 class="text-4xl font-bold text-green-600 mt-2">
                        {{ $totalKamar ?? 0 }}
                    </h2>

                </div>

                <div class="w-16 h-16 rounded-2xl bg-green-100 flex items-center justify-center text-3xl">

                    🏠

                </div>

            </div>

        </div>

        <!-- TERISI -->
        <div class="bg-white/95 backdrop-blur rounded-3xl shadow-2xl p-7 border border-white/30">

            <div class="flex items-center justify-between mb-4">

                <div>

                    <p class="text-gray-500 text-sm">
                        Kamar Terisi
                    </p>

                    <h2 class="text-4xl font-bold text-red-600 mt-2">
                        {{ $kamarTerisi ?? 0 }}
                    </h2>

                </div>

                <div class="w-16 h-16 rounded-2xl bg-red-100 flex items-center justify-center text-3xl">

                    🔥

                </div>

            </div>

        </div>

    </div>

    <!-- ACTION -->
    <div class="flex flex-wrap gap-4 mb-10">

        <a
            href="{{ route('admin.kamar.index') }}"
            class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-2xl font-semibold shadow-xl transition"
        >
            Kelola Kamar
        </a>

        <a
            href="{{ route('profile.edit') }}"
            class="bg-gray-700 hover:bg-gray-800 text-white px-6 py-3 rounded-2xl font-semibold shadow-xl transition"
        >
            Pengaturan Profile
        </a>

        <a
            href="{{ route('home') }}"
            class="bg-white hover:bg-gray-100 text-gray-700 px-6 py-3 rounded-2xl font-semibold shadow-xl transition"
        >
            Kembali ke Website
        </a>

    </div>

    <!-- USER TABLE -->
    <div class="bg-white/95 backdrop-blur rounded-3xl shadow-2xl overflow-hidden border border-white/30">

        <!-- TITLE -->
        <div class="px-6 py-5 border-b bg-white">

            <h2 class="text-xl font-bold text-gray-800">
                User Terbaru
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Daftar pengguna terbaru pada sistem
            </p>

        </div>

        <!-- TABLE -->
        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-amber-600 text-white">

                    <tr>

                        <th class="p-4 text-left">
                            Nama Lengkap
                        </th>

                        <th class="p-4 text-left">
                            Username
                        </th>

                        <th class="p-4 text-left">
                            Email
                        </th>

                        <th class="p-4 text-left">
                            Role
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($users ?? [] as $user)

                        <tr class="border-b hover:bg-gray-50 transition">

                            <!-- NAMA -->
                            <td class="p-4 font-medium text-gray-700">

                                {{ $user->nama_lengkap ?? '-' }}

                            </td>

                            <!-- USERNAME -->
                            <td class="p-4 text-gray-600">

                                {{ $user->username ?? '-' }}

                            </td>

                            <!-- EMAIL -->
                            <td class="p-4 text-gray-600">

                                {{ $user->email }}

                            </td>

                            <!-- ROLE -->
                            <td class="p-4">

                                @if($user->is_admin)

                                    <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-semibold">

                                        Admin

                                    </span>

                                @else

                                    <span class="bg-gray-100 text-gray-700 px-4 py-1 rounded-full text-sm font-semibold">

                                        User

                                    </span>

                                @endif

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4" class="p-8 text-center text-gray-500">

                                Belum ada user terdaftar

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection