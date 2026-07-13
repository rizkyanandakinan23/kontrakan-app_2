@extends('layouts.app')

@section('title', 'Beranda')

@section('content')


<div class="max-w-7xl mx-auto">

    <!-- HERO -->
    <div class="bg-white/95 rounded-3xl shadow-2xl overflow-hidden mb-12">

        <div class="grid md:grid-cols-2 gap-10 items-center p-10">

            <!-- TEXT -->
            <div>

                <span class="inline-block bg-amber-100 text-amber-700 px-4 py-2 rounded-full text-sm font-semibold mb-5">
                    Selamat Datang di Kontrakan Raden Panghulu Djaja
                </span>

                <h1 class="text-5xl font-bold text-gray-800 leading-tight mb-6">
                    Cari Kamar Kontrakan
                    <span class="text-amber-700">
                        Nyaman & Strategis
                    </span>
                </h1>

                <p class="text-gray-600 text-lg leading-relaxed mb-8">

                    Temukan kamar kontrakan terbaik dengan fasilitas lengkap,
                    lingkungan nyaman, lokasi strategis, dan harga yang terjangkau
                    untuk kebutuhan tempat tinggal Anda.

                </p>

                <div class="flex flex-wrap gap-4">

                    <a
                        href="{{ route('kamar.index') }}"
                        class="bg-amber-700 hover:bg-amber-800 text-white px-7 py-3 rounded-2xl font-semibold shadow-lg transition"
                    >
                        Lihat Kamar
                    </a>

                    @guest

                        <a
                            href="{{ route('register') }}"
                            class="border-2 border-amber-700 text-amber-700 hover:bg-amber-700 hover:text-white px-7 py-3 rounded-2xl font-semibold transition"
                        >
                            Daftar Sekarang
                        </a>

                    @endguest

                </div>

            </div>

            <!-- IMAGE -->
            <div>

                <img
                    src="{{ asset('images/IMG-20260607-WA0008.jpg') }}"
                    alt="Kamar"
                    class="rounded-3xl shadow-xl w-full h-[420px] object-cover"
                >

            </div>

        </div>

    </div>

    <!-- FITUR -->
    <div class="grid md:grid-cols-3 gap-7 mb-14">

        <!-- CARD -->
        <div class="bg-white/95 rounded-3xl shadow-xl p-7 hover:-translate-y-1 transition duration-300">

            <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl mb-5">
                🏠
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                Kamar Nyaman
            </h3>

            <p class="text-gray-600 leading-relaxed">
                Kamar bersih, nyaman, dan cocok untuk mahasiswa maupun pekerja.
            </p>

        </div>

        <!-- CARD -->
        <div class="bg-white/95 rounded-3xl shadow-xl p-7 hover:-translate-y-1 transition duration-300">

            <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center text-3xl mb-5">
                📍
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                Lokasi Strategis
            </h3>

            <p class="text-gray-600 leading-relaxed">
                Dekat kampus, minimarket, tempat makan, dan akses transportasi.
            </p>

        </div>

        <!-- CARD -->
        <div class="bg-white/95 rounded-3xl shadow-xl p-7 hover:-translate-y-1 transition duration-300">

            <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center text-3xl mb-5">
                💰
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                Harga Terjangkau
            </h3>

            <p class="text-gray-600 leading-relaxed">
                Harga sesuai fasilitas dengan proses booking yang mudah dan cepat.
            </p>

        </div>

    </div>

<!-- FASILITAS UMUM -->
<div class="mb-14">

    <div class="bg-white/95 rounded-3xl shadow-2xl p-8">

        <div class="text-center mb-8">

            <h2 class="text-4xl font-bold text-gray-800">
                Fasilitas Umum
            </h2>

            <p class="text-gray-500 mt-2">
                Seluruh unit kontrakan mendapatkan akses fasilitas berikut.
            </p>

        </div>

        <div class="grid md:grid-cols-4 gap-6">

            <!-- WIFI -->
            <div class="text-center bg-gray-50 rounded-2xl p-6">

                <div class="text-5xl mb-3">
                    📶
                </div>

                <h3 class="font-bold text-lg">
                    WiFi
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Akses internet untuk seluruh penghuni.
                </p>

            </div>

            <!-- PARKIR -->
            <div class="text-center bg-gray-50 rounded-2xl p-6">

                <div class="text-5xl mb-3">
                    🛵
                </div>

                <h3 class="font-bold text-lg">
                    Parkir Luar
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Area parkir kendaraan yang cukup luas.
                </p>

            </div>

            <!-- MUSHOLLA -->
            <div class="text-center bg-gray-50 rounded-2xl p-6">

                <div class="text-5xl mb-3">
                    🕌
                </div>

                <h3 class="font-bold text-lg">
                    Dekat Musholla
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Musholla dapat dijangkau dengan berjalan kaki.
                </p>

            </div>

            <!-- CCTV -->
            <div class="text-center bg-gray-50 rounded-2xl p-6">

                <div class="text-5xl mb-3">
                    📹
                </div>

                <h3 class="font-bold text-lg">
                    CCTV 24 Jam
                </h3>

                <p class="text-sm text-gray-500 mt-2">
                    Area kontrakan dipantau CCTV selama 24 jam.
                </p>

            </div>

        </div>

    </div>

</div>

@php
    $tanggal = $tanggal ?? now()->toDateString();
@endphp


<!-- KAMAR TERSEDIA -->
    <div class="mb-10">

        <!-- HEADER -->
        <div class="flex items-center justify-between mb-7">

            <div>
                
                <h2 class="text-4xl font-bold text-white">
                    Rekomendasi kamar
                </h2>

                <p class="text-white/80 mt-2">
                    Pilihan kamar terbaru yang tersedia untuk Anda.
                </p>

            </div>

            <a
    href="{{ route('kamar.index') }}"
    class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-3 rounded-2xl transition font-semibold shadow-lg"
>
    Lihat Semua
</a>

        </div>

        <!-- CARD -->
        <div class="grid md:grid-cols-3 gap-8">

            @forelse ($kamars as $kamar)

            <div class="bg-white rounded-3xl overflow-hidden shadow-2xl hover:scale-[1.02] transition duration-300">

                <!-- IMAGE -->
                <div class="relative overflow-hidden">

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
                        alt="{{ $kamar->nama_kamar }}"
                    >

                    <!-- STATUS -->
                    <div class="absolute top-4 right-4">

                        @if($kamar->status_booking == 'terisi')

<span class="bg-red-500 text-white text-xs px-4 py-2 rounded-full shadow">
    Sedang Ditempati
</span>

@elseif($kamar->status_booking == 'booking')

<span class="bg-yellow-500 text-white text-xs px-4 py-2 rounded-full shadow">
    Sudah Dibooking
</span>

@else

<span class="bg-green-500 text-white text-xs px-4 py-2 rounded-full shadow">
    Tersedia
</span>

@endif

                    </div>

                </div>

                <!-- CONTENT -->
                <div class="p-6">

                    <h3 class="text-2xl font-bold text-gray-800 mb-3">
                        {{ $kamar->nama_kamar }}
                    </h3>

                    <p class="text-gray-500 text-sm leading-relaxed mb-5 line-clamp-3">
                        {{ $kamar->deskripsi }}
                    </p>

                    <!-- FOOTER -->
                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-gray-500">
                                Harga
                            </p>

                            <h4 class="text-2xl font-bold text-amber-700">
                                Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                            </h4>

                            <p class="text-xs text-gray-400">
                                / bulan
                            </p>

                        </div>

                        @if($kamar->status_booking == 'terisi')

<div class="bg-gray-400 text-white px-5 py-3 rounded-2xl font-semibold cursor-not-allowed text-sm">
    Tidak Tersedia
</div>

@else

<a
    href="{{ route('kamar.show', $kamar->id) }}"
    class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-3 rounded-2xl font-semibold shadow-lg transition"
>
    Detail
</a>

@endif

                    </div>

                </div>

            </div>

            @empty

            <div class="col-span-3">

                <div class="bg-white rounded-3xl shadow-xl p-12 text-center">

                    <h2 class="text-3xl font-bold text-gray-700 mb-3">
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

</div>

@include('components.maps')

@endsection