@extends('layouts.adminlayouts')

@section('title', 'Dashboard')

@section('content')

<!-- STATISTICS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">

    <div class="bg-white rounded-3xl shadow p-6">
        <p class="text-gray-500">Total User</p>
        <h2 class="text-4xl font-bold text-blue-600 mt-2">
            {{ $totalUser ?? 0 }}
        </h2>
    </div>

    <div class="bg-white rounded-3xl shadow p-6">
        <p class="text-gray-500">Total Kamar</p>
        <h2 class="text-4xl font-bold text-green-600 mt-2">
            {{ $totalKamar ?? 0 }}
        </h2>
    </div>

    <div class="bg-white rounded-3xl shadow p-6">
        <p class="text-gray-500">Kamar Terisi</p>
        <h2 class="text-4xl font-bold text-red-600 mt-2">
            {{ $kamarTerisi ?? 0 }}
        </h2>
    </div>

</div>

<!-- TABLE USER -->
<div class="bg-white rounded-3xl shadow overflow-hidden">

    <div class="p-5 border-b">
        <h2 class="text-xl font-bold">User Terbaru</h2>
    </div>

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

            <tr class="border-b">
                <td class="p-4">{{ $user->nama_lengkap }}</td>
                <td class="p-4">{{ $user->username }}</td>
                <td class="p-4">{{ $user->email }}</td>
                <td class="p-4">
                    @if($user->is_admin)
                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Admin</span>
                    @else
                        <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">User</span>
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

@endsection