@extends('layouts.app')

@section('title', 'Ketentuan')

@section('content')

<div class="max-w-5xl mx-auto">

    <!-- HEADER -->
    <div class="text-center mb-10">

        <h1 class="text-4xl font-bold text-white mb-3">
            Ketentuan & Aturan
        </h1>

        <p class="text-white/90 text-lg">
            Seluruh pengguna wajib membaca dan memahami aturan penggunaan sistem
            serta ketentuan penyewaan kontrakan.
        </p>

    </div>

    <!-- CONTENT -->
    <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10 space-y-8">

        <!-- 1 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                1. Ketentuan Umum
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Sistem Kontrakan RDP dibuat untuk membantu proses pencarian,
                pemesanan, dan pengelolaan kamar kontrakan secara online.
                Pengguna wajib menggunakan sistem dengan bijak dan tidak
                menyalahgunakan layanan yang tersedia.

            </p>

        </div>

        <!-- 2 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                2. Registrasi Akun
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pengguna wajib menggunakan data asli dan valid saat melakukan
                pendaftaran akun. Sistem berhak menangguhkan atau menghapus akun
                yang menggunakan identitas palsu atau mencurigakan.

            </p>

        </div>

        <!-- 3 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                3. Keamanan Akun
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pengguna bertanggung jawab penuh terhadap keamanan akun,
                termasuk menjaga kerahasiaan password dan aktivitas akun masing-masing.

            </p>

        </div>

        <!-- 4 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                4. Pemesanan Kamar
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Booking kamar hanya dapat dilakukan apabila kamar masih tersedia.
                Status kamar dapat berubah sewaktu-waktu berdasarkan aktivitas pengguna lain
                maupun keputusan admin.

            </p>

        </div>

        <!-- 5 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                5. Pembayaran
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pembayaran dilakukan melalui metode yang disediakan oleh pengelola.
                Pengguna wajib mengupload bukti pembayaran yang jelas dan valid
                untuk proses verifikasi.

            </p>

        </div>

        <!-- 6 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                6. Verifikasi Pembayaran
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Admin memiliki hak untuk menerima atau menolak pembayaran apabila
                ditemukan bukti pembayaran yang tidak valid, buram, atau mencurigakan.

            </p>

        </div>

        <!-- 7 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                7. Pembatalan Booking
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pembatalan booking dapat dilakukan sesuai kebijakan pengelola.
                Pengguna dianjurkan menghubungi admin apabila ingin melakukan pembatalan.

            </p>

        </div>

        <!-- 8 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                8. Review dan Komentar
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pengguna diperbolehkan memberikan review terhadap kamar,
                namun wajib menggunakan bahasa yang sopan dan tidak mengandung
                unsur penghinaan, SARA, spam, atau informasi palsu.

            </p>

        </div>

        <!-- 9 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                9. Pelaporan Review
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pengguna dapat melaporkan review yang dianggap tidak pantas.
                Admin berhak melakukan moderasi, penghapusan, atau tindakan lainnya
                terhadap review yang melanggar aturan.

            </p>

        </div>

        <!-- 10 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                10. Larangan Pengguna
            </h2>

            <ul class="list-disc pl-6 text-gray-600 leading-relaxed space-y-2">

                <li>Menggunakan identitas palsu</li>

                <li>Melakukan spam atau aktivitas mencurigakan</li>

                <li>Menyebarkan konten negatif atau ilegal</li>

                <li>Melakukan booking palsu</li>

                <li>Merusak sistem atau mencoba membobol keamanan website</li>

                <li>Mengupload file berbahaya</li>

                <li>Menyalahgunakan fitur review dan report</li>

            </ul>

        </div>

        <!-- 11 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                11. Penghapusan Akun
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Admin berhak menonaktifkan atau menghapus akun pengguna
                yang terbukti melanggar aturan sistem atau melakukan aktivitas merugikan.

            </p>

        </div>

        <!-- 12 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                12. Ketersediaan Sistem
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pengelola berusaha menjaga sistem tetap aktif dan stabil,
                namun tidak menjamin layanan bebas gangguan setiap saat.

            </p>

        </div>

        <!-- 13 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                13. Perubahan Data
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Admin dapat memperbarui data kamar, harga, fasilitas,
                maupun informasi lainnya sesuai kondisi terbaru.

            </p>

        </div>

        <!-- 14 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                14. Privasi Pengguna
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Data pengguna digunakan hanya untuk keperluan operasional sistem
                dan tidak diperjualbelikan kepada pihak lain tanpa izin pengguna.

            </p>

        </div>

        <!-- 15 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                15. Hak Pengelola
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pengelola memiliki hak untuk melakukan maintenance,
                pembaruan fitur, moderasi konten, dan tindakan lain
                demi menjaga kualitas layanan sistem.

            </p>

        </div>

        <!-- 16 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                16. Tanggung Jawab Pengguna
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Pengguna bertanggung jawab atas seluruh aktivitas yang dilakukan
                menggunakan akun masing-masing di dalam sistem.

            </p>

        </div>

        <!-- 17 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                17. Penyalahgunaan Sistem
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Segala bentuk penyalahgunaan sistem dapat dikenakan pembatasan akses,
                penghapusan akun, atau tindakan lain sesuai kebijakan pengelola.

            </p>

        </div>

        <!-- 18 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                18. Perubahan Ketentuan
            </h2>

            <p class="text-gray-600 leading-relaxed">

                Ketentuan dan aturan ini dapat diperbarui sewaktu-waktu
                sesuai kebutuhan sistem tanpa pemberitahuan sebelumnya.

            </p>

        </div>

        <!-- FOOTER -->
        <div class="pt-6 border-t border-gray-200">

            <p class="text-sm text-gray-500 text-center">

                Dengan menggunakan sistem Kontrakan RDP,
                pengguna dianggap telah membaca, memahami,
                dan menyetujui seluruh ketentuan yang berlaku.

            </p>

        </div>

    </div>

</div>

@endsection