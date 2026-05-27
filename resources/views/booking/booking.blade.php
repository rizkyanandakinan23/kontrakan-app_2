@extends('layouts.app')

@section('title', 'Booking Kamar')

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

    <div class="grid lg:grid-cols-2 gap-8 items-start">

        <!-- ========================= -->
        <!-- DETAIL KAMAR -->
        <!-- ========================= -->

        <div class="bg-white rounded-3xl shadow-xl p-5 max-w-3xl mx-auto">

            <!-- FOTO -->
            <img
                src="{{ asset('storage/' . ($foto[0] ?? 'default.jpg')) }}"
                class="w-full h-80 object-cover"
            >

            <!-- CONTENT -->
            <div class="p-8">

                <!-- STATUS -->
                <div class="mb-4">

                    @if($kamar->status == 'terisi')

                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold">
                            Sudah Disewa
                        </span>

                    @else

                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">
                            Masih Tersedia
                        </span>

                    @endif

                </div>

                <!-- NAMA -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4">

                    {{ $kamar->nama_kamar }}

                </h1>

                <!-- DESKRIPSI -->
                <p class="text-gray-600 leading-relaxed mb-6">

                    {{ $kamar->deskripsi }}

                </p>

                <!-- HARGA -->
                <div class="mb-6">

                    <p class="text-gray-500">
                        Harga Sewa
                    </p>

                    <h2 class="text-4xl font-extrabold text-amber-700">

                        <span id="hargaKamar" data-harga="{{ $kamar->harga }}">
    Rp {{ number_format($kamar->harga, 0, ',', '.') }}
</span>

                    </h2>

                    <p class="text-gray-400">
                        / bulan
                    </p>

                </div>

                <!-- FASILITAS -->
                <div>

                    <h3 class="text-xl font-bold text-gray-800 mb-4">
                        Fasilitas
                    </h3>

                    <div class="flex flex-wrap gap-3">

                        @foreach($fasilitas as $item)

                            <div class="bg-gray-100 px-4 py-2 rounded-xl text-sm">

                                ✔ {{ $item }}

                            </div>

                        @endforeach

                    </div>

                </div>

            </div>

        </div>

        <!-- ========================= -->
        <!-- FORM BOOKING -->
        <!-- ========================= -->

        <div class="bg-white rounded-3xl shadow-2xl p-8">

            <h2 class="text-3xl font-bold text-gray-800 mb-8">
                Form Booking
            </h2>

            <form action="{{ route('booking.store', $kamar->id) }}" method="POST">

                @csrf



                <!-- WHATSAPP -->
                <div class="mb-5">

                    <label class="block mb-2 font-semibold">
                        Nomor WhatsApp
                    </label>

                    <input
                        type="text"
                        name="whatsapp"
                        class="w-full border border-gray-300 rounded-2xl px-4 py-3"
                        required
                    >

                </div>

                <!-- TANGGAL MASUK -->
                <div class="mb-5">

                    <label class="block mb-2 font-semibold">
                        Tanggal Masuk
                    </label>

                    <input
                        type="date"
                        name="tanggal_masuk"
                        class="w-full border border-gray-300 rounded-2xl px-4 py-3"
                        required
                    >

                </div>

                <!-- DURASI -->
<div class="mb-8">

    <label class="block mb-2 font-semibold">
        Durasi Sewa (Bulan)
    </label>

    <input
        type="number"
        name="durasi"
        id="durasi"
        min="1"
        value="1"
        class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
    >

    <p class="text-sm text-gray-500 mt-2">
        Masukkan jumlah bulan sewa
    </p>

</div>

<!-- TOTAL HARGA -->
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">

    <p class="text-gray-600 mb-2">
        Total Harga
    </p>

    <h2
        id="totalHarga"
        class="text-3xl font-bold text-amber-700"
    >
        Rp {{ number_format($kamar->harga, 0, ',', '.') }}
    </h2>

</div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="w-full bg-amber-700 hover:bg-amber-800 text-white py-4 rounded-2xl font-bold text-lg transition"
                >
                    Booking Sekarang
                </button>

            </form>

        </div>

    </div>

</div>

<script>

    const durasi = document.getElementById('durasi');

    const totalHarga = document.getElementById('totalHarga');

    const hargaKamar = document.getElementById('hargaKamar');

    const harga = parseInt(hargaKamar.dataset.harga);

    function hitungTotal() {

        let bulan = parseInt(durasi.value) || 1;

        let total = harga * bulan;

        totalHarga.innerText =
            'Rp ' + total.toLocaleString('id-ID');
    }

    durasi.addEventListener('input', hitungTotal);

    hitungTotal();

</script>



@endsection