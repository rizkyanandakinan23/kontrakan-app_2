@extends('layouts.app')

@section('title', 'Pembayaran Booking')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-3xl shadow-xl p-8">

        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Pembayaran Booking
        </h1>

        <!-- DETAIL KAMAR -->
        <div class="mb-8 border-b pb-6">

            <h2 class="text-2xl font-bold text-amber-700 mb-2">
                {{ $kamar->nama_kamar }}
            </h2>

            <p class="text-gray-600 leading-relaxed">
                {{ $kamar->deskripsi }}
            </p>

        </div>

        <!-- DETAIL PEMBAYARAN -->
        <div class="space-y-6">

            <!-- METODE -->
            <div>

                <p class="text-gray-500 mb-1">
                    Metode Pembayaran
                </p>

                <h3 class="text-2xl font-bold text-gray-800">
                    {{ $metode }}
                </h3>

            </div>

            <!-- BANK -->
            @if($metode != 'QRIS')

                <div>

                    <p class="text-gray-500 mb-1">
                        Nomor Rekening
                    </p>

                    <h3 class="text-3xl font-extrabold text-amber-700 tracking-wide">
                        {{ $rekening }}
                    </h3>

                    <p class="text-gray-500 mt-2">
                        a/n {{ $atasNama }}
                    </p>

                </div>

            @else

                <!-- QRIS -->
                <div>

                    <p class="text-gray-500 mb-4">
                        Scan QRIS Berikut
                    </p>

                    <img
                        src="{{ asset('images/qris.png') }}"
                        class="w-72 rounded-2xl border shadow"
                    >

                </div>

            @endif

            <!-- TOTAL -->
            <div class="bg-amber-50 rounded-2xl p-6 mt-8">

                <p class="text-gray-500 mb-2">
                    Total Pembayaran
                </p>

                <h2 class="text-4xl font-extrabold text-amber-700">
                    Rp {{ number_format($totalHarga, 0, ',', '.') }}
                </h2>

            </div>

        </div>

        <!-- UPLOAD BUKTI -->
        <div class="mt-10 border-t pt-8">

            <h2 class="text-2xl font-bold text-gray-800 mb-6">
                Upload Bukti Pembayaran
            </h2>

            <form
                action="#"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf

                <!-- FILE -->
                <div class="mb-6">

                    <label class="block mb-3 font-semibold">
                        Upload Bukti Transfer
                    </label>

                    <input
                        type="file"
                        name="bukti_pembayaran"
                        accept="image/*"
                        class="w-full border border-gray-300 rounded-2xl px-4 py-3"
                        required
                    >

                    <p class="text-sm text-gray-500 mt-2">
                        Format yang didukung: JPG, JPEG, PNG
                    </p>

                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white px-6 py-3 rounded-2xl font-bold transition"
                >
                    Upload Bukti Pembayaran
                </button>

            </form>

        </div>

        <!-- KEMBALI -->
        <div class="mt-8">

            <a
                href="{{ route('kamar.index') }}"
                class="bg-amber-700 hover:bg-amber-800 text-white px-6 py-3 rounded-2xl inline-block"
            >
                Kembali ke Daftar Kamar
            </a>

        </div>

    </div>

</div>

@endsection