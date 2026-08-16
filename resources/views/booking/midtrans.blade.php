@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')

<div class="max-w-5xl mx-auto">

<!-- NAVIGATION -->
<div class="mb-6 mt-6 flex justify-end items-center">

    <!-- KANAN: KE BERANDA -->
    <a 
        href="{{ route('home') }}" 
        class="bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow"
    >
        Kembali ke Halaman Utama
    </a>

</div>


<!-- MAIN CARD -->
<div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

    <!-- HEADER -->
    <div class="bg-amber-700 p-8 text-white">

        <h1 class="text-3xl font-bold">
            Pembayaran Sewa
        </h1>

        <p class="mt-2 text-amber-100">
            Selesaikan pembayaran untuk periode sewa yang sedang berjalan.
        </p>

    </div>


    <div class="p-8">


        <!-- INFORMASI PEMBAYARAN -->
        <div class="mb-8 rounded-2xl border border-yellow-200 bg-yellow-50 p-5">

            <div class="flex items-start gap-4">

                <div class="text-yellow-700 text-xl">
                    ⚠
                </div>

                <div>

                    <h3 class="font-semibold text-yellow-800">
                        Pembayaran Periode {{ $payment->periode_ke ?? 1 }}
                    </h3>

                    <p class="text-sm text-yellow-700 mt-1">

                        Pembayaran dilakukan secara bertahap setiap periode sewa.
                        Saat ini Anda perlu melakukan pembayaran untuk
                        periode sewa bulan ke-{{ $payment->periode_ke ?? 1 }}.

                    </p>

                </div>

            </div>

        </div>


        <!-- INFO BOOKING -->
        <div class="grid md:grid-cols-2 gap-8 mb-8">


            <!-- DATA KAMAR -->
            <div>

                <h2 class="text-2xl font-bold text-gray-800 mb-4">

                    {{ $kamar->nama_kamar }}

                </h2>


                <div class="space-y-3 text-gray-600">


                    <!-- TANGGAL MASUK -->
                    <div>

                        <span class="font-semibold">
                            Tanggal Masuk :
                        </span>

                        {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->translatedFormat('d F Y') }}

                    </div>


                    <!-- DURASI -->
                    <div>

                        <span class="font-semibold">
                            Durasi Sewa :
                        </span>

                        {{ $booking->durasi }} Bulan

                    </div>


                    <!-- TANGGAL SELESAI KONTRAK -->
                    <div>

                        <span class="font-semibold">
                            Tanggal Selesai Sewa :
                        </span>

                        {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->translatedFormat('d F Y') }}

                    </div>


                    <!-- NOMOR WHATSAPP -->
                    <div>

                        <span class="font-semibold">
                            WhatsApp :
                        </span>

                        {{ $booking->whatsapp }}

                    </div>

                </div>

            </div>


            <!-- PERIODE PEMBAYARAN -->
            <div>

                <div class="bg-blue-50 border border-blue-200 rounded-2xl p-6">

                    <p class="text-gray-500 mb-2">
                        Periode Pembayaran
                    </p>

                    <h3 class="text-2xl font-bold text-blue-700">

                        Periode {{ $payment->periode_ke ?? 1 }}

                    </h3>


                    <div class="mt-4 space-y-2 text-sm text-gray-600">


                        @if($payment->tanggal_periode_mulai)

                            <div>

                                <span class="font-semibold">
                                    Mulai :
                                </span>

                                {{ \Carbon\Carbon::parse($payment->tanggal_periode_mulai)->translatedFormat('d F Y') }}

                            </div>

                        @endif


                        @if($payment->tanggal_periode_selesai)

                            <div>

                                <span class="font-semibold">
                                    Selesai :
                                </span>

                                {{ \Carbon\Carbon::parse($payment->tanggal_periode_selesai)->translatedFormat('d F Y') }}

                            </div>

                        @endif


                    </div>

                </div>

            </div>

        </div>


        <!-- RINGKASAN PEMBAYARAN -->
        <div class="grid md:grid-cols-2 gap-6 mb-8">


            <!-- TOTAL KONTRAK -->
            <div class="border border-gray-200 rounded-2xl p-6">

                <p class="text-gray-500 mb-2">
                    Total Nilai Sewa
                </p>

                <h2 class="text-3xl font-extrabold text-gray-800">

                    Rp {{ number_format(
                        $booking->total_harga,
                        0,
                        ',',
                        '.'
                    ) }}

                </h2>

                <p class="text-sm text-gray-500 mt-2">

                    Nilai sewa berdasarkan seluruh
                    {{ $booking->durasi }} bulan.

                </p>

            </div>


            <!-- PEMBAYARAN SAAT INI -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">

                <p class="text-gray-500 mb-2">
                    Pembayaran Periode Ini
                </p>

                <h2 class="text-4xl font-extrabold text-amber-700">

                    Rp {{ number_format(
                        $payment->jumlah,
                        0,
                        ',',
                        '.'
                    ) }}

                </h2>

                <p class="text-sm text-gray-500 mt-2">

                    Pembayaran untuk
                    periode ke-{{ $payment->periode_ke ?? 1 }}.

                </p>

            </div>

        </div>


        <!-- JATUH TEMPO -->
        <div class="bg-gray-50 border border-gray-200 rounded-2xl p-5 mb-8">

            <h3 class="font-bold text-gray-700 mb-3">
                Informasi Pembayaran
            </h3>


            <div class="grid sm:grid-cols-2 gap-4 text-sm">


                @if($payment->tanggal_jatuh_tempo)

                    <div>

                        <p class="text-gray-500">
                            Jatuh Tempo
                        </p>

                        <p class="font-semibold text-gray-800 mt-1">

                            {{ \Carbon\Carbon::parse($payment->tanggal_jatuh_tempo)->translatedFormat('d F Y') }}

                        </p>

                    </div>

                @endif


                @if($payment->batas_pembayaran)

                    <div>

                        <p class="text-gray-500">
                            Batas Pembayaran
                        </p>

                        <p class="font-semibold text-red-600 mt-1">

                            {{ \Carbon\Carbon::parse($payment->batas_pembayaran)->translatedFormat('d F Y') }}

                        </p>

                    </div>

                @endif

            </div>


            <p class="text-xs text-gray-500 mt-4">

                Pembayaran periode berikutnya dilakukan sesuai jadwal
                yang ditentukan sistem. Apabila pembayaran belum dilakukan
                sampai batas waktu yang ditentukan, status pembayaran
                akan diproses sesuai ketentuan sewa.

            </p>

        </div>


        <!-- INFORMASI METODE -->
        <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-8">

            <h3 class="font-bold text-blue-700 mb-3">
                Informasi Pembayaran
            </h3>


            <ul class="space-y-2 text-sm text-gray-700">

                <li>
                    ✓ Pembayaran diproses menggunakan Midtrans.
                </li>

                <li>
                    ✓ Pembayaran dilakukan untuk satu periode sewa.
                </li>

                <li>
                    ✓ Mendukung QRIS, GoPay, ShopeePay,
                    Transfer Bank, dan metode pembayaran lainnya
                    yang tersedia pada Midtrans.
                </li>

                <li>
                    ✓ Status pembayaran akan diperbarui
                    setelah transaksi berhasil dikonfirmasi.
                </li>

                <li>
                    ✓ Simpan bukti pembayaran apabila diperlukan.
                </li>

            </ul>

        </div>


        <!-- BUTTON -->
        <button
            id="pay-button"
            type="button"
            class="w-full bg-amber-700 hover:bg-amber-800 text-white py-4 rounded-2xl text-lg font-bold transition shadow-lg"
        >
            Bayar Periode {{ $payment->periode_ke ?? 1 }}
        </button>


    </div>

</div>

</div>

<!-- MIDTRANS SNAP -->

<script
    type="text/javascript"
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}"
></script>

<script>

document
    .getElementById('pay-button')
    .addEventListener('click', function () {

        snap.pay(
            '{{ $payment->snap_token }}',
            {

                onSuccess: function(result) {

                    Swal.fire({

                        icon: 'success',

                        title: 'Pembayaran Berhasil',

                        text: 'Pembayaran periode sewa berhasil diproses.',

                        confirmButtonColor: '#b45309'

                    }).then(function () {

                        window.location.href =
                            "{{ route('booking.riwayat') }}";

                    });

                },


                onPending: function(result) {

                    Swal.fire({

                        icon: 'info',

                        title: 'Menunggu Pembayaran',

                        text: 'Silakan selesaikan pembayaran sesuai instruksi yang diberikan.',

                        confirmButtonColor: '#b45309'

                    });

                },


                onError: function(result) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Pembayaran Gagal',

                        text: 'Pembayaran tidak dapat diproses. Silakan coba kembali.',

                        confirmButtonColor: '#b45309'

                    });

                },


                onClose: function() {

                    Swal.fire({

                        icon: 'info',

                        title: 'Pembayaran Belum Selesai',

                        text: 'Anda menutup halaman pembayaran sebelum transaksi selesai.',

                        confirmButtonColor: '#b45309'

                    });

                }

            }
        );

    });

</script>

@endsection
