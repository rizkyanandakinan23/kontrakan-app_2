@extends('layouts.app')

@section('title', 'Bukti Pembayaran')

@section('content')

<div class="max-w-3xl mx-auto">

    <h1 class="text-3xl font-bold text-white mb-6">
        Bukti Pembayaran
    </h1>


    <div class="bg-white rounded-3xl shadow-xl p-8">


        <!-- HEADER -->
        <div class="text-center mb-6">

            <h2 class="text-2xl font-bold text-gray-800">
                Kontrakan Raden Panghulu Djaja
            </h2>

            <p class="text-gray-500">
                Bukti transaksi penyewaan kontrakan
            </p>

        </div>



        <hr class="mb-6">



        <!-- DETAIL BOOKING -->
        <div class="space-y-4 text-gray-700">


            <div class="flex justify-between">
                <span class="font-semibold">
                    Booking ID
                </span>

                <span>
                    #{{ $booking->id }}
                </span>
            </div>



            <div class="flex justify-between">
                <span class="font-semibold">
                    Nama Penyewa
                </span>

                <span>
                    {{ $booking->user->nama_lengkap }}
                </span>
            </div>

            <div class="flex justify-between">
        <span class="font-semibold">
            Email
        </span>

        <span>
            {{ $booking->user->email }}
        </span>
    </div>


    <div class="flex justify-between">
        <span class="font-semibold">
            No. Telpon
        </span>

        <span>
            {{ $booking->user->no_telp ?? '-' }}
        </span>
    </div>



            <div class="flex justify-between">
                <span class="font-semibold">
                    Kamar
                </span>

                <span>
                    {{ $booking->kamar->nama_kamar }}
                </span>
            </div>



            <div class="flex justify-between">
                <span class="font-semibold">
                    Tanggal Masuk
                </span>

                <span>
                    {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }}
                </span>
            </div>



            <div class="flex justify-between">
                <span class="font-semibold">
                    Tanggal Selesai
                </span>

                <span>
                    {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
                </span>
            </div>



            <div class="flex justify-between">
                <span class="font-semibold">
                    Durasi Sewa
                </span>

                <span>
                    {{ $booking->durasi }} bulan
                </span>
            </div>



        </div>



        <hr class="my-6">



        <!-- DETAIL PEMBAYARAN -->
        <div class="space-y-4 text-gray-700">


            <div class="flex justify-between">
                <span class="font-semibold">
                    Total Pembayaran
                </span>

                <span class="font-bold text-amber-600">

                    Rp {{ number_format($booking->total_harga,0,',','.') }}

                </span>

            </div>




            <div class="flex justify-between">

                <span class="font-semibold">
                    Metode Pembayaran
                </span>

                <span>
                    {{ $booking->payment->payment_type ?? 'Midtrans' }}
                </span>

            </div>




            <div class="flex justify-between">

                <span class="font-semibold">
                    Status Pembayaran
                </span>


                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full">

                    Lunas

                </span>

            </div>




            <div class="flex justify-between">

                <span class="font-semibold">
                    ID Transaksi
                </span>


                <span>
                    {{ $booking->payment->order_id ?? '-' }}
                </span>

            </div>




            <div class="flex justify-between">

                <span class="font-semibold">
                    Waktu Pembayaran
                </span>


                <span>

                    {{ 
                        $booking->payment?->paid_at 
                        ? \Carbon\Carbon::parse($booking->payment->paid_at)
                            ->timezone('Asia/Jakarta')
                            ->format('d M Y H:i')
                        : '-'
                    }}

                </span>

            </div>


        </div>



        <hr class="my-6">



        <!-- BUTTON -->
        <div class="flex justify-end gap-3">


            <button onclick="window.print()"
                class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-lg">

                🖨 Cetak Bukti

            </button>


            <a href="{{ route('booking.riwayat') }}"
                class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg">

                Kembali

            </a>


        </div>



    </div>


</div>

<style>

@media print {

    /* sembunyikan semua elemen layout */
    header,
    footer,
    nav {
        display: none !important;
    }


    /* hilangkan background halaman */
    body {
        background: white !important;
    }


    /* hilangkan tombol */
    button,
    a {
        display: none !important;
    }


    /* rapikan area cetak */
    .print-area {
        width: 100%;
        margin: 0;
        padding: 0;
        box-shadow: none !important;
    }

}

</style>

@endsection