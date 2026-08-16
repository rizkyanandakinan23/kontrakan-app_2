@extends('layouts.adminlayouts')

@section('title', 'Kelola Pembayaran')

@section('content')

<div class="space-y-8">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div>
        <h1 class="text-3xl font-bold text-white drop-shadow-lg">
            💰 Kelola Pembayaran
        </h1>

        <p class="mt-2 text-white/80">
            Monitoring transaksi pembayaran, pendapatan bulanan,
            dan laporan keuangan Kontrakan Raden Panghulu Djaja.
        </p>
    </div>


    {{-- =========================================================
        STATISTIK
    ========================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- TOTAL TRANSAKSI --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <p class="text-gray-500 text-sm">
                Total Transaksi Berhasil
            </p>

            <h2 class="text-2xl md:text-3xl font-bold text-blue-600 mt-2">
                {{ $totalTransaksi ?? 0 }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Transaksi dengan status settlement
            </p>

        </div>


        {{-- RATA-RATA --}}
        <div class="bg-white rounded-2xl shadow-sm p-6">

            <p class="text-gray-500 text-sm">
                Rata-rata Pendapatan / Bulan
            </p>

            <h2 class="text-2xl md:text-3xl font-bold text-indigo-600 mt-2 break-words">
                Rp {{ number_format($rataPendapatan ?? 0, 0, ',', '.') }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Berdasarkan pembayaran berhasil
            </p>

        </div>

    </div>



    {{-- =========================================================
        LAPORAN BULANAN
    ========================================================== --}}
    <div class="bg-white rounded-3xl shadow overflow-hidden">

        <div class="p-6 border-b">

            <h2 class="text-xl font-bold text-gray-800">
                Pendapatan Per Bulan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Rekap transaksi pembayaran yang berhasil.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-amber-600 text-white">

                    <tr>

                        <th class="p-4 text-left">
                            Bulan
                        </th>

                        <th class="p-4 text-left">
                            Jumlah Transaksi
                        </th>

                        <th class="p-4 text-left">
                            Pendapatan
                        </th>

                        <th class="p-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($laporanBulanan as $laporan)

                    <tr class="border-b hover:bg-gray-50">

                        {{-- BULAN --}}
                        <td class="p-4 font-semibold text-gray-800">

                            {{ \Carbon\Carbon::create()
                                ->month((int) $laporan->bulan)
                                ->translatedFormat('F') }}

                            {{ $laporan->tahun }}

                        </td>


                        {{-- JUMLAH TRANSAKSI --}}
                        <td class="p-4 text-gray-700">

                            {{ $laporan->jumlah_transaksi }}

                            <span class="text-xs text-gray-400">
                                transaksi
                            </span>

                        </td>


                        {{-- PENDAPATAN --}}
                        <td class="p-4 font-bold text-green-600">

                            Rp
                            {{ number_format(
                                $laporan->total_pendapatan ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>


                        {{-- DETAIL --}}
                        <td class="p-4 text-center">

                            <a
                                href="{{ route(
                                    'admin.pembayaran.detail',
                                    [
                                        $laporan->tahun,
                                        $laporan->bulan
                                    ]
                                ) }}"
                                class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm transition"
                            >
                                Detail
                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="4"
                            class="p-8 text-center text-gray-500"
                        >
                            Belum ada data pembayaran.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- =========================================================
        DETAIL PEMBAYARAN TERBARU
    ========================================================== --}}
    <div class="bg-white rounded-3xl shadow overflow-hidden">

        <div class="p-6 border-b">

            <h2 class="text-xl font-bold text-gray-800">
                Detail Pembayaran
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Daftar pembayaran berhasil terbaru.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-gray-100">

                    <tr>

                        <th class="p-4 text-left">
                            ID Booking
                        </th>

                        <th class="p-4 text-left">
                            Kontrakan
                        </th>

                        <th class="p-4 text-left">
                            Penyewa
                        </th>

                        <th class="p-4 text-left">
                            Periode
                        </th>

                        <th class="p-4 text-left">
                            Metode
                        </th>

                        <th class="p-4 text-left">
                            Tanggal Bayar
                        </th>

                        <th class="p-4 text-left">
                            Status
                        </th>

                        <th class="p-4 text-left">
                            Total
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($detailPembayaran as $item)

                    <tr class="border-b hover:bg-gray-50">

                        {{-- BOOKING ID --}}
                        <td class="p-4">

                            @if($item->booking)

                                <a
                                    href="{{ route(
                                        'admin.booking.detail',
                                        $item->booking->id
                                    ) }}"
                                    class="font-mono font-semibold text-blue-600 hover:underline"
                                >
                                    #{{ $item->booking->id }}
                                </a>

                            @else

                                <span class="text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- KONTRAKAN --}}
                        <td class="p-4">

                            <span class="font-semibold text-gray-800">
                                {{ $item->booking?->kamar?->nama_kamar ?? '-' }}
                            </span>

                        </td>


                        {{-- PENYEWA --}}
                        <td class="p-4">

                            <div class="font-semibold text-gray-800">
                                {{ $item->booking?->user?->nama_lengkap ?? '-' }}
                            </div>

                            @if($item->booking?->user?->email)

                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $item->booking->user->email }}
                                </div>

                            @endif

                        </td>


                        {{-- PERIODE --}}
                        <td class="p-4">

                            @if($item->periode_ke)

                                <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Periode {{ $item->periode_ke }}
                                </span>

                            @else

                                <span class="text-gray-500">
                                    -

                                </span>

                            @endif

                        </td>


                        {{-- METODE --}}
                        <td class="p-4">

                            @if($item->payment_type)

                                <span class="font-semibold text-gray-700">
                                    {{ strtoupper($item->payment_type) }}
                                </span>

                            @else

                                <span class="text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- TANGGAL BAYAR --}}
                        <td class="p-4">

                            @if($item->paid_at)

                                <div class="font-medium text-gray-700">

                                    {{ \Carbon\Carbon::parse($item->paid_at)
                                        ->timezone('Asia/Jakarta')
                                        ->format('d M Y') }}

                                </div>

                                <div class="text-xs text-gray-500">

                                    {{ \Carbon\Carbon::parse($item->paid_at)
                                        ->timezone('Asia/Jakarta')
                                        ->format('H:i') }}
                                    WIB

                                </div>

                            @else

                                <span class="text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- STATUS --}}
                        <td class="p-4">

                            @php
                                $status = $item->transaction_status ?? 'pending';
                            @endphp


                            @if($status === 'settlement')

                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Lunas
                                </span>

                            @elseif($status === 'capture')

                                <span class="inline-block bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Berhasil
                                </span>

                            @elseif($status === 'pending')

                                <span class="inline-block bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Pending
                                </span>

                            @elseif($status === 'expire')

                                <span class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Kedaluwarsa
                                </span>

                            @elseif(in_array($status, ['cancel', 'deny']))

                                <span class="inline-block bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    Gagal
                                </span>

                            @else

                                <span class="inline-block bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs font-semibold">
                                    {{ ucfirst($status) }}
                                </span>

                            @endif

                        </td>


                        {{-- TOTAL --}}
                        <td class="p-4 font-bold text-green-600 whitespace-nowrap">

                            Rp
                            {{ number_format(
                                $item->jumlah ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="8"
                            class="p-8 text-center text-gray-500"
                        >
                            Belum ada pembayaran berhasil.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection