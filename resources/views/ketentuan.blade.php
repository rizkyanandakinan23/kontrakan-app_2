@extends('layouts.app')

@section('title', 'Ketentuan')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">

        <h1 class="text-4xl font-bold text-white mb-3">
            Ketentuan & Aturan
        </h1>

        <p class="text-white/90">
            Peraturan penggunaan sistem dan penyewaan kamar kontrakan.
        </p>

    </div>

    <!-- CONTENT -->
    <div class="bg-white rounded-2xl shadow p-8 space-y-6">

        <!-- SECTION -->
        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">
                1. Ketentuan Umum
            </h2>

            <p class="text-gray-600 leading-relaxed text-sm">

                Sistem ini digunakan untuk mempermudah proses pencarian dan
                penyewaan kamar kontrakan. Pengguna wajib menggunakan sistem
                dengan itikad baik dan tidak menyalahgunakan layanan.

            </p>

        </div>

        <!-- SECTION -->
        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">
                2. Pendaftaran Pengguna
            </h2>

            <p class="text-gray-600 leading-relaxed text-sm">

                Pengguna wajib melakukan registrasi dengan data yang benar dan valid.
                Data yang tidak valid dapat menyebabkan akun dibatasi atau dihapus.

            </p>

        </div>

        <!-- SECTION -->
        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">
                3. Pemesanan Kamar
            </h2>

            <p class="text-gray-600 leading-relaxed text-sm">

                Pemesanan kamar hanya dapat dilakukan jika kamar tersedia.
                Status kamar dapat berubah sewaktu-waktu sesuai kondisi terbaru.

            </p>

        </div>

        <!-- SECTION -->
        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">
                4. Pembayaran
            </h2>

            <p class="text-gray-600 leading-relaxed text-sm">

                Pembayaran dilakukan secara manual melalui transfer bank,
                QRIS, atau tunai. Konfirmasi pembayaran wajib dilakukan melalui sistem.

            </p>

        </div>

        <!-- SECTION -->
        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">
                5. Larangan
            </h2>

            <ul class="list-disc pl-6 text-sm text-gray-600 space-y-1">

                <li>Menggunakan data palsu saat registrasi</li>
                <li>Merusak atau menyalahgunakan sistem</li>
                <li>Melakukan pemesanan fiktif</li>
                <li>Mengganggu proses operasional kontrakan</li>

            </ul>

        </div>

        <!-- SECTION -->
        <div>

            <h2 class="text-xl font-bold text-gray-800 mb-2">
                6. Perubahan Ketentuan
            </h2>

            <p class="text-gray-600 leading-relaxed text-sm">

                Ketentuan ini dapat berubah sewaktu-waktu sesuai kebutuhan
                pengelola tanpa pemberitahuan sebelumnya.

            </p>

        </div>

    </div>

</div>

@endsection