@extends('layouts.app')

@section('title', 'Detail Kamar')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | FOTO
    |--------------------------------------------------------------------------
    */

    $foto = $kamar->foto_kamar ?? [];

    if (is_string($foto)) {

        $decoded = json_decode($foto, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            $foto = $decoded;

        } else {

            $foto = [];
        }
    }

    if (!is_array($foto)) {
        $foto = [];
    }

    /*
    |--------------------------------------------------------------------------
    | FASILITAS
    |--------------------------------------------------------------------------
    */

    $fasilitas = $kamar->fasilitas ?? [];

    if (is_string($fasilitas)) {

        $decoded = json_decode($fasilitas, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            $fasilitas = $decoded;

        } else {

            $fasilitas = [];
        }
    }

    if (!is_array($fasilitas)) {
        $fasilitas = [];
    }

@endphp

<div class="max-w-7xl mx-auto">

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="grid lg:grid-cols-2 gap-10">

            <!-- ================================= -->
            <!-- SLIDER FOTO -->
            <!-- ================================= -->

            <div class="p-6">

                <!-- FOTO UTAMA -->
                <div class="mb-4">

                    <img
                        id="mainImage"
                        src="{{ asset('storage/' . ($foto[0] ?? 'default.jpg')) }}"
                        class="w-full h-[450px] object-cover rounded-3xl shadow-lg transition duration-300"
                    >

                </div>

                <!-- THUMBNAIL -->
                <div class="flex gap-4 overflow-x-auto pb-2">

                    @foreach($foto as $img)

                        <img
                            src="{{ asset('storage/' . $img) }}"
                            onclick="changeImage(this)"
                            class="w-28 h-28 object-cover rounded-2xl cursor-pointer border-4 border-transparent hover:border-amber-500 transition"
                        >

                    @endforeach

                </div>

            </div>

            <!-- ================================= -->
            <!-- DETAIL -->
            <!-- ================================= -->

            <div class="p-8">

                <!-- STATUS -->
                <div class="mb-4">

                    @if($kamar->status == 'terisi')

                        <span class="bg-red-100 text-red-700 px-5 py-2 rounded-full text-sm font-bold">
                            Sudah Disewa
                        </span>

                    @else

                        <span class="bg-green-100 text-green-700 px-5 py-2 rounded-full text-sm font-bold">
                            Masih Tersedia
                        </span>

                    @endif

                </div>

                <!-- NAMA -->
                <h1 class="text-5xl font-bold text-gray-800 mb-4">

                    {{ $kamar->nama_kamar }}

                </h1>

                <!-- HARGA -->
                <div class="mb-8">

                    <p class="text-gray-500 text-lg">
                        Harga Sewa
                    </p>

                    <h2 class="text-5xl font-extrabold text-amber-700 mt-2">

                        Rp {{ number_format($kamar->harga, 0, ',', '.') }}

                    </h2>

                    <p class="text-gray-400 mt-1">
                        / bulan
                    </p>

                </div>

                <!-- DESKRIPSI -->
                <div class="mb-8">

                    <h3 class="text-2xl font-bold text-gray-800 mb-3">
                        Deskripsi
                    </h3>

                    <p class="text-gray-600 leading-relaxed text-lg">

                        {{ $kamar->deskripsi }}

                    </p>

                </div>

                <!-- FASILITAS -->
                <div class="mb-10">

                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        Fasilitas
                    </h3>

                    <div class="flex flex-wrap gap-3">

                        @foreach($fasilitas as $item)

                            <div class="bg-gray-100 px-5 py-3 rounded-2xl text-gray-700 font-medium shadow-sm">

                                ✔ {{ $item }}

                            </div>

                        @endforeach

                    </div>

                </div>

                <!-- BUTTON -->
                <div class="flex gap-4">

                    <a
                        href="{{ route('kamar.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-6 py-4 rounded-2xl font-semibold transition"
                    >
                        Kembali
                    </a>

                    <a
    href="{{ route('booking.index', $kamar->id) }}"
    class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg transition"
>
    Sewa Sekarang
</a>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- ================================= -->
<!-- SCRIPT SLIDER -->
<!-- ================================= -->

<script>

    function changeImage(element)
    {
        document.getElementById('mainImage').src = element.src;
    }

</script>

@endsection