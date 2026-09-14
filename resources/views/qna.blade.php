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
                Kontrakan RDP adalah sistem informasi berbasis web yang
                membantu penyewa mencari kontrakan, melihat detail kontrakan,
                melakukan booking, melakukan pembayaran, serta melihat
                riwayat penyewaan secara online.
            </p>

        </details>


        <!-- 2 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah saya harus login untuk booking kontrakan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Ya. Penyewa harus memiliki akun dan login terlebih dahulu
                sebelum melakukan booking kontrakan.
            </p>

        </details>


        <!-- 3 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana cara melakukan booking kontrakan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Penyewa dapat memilih kontrakan yang tersedia, membuka
                halaman detail kontrakan, kemudian menekan tombol
                <strong>Sewa Sekarang</strong> dan mengisi data booking
                sesuai dengan kebutuhan penyewaan.
            </p>

        </details>


        <!-- 4 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana sistem pembayaran dilakukan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Pembayaran dilakukan secara online melalui
                <strong>Midtrans</strong>. Penyewa dapat memilih metode
                pembayaran yang tersedia pada halaman pembayaran Midtrans.
            </p>

        </details>


        <!-- 5 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah pembayaran dilakukan sekaligus untuk seluruh masa sewa?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Pembayaran dilakukan berdasarkan periode penyewaan.
                Setiap periode memiliki pembayaran masing-masing sesuai
                dengan durasi booking.
            </p>

        </details>


        <!-- 6 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apa arti status pembayaran "Pending", "Success", dan "Failed"?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                <strong>Pending</strong> berarti pembayaran belum selesai
                atau masih menunggu proses pembayaran.
                <br><br>

                <strong>Success</strong> berarti pembayaran berhasil dan
                periode tersebut telah dinyatakan lunas.
                <br><br>

                <strong>Failed</strong> berarti pembayaran gagal sehingga
                penyewa perlu melakukan pembayaran kembali.
            </p>

        </details>


        <!-- 7 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana jika pembayaran saya gagal?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Jika pembayaran gagal, penyewa dapat kembali ke menu
                <strong>Riwayat Booking</strong> dan memilih opsi pembayaran
                pada periode yang belum lunas untuk melakukan pembayaran
                kembali.
            </p>

        </details>


        <!-- 8 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana cara melanjutkan pembayaran periode berikutnya?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Setelah periode sebelumnya berhasil dibayar, penyewa dapat
                membuka menu <strong>Riwayat Booking</strong>. Jika terdapat
                periode berikutnya yang belum lunas, sistem akan menyediakan
                tombol <strong>Bayar Periode</strong> atau
                <strong>Lanjut Bayar</strong>.
            </p>

        </details>


        <!-- 9 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah saya bisa melihat riwayat booking?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Ya. Penyewa dapat melihat seluruh data booking dan status
                pembayaran melalui menu <strong>Riwayat Booking</strong>.
            </p>

        </details>


        <!-- 10 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah saya bisa mendapatkan bukti pembayaran?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Ya. Setelah pembayaran berhasil, penyewa dapat melihat dan
                mencetak bukti pembayaran melalui fitur
                <strong>Bukti Pembayaran</strong> pada menu Riwayat Booking.
            </p>

        </details>


        <!-- 11 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apa arti status "Sedang Ditempati" pada kontrakan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Status <strong>Sedang Ditempati</strong> menunjukkan bahwa
                kontrakan sedang digunakan oleh penyewa sehingga tidak dapat
                dibooking oleh penyewa lain.
            </p>

        </details>


        <!-- 12 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah booking dapat dibatalkan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Booking yang belum memiliki pembayaran berhasil dapat
                dibatalkan melalui menu <strong>Riwayat Booking</strong>.
                Setelah booking dibatalkan, status booking akan berubah
                menjadi <strong>Dibatalkan</strong>.
            </p>

        </details>


        <!-- 13 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana cara memberikan review kontrakan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Penyewa dapat memberikan review dan rating melalui halaman
                detail kontrakan setelah memenuhi kondisi yang diperlukan
                oleh sistem.
            </p>

        </details>


        <!-- 14 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah review penyewa lain dapat dilaporkan?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Ya. Jika terdapat review yang dianggap tidak sesuai atau
                tidak pantas, penyewa dapat menggunakan fitur
                <strong>Report</strong> untuk melaporkannya kepada admin.
            </p>

        </details>


        <!-- 15 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah penyewa dapat berkomunikasi dengan admin?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Ya. Sistem menyediakan fitur <strong>Chat</strong> yang
                memungkinkan penyewa berkomunikasi dengan admin mengenai
                kebutuhan terkait penyewaan kontrakan.
            </p>

        </details>


        <!-- 16 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Bagaimana jika saya lupa password?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Penyewa dapat menggunakan fitur
                <strong>Lupa Password</strong> pada halaman login untuk
                melakukan proses reset password akun.
            </p>

        </details>


        <!-- 17 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah sistem dapat diakses melalui HP?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Ya. Sistem dirancang dengan tampilan responsif sehingga
                dapat digunakan melalui smartphone, tablet, maupun komputer.
            </p>

        </details>


        <!-- 18 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Siapa yang mengelola sistem ini?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Sistem dikelola oleh admin atau pengelola kontrakan untuk
                memonitor data kontrakan, booking, pembayaran, penyewa,
                review, dan komunikasi dengan penyewa.
            </p>

        </details>


        <!-- 19 -->
        <details class="border border-gray-200 rounded-2xl p-5 cursor-pointer hover:shadow-md transition">

            <summary class="font-bold text-gray-800 text-lg">
                Apakah data penyewa aman?
            </summary>

            <p class="mt-4 text-gray-600 leading-relaxed">
                Sistem menerapkan autentikasi dan pembatasan hak akses
                berdasarkan peran pengguna sehingga fitur dan data dapat
                diakses sesuai dengan hak masing-masing pengguna.
            </p>

        </details>


    </div>

</div>

@endsection