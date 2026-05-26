@extends('layouts.app')

@section('title', 'FAQ')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">

        <h1 class="text-4xl font-bold text-white mb-3">
            Frequently Asked Questions
        </h1>

        <p class="text-white/90 text-lg">
            Pertanyaan yang sering ditanyakan mengenai sistem Kontrakan RDP.
        </p>

    </div>

    <!-- FAQ LIST -->
    <div class="bg-white rounded-3xl shadow-2xl p-6 md:p-8 space-y-5">

        <!-- 1 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apa itu sistem Kontrakan RDP?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Kontrakan RDP adalah sistem berbasis web yang membantu pengguna
                mencari, melihat detail kamar, melakukan booking, dan mengelola
                penyewaan kontrakan secara online.

            </p>

        </details>

        <!-- 2 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah saya harus login untuk booking kamar?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Ya. Pengguna wajib memiliki akun dan login terlebih dahulu
                sebelum melakukan pemesanan kamar.

            </p>

        </details>

        <!-- 3 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana cara booking kamar?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Pilih kamar yang tersedia, masuk ke halaman detail kamar,
                lalu tekan tombol <strong>Sewa Sekarang</strong> untuk
                melakukan proses booking.

            </p>

        </details>

        <!-- 4 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah pembayaran dilakukan secara online?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Pembayaran dilakukan melalui transfer bank atau metode pembayaran
                lain yang disediakan admin, kemudian pengguna mengupload bukti pembayaran.

            </p>

        </details>

        <!-- 5 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Kapan booking saya dikonfirmasi?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Booking akan dikonfirmasi setelah admin melakukan verifikasi
                terhadap bukti pembayaran yang diupload pengguna.

            </p>

        </details>

        <!-- 6 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apa arti status “Terisi” pada kamar?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Status “Terisi” berarti kamar sedang disewa dan tidak dapat
                dibooking oleh pengguna lain.

            </p>

        </details>

        <!-- 7 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah saya bisa membatalkan booking?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Untuk saat ini pembatalan booking dilakukan melalui admin
                atau pemilik kontrakan.

            </p>

        </details>

        <!-- 8 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah saya bisa melihat riwayat booking?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Ya. Pengguna dapat melihat seluruh riwayat booking
                melalui menu Riwayat Booking pada akun masing-masing.

            </p>

        </details>

        <!-- 9 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana cara memberikan review kamar?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Pengguna dapat memberikan review dan rating melalui
                halaman detail kamar setelah login ke dalam sistem.

            </p>

        </details>

        <!-- 10 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah review pengguna lain bisa dilaporkan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Ya. Jika terdapat review yang tidak pantas, pengguna
                dapat menggunakan fitur report untuk melaporkannya kepada admin.

            </p>

        </details>

        <!-- 11 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Siapa yang mengelola sistem ini?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Sistem dikelola oleh admin atau pemilik kontrakan
                untuk memonitor kamar, booking, pengguna, dan review.

            </p>

        </details>

        <!-- 12 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah data pengguna aman?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Ya. Sistem menggunakan autentikasi login dan pengelolaan akses
                pengguna agar data lebih aman dan terkontrol.

            </p>

        </details>

        <!-- 13 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah sistem bisa diakses melalui HP?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Bisa. Sistem dirancang responsif sehingga dapat diakses
                melalui smartphone, tablet, maupun komputer.

            </p>

        </details>

        <!-- 14 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana jika lupa password akun?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Pengguna dapat menggunakan fitur reset password
                atau menghubungi admin untuk bantuan pemulihan akun.

            </p>

        </details>

        <!-- 15 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah sistem ini gratis digunakan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">

                Ya. Sistem dapat digunakan secara gratis oleh pengguna
                untuk mencari dan melakukan booking kamar kontrakan.

            </p>

        </details>

    </div>

</div>

@endsection