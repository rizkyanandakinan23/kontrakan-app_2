@extends('layouts.app')

@section('title', 'Daftar Kamar')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">

        <h1 class="text-4xl font-bold text-white mb-3">
            Daftar Kamar
        </h1>

        <p class="text-white/90">
            Pilih kamar kontrakan yang nyaman dan sesuai kebutuhan Anda.
        </p>

    </div>

    <!-- CARD KAMAR -->
    <div class="grid md:grid-cols-3 gap-8">

        @forelse ($kamars as $kamar)

        <div class="bg-white rounded-3xl overflow-hidden shadow-lg hover:scale-[1.02] transition">

            <!-- IMAGE -->
            <div class="relative overflow-hidden rounded-t-3xl">

                @php
                    $foto = $kamar->foto_kamar;

                    if (is_string($foto)) {
                        $decoded = json_decode($foto, true);
                        $foto = is_array($decoded) ? $decoded : [$foto];
                    }
                @endphp

                <img
    src="{{ asset('storage/' . ($foto[0] ?? 'default.jpg')) }}"
    class="w-full h-64 object-cover object-center hover:scale-110 transition duration-500"
>

                <div class="absolute top-4 right-4">

                    @if(($kamar->status ?? '') == 'terisi')

                        <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full">
                            Terisi
                        </span>

                    @elseif(($kamar->status ?? '') == 'kosong')

                        <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">
                            Tersedia
                        </span>

                    @endif

                </div>

            </div>

            <!-- CONTENT -->
            <div class="p-6">

                <h2 class="text-2xl font-bold text-gray-800 mb-2">
                    {{ $kamar->nama_kamar }}
                </h2>

                <p class="text-gray-500 text-sm mb-4 line-clamp-3">
                    {{ $kamar->deskripsi }}
                </p>

                <!-- FACILITY -->
                <div class="space-y-2 text-sm text-gray-600 mb-5">

                    @php
                        $fasilitas = $kamar->fasilitas;

                        if (is_string($fasilitas)) {
                            $decoded = json_decode($fasilitas, true);
                            $fasilitas = is_array($decoded)
                                ? $decoded
                                : [$fasilitas];
                        }
                    @endphp

                    @foreach($fasilitas ?? [] as $item)

                        <div class="flex items-center gap-2">
                            <span>✔️</span>
                            <span>{{ $item }}</span>
                        </div>

                    @endforeach

                </div>

                <!-- PRICE -->
                <div class="flex items-center justify-between">

                    <div>

                        <p class="text-sm text-gray-500">
                            Harga
                        </p>

                        <h3 class="text-2xl font-bold text-amber-700">
                            Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                        </h3>

                        <p class="text-xs text-gray-400">
                            / bulan
                        </p>

                    </div>

                    <!-- BUTTON -->
@if(($kamar->status ?? '') == 'terisi')

    <button
        disabled
        class="bg-gray-400 text-white px-5 py-3 rounded-xl font-semibold cursor-not-allowed opacity-80"
    >
        Sudah Terisi
    </button>

@elseif(($kamar->status ?? '') == 'kosong')

    <a
        href="{{ route('kamar.show', $kamar->id) }}"
        class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-3 rounded-xl font-semibold transition inline-block"
    >
        Lihat Detail
    </a>

@endif

                </div>

            </div>

        </div>

        @empty

        <div class="col-span-3">

            <div class="bg-white rounded-3xl p-10 text-center shadow-xl">

                <h2 class="text-2xl font-bold text-gray-700 mb-2">
                    Belum Ada Kamar
                </h2>

                <p class="text-gray-500">
                    Saat ini belum ada kamar yang tersedia.
                </p>

            </div>

        </div>

        @endforelse

    </div>

</div>

@include('components.maps')

@include('components.floating-chat')

@endsection