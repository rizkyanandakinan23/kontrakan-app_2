@extends('layouts.adminlayouts')

@section('title', 'Kelola Kamar')

@section('content')


   
<div class="max-w-7xl mx-auto">
       

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">

        <div>

            <h1 class="text-4xl font-bold text-white drop-shadow-lg">
                Kelola Kamar
            </h1>

            <p class="text-white/80 mt-2">
                Tambah, edit, dan hapus data kamar kontrakan
            </p>

        </div>

        <a
            href="{{ route('admin.kamar.create') }}"
            class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-2xl font-semibold shadow-xl transition"
        >
            + Tambah Kamar
        </a>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <!-- HEAD -->
                <thead class="bg-amber-600 text-white">

                    <tr>

                        <th class="p-4 text-left">Foto</th>
                        <th class="p-4 text-left">Nama Kamar</th>
                        <th class="p-4 text-left">Harga</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Fasilitas</th>
                        <th class="p-4 text-center">Aksi</th>

                    </tr>

                </thead>

                <!-- BODY -->
                <tbody>

                    @forelse($kamars as $kamar)

                        @php
                            $fotoUtama = $kamar->foto_kamar[0] ?? null;
                        @endphp

                        <tr class="border-b hover:bg-gray-50 transition">

                            <!-- FOTO -->
                            <td class="p-4">

                                @if($fotoUtama)

                                    <img
                                        src="{{ asset('storage/' . $fotoUtama) }}"
                                        class="w-24 h-24 object-cover rounded-2xl border"
                                    >

                                @else

                                    <div class="w-24 h-24 bg-gray-200 rounded-2xl flex items-center justify-center text-gray-500 text-sm">
                                        No Image
                                    </div>

                                @endif

                            </td>

                            <!-- NAMA -->
                            <td class="p-4">

                                <h2 class="font-bold text-gray-800 text-lg">
                                    {{ $kamar->nama_kamar }}
                                </h2>

                                <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                    {{ $kamar->deskripsi }}
                                </p>

                            </td>

                            <!-- HARGA -->
                            <td class="p-4 font-semibold text-amber-700">

                                Rp {{ number_format($kamar->harga, 0, ',', '.') }}

                            </td>

                            <!-- STATUS -->
                            <td class="p-4">

                                @if($kamar->status == 'terisi')

                                    <span class="bg-red-100 text-red-700 px-4 py-1 rounded-full text-sm font-semibold">
                                        Terisi
                                    </span>

                                @else

                                    <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-semibold">
                                        Tersedia
                                    </span>

                                @endif

                            </td>

                            <!-- FASILITAS -->
<td class="p-4">

    @php

        $fasilitas = $kamar->fasilitas;

        // jika string JSON
        if (is_string($fasilitas)) {

            $decoded = json_decode($fasilitas, true);

            if (json_last_error() === JSON_ERROR_NONE) {

                $fasilitas = $decoded;

            } else {

                // kalau string biasa
                $fasilitas = [$fasilitas];

            }

        }

        // kalau null
        if (!is_array($fasilitas)) {
            $fasilitas = [];
        }

    @endphp

    <div class="flex flex-wrap gap-2">

        @foreach($fasilitas as $item)

            <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                {{ $item }}
            </span>

        @endforeach

    </div>

</td>

                            </td>

                            <!-- AKSI -->
                            <td class="p-4">

                                <div class="flex items-center justify-center gap-3">

                                    <a
                                        href="{{ route('admin.kamar.edit', $kamar->id) }}"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition"
                                    >
                                        Edit
                                    </a>

                                    <form
                                        action="{{ route('admin.kamar.destroy', $kamar->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin ingin menghapus kamar ini?')"
                                    >

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm font-semibold transition"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="p-10 text-center text-gray-500">
                                Belum ada data kamar
                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

 

@endsection