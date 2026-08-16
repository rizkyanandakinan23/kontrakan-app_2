@extends('layouts.app')

@section('title', 'Bukti Pembayaran')

@section('content')

<style>
    @media print {

        @page {
            size: A4;
            margin: 15mm;
        }

        /* Sembunyikan seluruh halaman */
        body * {
            visibility: hidden !important;
        }

        /* Hanya bukti pembayaran yang dicetak */
        #print-area,
        #print-area * {
            visibility: visible !important;
        }

        /* Atur posisi dan ukuran struk */
        #print-area {
            position: absolute !important;
            left: 50% !important;
            top: 15mm !important;
            transform: translateX(-50%) !important;

            width: 170mm !important;
            margin: 0 !important;
            padding: 12mm !important;

            background: white !important;
            box-shadow: none !important;
            border-radius: 0 !important;

            box-sizing: border-box !important;

            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }

        /* Jangan cetak elemen ini */
        .no-print {
            display: none !important;
        }

        /* Hilangkan background/layout website */
        html,
        body {
            background: white !important;
            margin: 0 !important;
            padding: 0 !important;
            height: auto !important;
            overflow: visible !important;
        }

        /* Sembunyikan navigasi/footer/layout */
        header,
        footer,
        nav,
        aside {
            display: none !important;
        }
    }
</style>

<h1 class="text-3xl font-bold text-white mb-6 no-print">
    Bukti Pembayaran
</h1>

<div id="print-area" class="bg-white rounded-3xl shadow-xl p-8">

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

<!-- DETAIL PERIODE PEMBAYARAN -->
<div class="space-y-4 text-gray-700">

    <div class="flex justify-between">
        <span class="font-semibold">
            Periode Pembayaran
        </span>

        <span>
            Periode ke-{{ $payment->periode_ke }}
        </span>
    </div>

    <div class="flex justify-between">
        <span class="font-semibold">
            Periode Mulai
        </span>

        <span>
            {{ $payment->tanggal_periode_mulai
                ? $payment->tanggal_periode_mulai->format('d M Y')
                : '-' }}
        </span>
    </div>

    <div class="flex justify-between">
        <span class="font-semibold">
            Periode Selesai
        </span>

        <span>
            {{ $payment->tanggal_periode_selesai
                ? $payment->tanggal_periode_selesai->format('d M Y')
                : '-' }}
        </span>
    </div>

    <div class="flex justify-between">
        <span class="font-semibold">
            Total Pembayaran
        </span>

        <span class="font-bold text-amber-600">
            Rp {{ number_format($payment->jumlah, 0, ',', '.') }}
        </span>
    </div>

    <div class="flex justify-between">
        <span class="font-semibold">
            Metode Pembayaran
        </span>

        <span>
            {{ $payment->payment_type ?? 'Midtrans' }}
        </span>
    </div>

    <div class="flex justify-between">
        <span class="font-semibold">
            Status Pembayaran
        </span>

        @if($payment->status === 'success')

            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full">
                Lunas
            </span>

        @elseif($payment->status === 'pending')

            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full">
                Belum Dibayar
            </span>

        @else

            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full">
                Gagal
            </span>

        @endif

    </div>

    <div class="flex justify-between">
        <span class="font-semibold">
            ID Transaksi
        </span>

        <span>
            {{ $payment->order_id ?? '-' }}
        </span>
    </div>

    <div class="flex justify-between">
        <span class="font-semibold">
            Waktu Pembayaran
        </span>

        <span>
            {{ $payment->paid_at
                ? $payment->paid_at
                    ->timezone('Asia/Jakarta')
                    ->format('d M Y H:i')
                : '-' }}
        </span>
    </div>

</div>

<hr class="my-6">

<!-- BUTTON -->
<div class="flex justify-end gap-3 no-print">

    <button
        onclick="printBukti()"
        class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-lg">

        🖨 Cetak Bukti

    </button>

    <a
        href="{{ route('booking.riwayat') }}"
        class="bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg">

        Kembali

    </a>

</div>


</div>

<script>
function printBukti() {
    const originalTitle = document.title;

    document.title = 'Bukti-Pembayaran-{{ $booking->id }}';

    window.print();

    setTimeout(() => {
        document.title = originalTitle;
    }, 1000);
}
</script>

@endsection
