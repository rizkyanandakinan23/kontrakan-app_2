@extends('layouts.app')

@section('title', 'FAQ')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">

        <h1 class="text-4xl font-bold text-white mb-3">
            Frequently Asked Questions
        </h1>

        <p class="text-white/90">
            Pertanyaan yang sering ditanyakan tentang sistem kontrakan.
        </p>

    </div>

    <!-- FAQ LIST -->
    <div class="bg-white rounded-2xl shadow p-6 space-y-4">

        <!-- ITEM -->
        <details class="border rounded-xl p-4 cursor-pointer">

            <summary class="font-semibold text-gray-800">
                Apa itu sistem Kontrakan RDP?
            </summary>

            <p class="mt-3 text-gray-600 text-sm leading-relaxed">

                Kontrakan RDP adalah sistem berbasis web untuk memudahkan
                pencarian, pemesanan, dan pengelolaan kamar kontrakan secara online.

            </p>

        </details>

        <!-- ITEM -->
        <details class="border rounded-xl p-4 cursor-pointer">

            <summary class="font-semibold text-gray-800">
                Apakah bisa booking kamar secara online?
            </summary>

            <p class="mt-3 text-gray-600 text-sm leading-relaxed">

                Ya, pengguna dapat melakukan pemesanan kamar secara online
                melalui sistem tanpa harus datang langsung ke lokasi.

            </p>

        </details>

        <!-- ITEM -->
        <details class="border rounded-xl p-4 cursor-pointer">

            <summary class="font-semibold text-gray-800">
                Apakah pembayaran sudah otomatis?
            </summary>

            <p class="mt-3 text-gray-600 text-sm leading-relaxed">

                Tidak. Pembayaran masih manual melalui transfer bank, QRIS,
                atau tunai, kemudian dikonfirmasi melalui sistem.

            </p>

        </details>

        <!-- ITEM -->
        <details class="border rounded-xl p-4 cursor-pointer">

            <summary class="font-semibold text-gray-800">
                Siapa yang bisa menggunakan sistem ini?
            </summary>

            <p class="mt-3 text-gray-600 text-sm leading-relaxed">

                Sistem ini digunakan oleh admin (pemilik kontrakan) dan penyewa
                untuk mengelola serta mencari kamar kontrakan.

            </p>

        </details>

        <!-- ITEM -->
        <details class="border rounded-xl p-4 cursor-pointer">

            <summary class="font-semibold text-gray-800">
                Apakah data aman?
            </summary>

            <p class="mt-3 text-gray-600 text-sm leading-relaxed">

                Ya, sistem menggunakan autentikasi login sehingga data pengguna
                lebih terkontrol dan aman.

            </p>

        </details>

    </div>

</div>

@endsection