@extends('layouts.adminlayouts')

@section('title', 'Kelola User')

@section('content')

<div class="max-w-7xl mx-auto">

    
    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Kelola User
        </h1>
        <p class="text-white/80 mt-2">
            Manajemen akun pengguna aplikasi
        </p>
    </div>


    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">
                Daftar User
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Nama</th>
                        <th class="p-4 text-left">Username</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Role</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users as $user)

                    <tr class="border-b hover:bg-gray-50">

                        <!-- NAMA -->
                        <td class="p-4 font-semibold">
                            {{ $user->nama_lengkap ?? '-' }}
                        </td>

                        <!-- USERNAME -->
                        <td class="p-4">
                            {{ $user->username ?? '-' }}
                        </td>

                        <!-- EMAIL -->
                        <td class="p-4">
                            {{ $user->email }}
                        </td>

                        <!-- ROLE -->
                        <td class="p-4">
    @if($user->is_admin == 1)

        <span class="px-3 py-1 text-xs rounded-full bg-red-100 text-red-700">
            Admin
        </span>

    @else

        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
            User
        </span>

    @endif
</td>

                        <!-- AKSI -->
                        <td class="p-4">

    @if($user->is_admin == 1)

        <span class="text-gray-500 text-xs">
            Admin tidak bisa dihapus
        </span>

    @else

        <form action="{{ route('admin.user.delete', $user->id) }}" method="POST"
              onsubmit="return confirm('Yakin ingin menghapus akun ini?')">

            @csrf
            @method('DELETE')

            <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs">
                Hapus
            </button>

        </form>

    @endif

</td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="5" class="p-8 text-center text-gray-500">
                            Belum ada user
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>



@endsection