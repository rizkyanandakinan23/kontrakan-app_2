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
                        Lihat Kontrakan
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
        @php
    $heroImages = [
        'images/12.png',
        'images/13.png',
        'images/14.png',
        'images/15.png',
        'images/17.png',
        'images/18.png',
    ];
@endphp

    <div class="relative mx-auto overflow-hidden rounded-3xl shadow-xl bg-gray-100"
     style="width:400px; height:531.60px; max-width:100%;">

    @foreach($heroImages as $index => $img)
        <img
            src="{{ asset($img) }}"
            class="hero-slide absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 {{ $loop->first ? 'opacity-100' : 'opacity-0' }}"
            alt="Kontrakan">
    @endforeach

        <!-- Overlay -->
        <div class="absolute inset-0 bg-black/25"></div>

    </div>

</div>

        </div>

    </div>

    <div class="my-16">

    <h2 class="text-3xl font-bold text-white mb-3">
        Siap Menemukan Kontrakan yang Tepat?
    </h2>

    <p class="text-white/90 text-lg mb-8 leading-relaxed">
        Pilih kamar yang sesuai dengan kebutuhan Anda dan lakukan booking secara online dengan proses yang mudah, cepat, dan aman.
    </p>

    <a href="{{ route('kamar.index') }}"
   class="inline-block bg-amber-700 hover:bg-amber-800 text-white text-lg font-bold px-10 py-4 rounded-2xl shadow-lg hover:shadow-xl transition duration-300">
    Pesan Kamar
    </a>

    <p class="text-sm text-white/80 mt-4">
        Cek ketersediaan kamar dan lakukan booking langsung melalui website.
    </p>

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

<!-- FITUR -->
    <div class="grid md:grid-cols-3 gap-7 mb-14">

        <!-- CARD -->
        <div class="bg-white/95 rounded-3xl shadow-xl p-7 hover:-translate-y-1 transition duration-300">

            <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center text-3xl mb-5">
                🏠
            </div>

            <h3 class="text-2xl font-bold text-gray-800 mb-3">
                Kontrakan Nyaman
            </h3>

            <p class="text-gray-600 leading-relaxed">
                Kontrakan bersih, nyaman, dan cocok untuk mahasiswa maupun pekerja.
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


@include('components.maps')

<script>

document.addEventListener('DOMContentLoaded', function () {

    const slides = document.querySelectorAll('.hero-slide');

    let current = 0;

    if(slides.length <= 1) return;

    setInterval(function(){

        slides[current].classList.remove('opacity-100');
        slides[current].classList.add('opacity-0');

        current++;

        if(current >= slides.length){
            current = 0;
        }

        slides[current].classList.remove('opacity-0');
        slides[current].classList.add('opacity-100');

    },6000);

});

</script>

@endsection