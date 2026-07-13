@extends('layouts.adminlayouts')

@section('title', 'Kelola Pembayaran')

@section('content')

<div class="bg-gradient-to-r from-emerald-600 to-green-500 text-white rounded-3xl shadow p-8 mb-6">

    <h1 class="text-3xl font-bold">
        💰 Kelola Pembayaran
    </h1>

    <p class="mt-2 text-green-100">
        Monitoring transaksi pembayaran, pendapatan bulanan, dan laporan keuangan Kontrakan Raden Panghulu Djaja.
    </p>

</div>

<div class="space-y-6">

    {{-- Statistik --}}
    <div class="grid md:grid-cols-2 gap-4">

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Total Pendapatan</p>
            <h2 class="text-3xl font-bold text-green-600 mt-2">
                Rp {{ number_format($totalPendapatan,0,',','.') }}
            </h2>
        </div>

        <div class="bg-white rounded-2xl shadow p-6">
            <p class="text-gray-500">Total Transaksi Berhasil</p>
            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $totalTransaksi }}
            </h2>
        </div>

    </div>

    {{-- Laporan Bulanan --}}
    <div class="bg-white rounded-3xl shadow overflow-hidden">

        <div class="p-5 border-b">
            <h2 class="text-xl font-bold">
                Pendapatan Per Bulan
            </h2>
        </div>

        <table class="w-full">

            <thead class="bg-amber-600 text-white">
                <tr>
                    <th class="p-4 text-left">Bulan</th>
                    <th class="p-4 text-left">Jumlah Transaksi</th>
                    <th class="p-4 text-left">Pendapatan</th>
                </tr>
            </thead>

            <tbody>

            @forelse($laporanBulanan as $laporan)

                <tr class="border-b">

                    <td class="p-4">
                        {{ \Carbon\Carbon::create()
                            ->month($laporan->bulan)
                            ->translatedFormat('F') }}
                        {{ $laporan->tahun }}
                    </td>

                    <td class="p-4">
                        {{ $laporan->jumlah_transaksi }}
                    </td>

                    <td class="p-4 font-semibold text-green-600">
                        Rp {{ number_format($laporan->total_pendapatan,0,',','.') }}
                    </td>

                </tr>

            @empty

                <tr>
                    <td colspan="3" class="p-6 text-center text-gray-500">
                        Belum ada data pembayaran
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

    {{-- Detail Pembayaran --}}
    <div class="bg-white rounded-3xl shadow overflow-hidden">

        <div class="p-5 border-b">
            <h2 class="text-xl font-bold">
                Detail Pembayaran
            </h2>
        </div>

        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-4 text-left">Order ID</th>
                    <th class="p-4 text-left">Penyewa</th>
                    <th class="p-4 text-left">Metode</th>
                    <th class="p-4 text-left">Tanggal Bayar</th>
                    <th class="p-4 text-left">Total</th>
                </tr>
            </thead>

            <tbody>

@foreach($detailPembayaran as $item)

<tr class="border-b">

    <td class="p-4">
        {{ $item->order_id }}
    </td>


    <td class="p-4">
        {{ $item->booking->user->nama_lengkap ?? '-' }}
    </td>


    <td class="p-4">
        {{ strtoupper($item->payment_type ?? '-') }}
    </td>


    <td class="p-4">

        @if($item->paid_at)

            {{ \Carbon\Carbon::parse($item->paid_at)->format('d-m-Y H:i') }}

        @else

            -

        @endif

    </td>


    <td class="p-4 font-semibold text-green-600">
        Rp {{ number_format($item->jumlah ?? 0,0,',','.') }}
    </td>


</tr>

@endforeach

</tbody>

        </table>

    </div>

</div>

@endsection