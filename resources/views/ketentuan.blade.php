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
            Seluruh penyewa wajib membaca dan memahami aturan penggunaan sistem
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
                Sistem Kontrakan RDP merupakan sistem informasi berbasis web
                yang digunakan untuk membantu proses pencarian kontrakan,
                booking, pembayaran, serta pengelolaan data penyewaan.
                Penyewa wajib menggunakan sistem sesuai dengan ketentuan
                yang berlaku.
            </p>

        </div>


        <!-- 2 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                2. Registrasi Akun
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Penyewa wajib memberikan data yang benar dan sesuai ketika
                melakukan registrasi akun. Data akun digunakan untuk
                keperluan proses booking, pembayaran, dan pengelolaan
                penyewaan.
            </p>

        </div>


        <!-- 3 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                3. Keamanan Akun
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Penyewa bertanggung jawab menjaga keamanan akun,
                termasuk menjaga kerahasiaan password dan tidak
                memberikan informasi akun kepada pihak lain.
            </p>

        </div>


        <!-- 4 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                4. Booking Kontrakan
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Booking hanya dapat dilakukan terhadap kontrakan yang
                tersedia. Penyewa wajib mengisi data booking dengan benar
                dan menentukan durasi serta tanggal penyewaan sesuai
                kebutuhan.
            </p>

        </div>


        <!-- 5 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                5. Pembayaran
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Pembayaran dilakukan secara online melalui layanan
                pembayaran <strong>Midtrans</strong> dengan metode pembayaran
                yang tersedia pada halaman pembayaran.
            </p>

        </div>


        <!-- 6 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                6. Pembayaran Per Periode
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Pembayaran penyewaan dilakukan berdasarkan periode pembayaran
                sesuai dengan durasi booking. Setiap periode memiliki
                status pembayaran masing-masing dan harus diselesaikan
                sesuai dengan ketentuan penyewaan.
            </p>

        </div>


        <!-- 7 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                7. Status Pembayaran
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Sistem menggunakan beberapa status pembayaran, yaitu:
            </p>

            <ul class="list-disc pl-6 text-gray-600 leading-relaxed space-y-2 mt-3">

                <li>
                    <strong>Pending</strong> — pembayaran belum selesai
                    atau masih menunggu proses pembayaran.
                </li>

                <li>
                    <strong>Success</strong> — pembayaran berhasil dan
                    periode tersebut telah dinyatakan lunas.
                </li>

                <li>
                    <strong>Failed</strong> — pembayaran gagal dan
                    penyewa dapat melakukan pembayaran kembali.
                </li>

            </ul>

        </div>


        <!-- 8 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                8. Pembayaran Periode Berikutnya
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Setelah periode sebelumnya berhasil dibayar, penyewa dapat
                melanjutkan pembayaran periode berikutnya melalui menu
                <strong>Riwayat Booking</strong>. Penyewa wajib menyelesaikan
                pembayaran setiap periode sesuai dengan durasi penyewaan.
            </p>

        </div>


        <!-- 9 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                9. Pembatalan Booking
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Booking yang belum memiliki pembayaran berhasil dapat
                dibatalkan melalui fitur pembatalan booking yang tersedia
                pada sistem. Setelah dibatalkan, status booking akan
                berubah menjadi <strong>Dibatalkan</strong>.
            </p>

        </div>


        <!-- 10 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                10. Riwayat Booking
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Penyewa dapat melihat informasi booking, periode pembayaran,
                status pembayaran, tanggal penyewaan, serta informasi
                terkait lainnya melalui menu <strong>Riwayat Booking</strong>.
            </p>

        </div>


        <!-- 11 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                11. Bukti Pembayaran
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Setelah pembayaran berhasil, penyewa dapat melihat dan
                mencetak bukti pembayaran melalui fitur
                <strong>Bukti Pembayaran</strong> yang tersedia pada
                riwayat booking.
            </p>

        </div>


        <!-- 12 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                12. Status Kontrakan
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Informasi ketersediaan kontrakan dapat berubah sesuai
                dengan kondisi penyewaan. Kontrakan yang sedang ditempati
                tidak dapat dibooking oleh penyewa lain.
            </p>

        </div>


        <!-- 13 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                13. Review dan Rating
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Penyewa dapat memberikan review dan rating terhadap
                kontrakan sesuai dengan pengalaman penyewaan.
                Review harus ditulis secara sopan dan tidak mengandung
                penghinaan, spam, maupun informasi yang menyesatkan.
            </p>

        </div>


        <!-- 14 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                14. Pelaporan Review
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Penyewa dapat menggunakan fitur <strong>Report</strong>
                untuk melaporkan review yang dianggap tidak sesuai.
                Admin dapat melakukan pemeriksaan dan tindakan moderasi
                terhadap laporan tersebut.
            </p>

        </div>


        <!-- 15 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                15. Chat dengan Admin
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Fitur chat dapat digunakan oleh penyewa untuk
                berkomunikasi dengan admin mengenai informasi atau
                kebutuhan yang berkaitan dengan penyewaan kontrakan.
            </p>

        </div>


        <!-- 16 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                16. Larangan Penggunaan Sistem
            </h2>

            <ul class="list-disc pl-6 text-gray-600 leading-relaxed space-y-2">

                <li>
                    Menggunakan data atau identitas palsu.
                </li>

                <li>
                    Melakukan booking secara tidak bertanggung jawab.
                </li>

                <li>
                    Menyalahgunakan fitur review dan report.
                </li>

                <li>
                    Menggunakan sistem untuk aktivitas yang merugikan
                    pengguna lain atau pengelola.
                </li>

                <li>
                    Mencoba mengakses atau mengubah data yang bukan
                    merupakan hak aksesnya.
                </li>

                <li>
                    Mengganggu atau mencoba merusak sistem.
                </li>

            </ul>

        </div>


        <!-- 17 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                17. Pengelolaan Data
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Data yang diberikan oleh penyewa digunakan untuk mendukung
                proses registrasi, booking, pembayaran, penyewaan, dan
                pengelolaan layanan dalam sistem.
            </p>

        </div>


        <!-- 18 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                18. Hak Pengelola
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Admin atau pengelola memiliki hak untuk mengelola data
                kontrakan, booking, pembayaran, penyewa, review, dan
                komunikasi dalam sistem sesuai dengan hak akses yang
                diberikan.
            </p>

        </div>


        <!-- 19 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                19. Ketersediaan Sistem
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Pengelola berupaya menjaga sistem agar dapat digunakan
                dengan baik. Namun, gangguan teknis, maintenance,
                maupun kendala layanan pihak ketiga dapat menyebabkan
                sistem tidak dapat digunakan sementara.
            </p>

        </div>


        <!-- 20 -->
        <div>

            <h2 class="text-2xl font-bold text-gray-800 mb-3">
                20. Perubahan Ketentuan
            </h2>

            <p class="text-gray-600 leading-relaxed">
                Ketentuan dan aturan penggunaan sistem dapat diperbarui
                sesuai dengan kebutuhan pengelolaan sistem dan perubahan
                layanan.
            </p>

        </div>


        <!-- FOOTER -->
        <div class="pt-6 border-t border-gray-200">

            <p class="text-sm text-gray-500 text-center">
                Dengan menggunakan sistem Kontrakan RDP, penyewa dianggap
                telah membaca dan memahami ketentuan penggunaan sistem
                serta aturan penyewaan yang berlaku.
            </p>

        </div>

    </div>

</div>

@endsection