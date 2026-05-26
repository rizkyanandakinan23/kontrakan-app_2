@extends('layouts.app')

@section('title', 'Admin Panel')

@section('content')

<div class="max-w-7xl mx-auto flex gap-6">

    <!-- ========================= -->
    <!-- SIDEBAR -->
    <!-- ========================= -->
    <aside class="w-72 bg-white/95 backdrop-blur rounded-3xl shadow-2xl p-6 h-fit">

        <h2 class="text-xl font-bold text-amber-700 mb-6">
            Admin Menu
        </h2>

        <nav class="space-y-3 text-sm font-medium">

            <!-- DASHBOARD -->
            <a href="{{ route('admin.panel') }}"
               class="block px-4 py-3 rounded-xl bg-amber-100 text-amber-800 font-semibold">
                📊 Dashboard
            </a>

            <!-- KELOLA KAMAR -->
            <a href="{{ route('admin.kamar.index') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
                🏠 Kelola Kamar
            </a>

            <!-- KELOLA USER -->
            <a href="{{ route('admin.user.index') }}"
   class="block px-4 py-2 rounded-lg hover:bg-amber-100 text-gray-700 font-semibold">
    👤 Kelola User
</a>

            <!-- KELOLA BOOKING -->
            <a href="{{ route('admin.booking.index') }}"
   class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
    📑 Kelola Booking
</a>

           <!-- KELOLA REVIEW -->
<a href="{{ route('admin.review.index') }}"
   class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
    ⭐ Kelola Review
</a>

            <hr class="my-4">

            <!-- BACK -->
            <a href="{{ route('home') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
                ↩ Kembali ke Website
            </a>

        </nav>

    </aside>

    <!-- ========================= -->
    <!-- MAIN CONTENT -->
    <!-- ========================= -->
    <main class="flex-1 space-y-6">

        <!-- HEADER -->
        <div class="mb-2">
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">
                Admin Dashboard
            </h1>

            <p class="text-white/80 mt-1">
                Monitoring sistem kontrakan secara real-time
            </p>
        </div>

        <!-- ========================= -->
        <!-- STATISTICS -->
        <!-- ========================= -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <!-- USER -->
            <div class="bg-white rounded-3xl shadow-xl p-6">
                <p class="text-gray-500">Total User</p>
                <h2 class="text-4xl font-bold text-blue-600 mt-2">
                    {{ $totalUser ?? 0 }}
                </h2>
            </div>

            <!-- KAMAR -->
            <div class="bg-white rounded-3xl shadow-xl p-6">
                <p class="text-gray-500">Total Kamar</p>
                <h2 class="text-4xl font-bold text-green-600 mt-2">
                    {{ $totalKamar ?? 0 }}
                </h2>
            </div>

            <!-- TERISI -->
            <div class="bg-white rounded-3xl shadow-xl p-6">
                <p class="text-gray-500">Kamar Terisi</p>
                <h2 class="text-4xl font-bold text-red-600 mt-2">
                    {{ $kamarTerisi ?? 0 }}
                </h2>
            </div>

        </div>

        <!-- ========================= -->
        <!-- RECENT USERS TABLE -->
        <!-- ========================= -->
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

            <div class="p-5 border-b">
                <h2 class="text-xl font-bold text-gray-800">
                    User Terbaru
                </h2>
            </div>

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-amber-600 text-white">
                        <tr>
                            <th class="p-4 text-left">Nama</th>
                            <th class="p-4 text-left">Username</th>
                            <th class="p-4 text-left">Email</th>
                            <th class="p-4 text-left">Role</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($users ?? [] as $user)

                        <tr class="border-b hover:bg-gray-50">

                            <td class="p-4 font-medium">
                                {{ $user->nama_lengkap }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $user->username }}
                            </td>

                            <td class="p-4 text-gray-600">
                                {{ $user->email }}
                            </td>

                            <td class="p-4">

                                @if($user->is_admin)

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">
                                        Admin
                                    </span>

                                @else

                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                                        User
                                    </span>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="4" class="p-6 text-center text-gray-500">
                                Belum ada user
                            </td>
                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

@endsection