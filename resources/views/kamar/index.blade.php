@extends('layouts.app')

@section('title', 'Daftar Kontrakan')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">

        <h1 class="text-4xl font-bold text-white mb-3">
            Daftar Kontrakan
        </h1>

        <p class="text-white/90">
            Pilih kontrakan yang nyaman dan sesuai kebutuhan Anda.
        </p>

    </div>

      <!-- SEARCH -->
    @include('components.search-filter')

    <div class="bg-white rounded-2xl p-6 mb-8 shadow-lg">

    <form method="GET" action="{{ route('kamar.index') }}">

        <!-- Pertahankan search & sort -->
        <input type="hidden" name="search" value="{{ request('search') }}">
        <input type="hidden" name="sort" value="{{ request('sort') }}">

        <div class="flex flex-wrap items-end gap-4">

            <div>
                <label class="block text-sm font-semibold mb-2">
                    Tanggal Check-in
                </label>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ request('tanggal', date('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    class="border rounded-xl px-4 py-3"
                >
            </div>

            <button
                type="submit"
                class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-3 rounded-xl"
            >
                Cek Ketersediaan
            </button>

        </div>

    </form>

</div>

    <!-- STATISTIK -->
    @include('components.statistik-kamar')

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
    class="max-w-full max-h-[450px] w-auto h-auto object-contain rounded-3xl shadow-lg transition duration-300 mx-auto"
>

                <div class="absolute top-4 right-4">

                   @if($kamar->status_booking == 'tersedia')

    <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">
        Tersedia
    </span>

@elseif($kamar->status_booking == 'booking')

    <span class="bg-yellow-500 text-white text-xs px-3 py-1 rounded-full">
        Sudah Dibooking
    </span>

@else

    <span class="bg-red-500 text-white text-xs px-3 py-1 rounded-full">
        Sedang Ditempati
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

                 <a href="{{ route('kamar.show', [
                    'id' => $kamar->id,
                   'tanggal' => request('tanggal')    ]) }}"
                 class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-3 rounded-xl font-semibold transition inline-block">
                Lihat Detail </a>

                </div>

            </div>

        </div>

        @empty

        <div class="col-span-3">

            <div class="bg-white rounded-3xl p-10 text-center shadow-xl">

                <h2 class="text-2xl font-bold text-gray-700 mb-2">
                    Belum Ada Kontrakan
                </h2>

                <p class="text-gray-500">
                    Saat ini belum ada Kontrakan yang tersedia.
                </p>

            </div>

        </div>

        @endforelse

    </div>

</div>

@include('components.maps')

@include('components.floating-chat')

@endsection