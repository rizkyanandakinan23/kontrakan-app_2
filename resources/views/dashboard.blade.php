@extends('layouts.app')

@section('title', 'Beranda')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HERO -->
    <div class="bg-white/95 rounded-3xl shadow-xl p-10 mb-10">

        <div class="grid md:grid-cols-2 gap-10 items-center">

            <!-- TEXT -->
            <div>

                <p class="text-amber-700 font-semibold mb-3">
                    Selamat Datang di Kontrakan RDP
                </p>

                <h1 class="text-5xl font-bold text-gray-800 leading-tight mb-5">

                    Cari Kamar Kontrakan
                    Nyaman & Strategis

                </h1>

                <p class="text-gray-600 text-lg mb-8 leading-relaxed">

                    Temukan kamar kontrakan terbaik dengan fasilitas lengkap,
                    lokasi strategis, dan harga terjangkau untuk kebutuhan Anda.

                </p>

                <div class="flex flex-wrap gap-4">

                    <a
                        href="{{ route('kamar.index') }}"
                        class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-3 rounded-xl font-semibold transition"
                    >
                        Lihat Kamar
                    </a>

                    @guest

                        <a
                            href="{{ route('register') }}"
                            class="border border-amber-700 text-amber-700 hover:bg-amber-700 hover:text-white px-6 py-3 rounded-xl font-semibold transition"
                        >
                            Daftar Sekarang
                        </a>

                    @endguest

                </div>

            </div>

            <!-- IMAGE -->
            <div>

                <img
                    src="{{ asset('images/kamar1.jpg') }}"
                    alt="Kamar"
                    class="rounded-3xl shadow-lg w-full h-[400px] object-cover"
                >

            </div>

        </div>

    </div>

    <!-- FITUR -->
    <div class="grid md:grid-cols-3 gap-6 mb-10">

        <!-- CARD -->
        <div class="bg-white/95 rounded-2xl shadow-lg p-6">

            <div class="text-4xl mb-4">
                🏠
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Kamar Nyaman
            </h3>

            <p class="text-gray-600">
                Kamar bersih dan nyaman dengan fasilitas lengkap.
            </p>

        </div>

        <!-- CARD -->
        <div class="bg-white/95 rounded-2xl shadow-lg p-6">

            <div class="text-4xl mb-4">
                📍
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Lokasi Strategis
            </h3>

            <p class="text-gray-600">
                Dekat kampus, jalan utama, dan fasilitas umum.
            </p>

        </div>

        <!-- CARD -->
        <div class="bg-white/95 rounded-2xl shadow-lg p-6">

            <div class="text-4xl mb-4">
                💰
            </div>

            <h3 class="text-xl font-bold text-gray-800 mb-2">
                Harga Terjangkau
            </h3>

            <p class="text-gray-600">
                Harga sesuai fasilitas dengan pembayaran mudah.
            </p>

        </div>

    </div>

    <!-- KAMAR POPULER -->
    <div class="mb-10">

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-3xl font-bold text-white">
                Kamar Tersedia
            </h2>

            <a
                href="{{ route('kamar.index') }}"
                class="text-white hover:underline"
            >
                Lihat Semua
            </a>

        </div>

        <div class="grid md:grid-cols-3 gap-8">

            @for ($i = 1; $i <= 3; $i++)

            <div class="bg-white rounded-3xl overflow-hidden shadow-xl">

                <!-- IMAGE -->
                <img
                    src="{{ asset('images/kamar1.jpg') }}"
                    class="w-full h-56 object-cover"
                    alt="Kamar"
                >

                <!-- CONTENT -->
                <div class="p-6">

                    <div class="flex items-center justify-between mb-3">

                        <h3 class="text-2xl font-bold text-gray-800">
                            Kamar {{ $i }}
                        </h3>

                        <span class="bg-green-500 text-white text-xs px-3 py-1 rounded-full">
                            Tersedia
                        </span>

                    </div>

                    <p class="text-gray-600 mb-4">

                        Kamar nyaman dengan fasilitas lengkap dan lokasi strategis.

                    </p>

                    <!-- FASILITAS -->
                    <div class="flex flex-wrap gap-2 mb-5">

                        <span class="bg-gray-100 text-sm px-3 py-1 rounded-full">
                            WiFi
                        </span>

                        <span class="bg-gray-100 text-sm px-3 py-1 rounded-full">
                            Kasur
                        </span>

                        <span class="bg-gray-100 text-sm px-3 py-1 rounded-full">
                            Lemari
                        </span>

                    </div>

                    <!-- FOOTER -->
                    <div class="flex items-center justify-between">

                        <div>

                            <p class="text-sm text-gray-500">
                                Harga
                            </p>

                            <h4 class="text-2xl font-bold text-amber-700">

                                Rp 750K

                            </h4>

                        </div>

                        <a
                            href="#"
                            class="bg-amber-700 hover:bg-amber-800 text-white px-5 py-3 rounded-xl font-semibold transition"
                        >
                            Detail
                        </a>

                    </div>

                </div>

            </div>

            @endfor

        </div>

    </div>

</div>

@endsection