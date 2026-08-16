@extends('layouts.adminlayouts')

@section('title', 'Detail Pendapatan Bulanan')

@section('content')

<div class="space-y-8">

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <div>

        <h1 class="text-3xl font-bold text-white drop-shadow-lg">
            📊 Detail Pendapatan Bulanan
        </h1>

        <p class="mt-2 text-white/80">
            Rincian seluruh transaksi pembayaran pada bulan
            <strong>{{ $namaBulan }} {{ $tahun }}</strong>.
        </p>

    </div>


    {{-- =========================================================
        RINGKASAN
    ========================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">

        {{-- TOTAL TRANSAKSI --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <p class="text-gray-500 text-sm">
                Total Transaksi
            </p>

            <h2 class="text-3xl font-bold text-blue-600 mt-2">
                {{ $detail->count() }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Pembayaran berhasil
            </p>

        </div>


        {{-- TOTAL PENDAPATAN --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <p class="text-gray-500 text-sm">
                Total Pendapatan
            </p>

            <h2 class="text-3xl font-bold text-green-600 mt-2 break-words">
                Rp {{ number_format(
                    $detail->sum('jumlah'),
                    0,
                    ',',
                    '.'
                ) }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Total pembayaran berhasil bulan ini
            </p>

        </div>


        {{-- RATA-RATA --}}
        <div class="bg-white rounded-2xl shadow p-6">

            <p class="text-gray-500 text-sm">
                Rata-rata Transaksi
            </p>

            <h2 class="text-3xl font-bold text-indigo-600 mt-2 break-words">
                Rp {{ number_format(
                    $detail->avg('jumlah') ?? 0,
                    0,
                    ',',
                    '.'
                ) }}
            </h2>

            <p class="text-xs text-gray-400 mt-2">
                Rata-rata nilai setiap pembayaran
            </p>

        </div>

    </div>



    {{-- =========================================================
        DAFTAR TRANSAKSI
    ========================================================== --}}
    <div class="bg-white rounded-3xl shadow overflow-hidden">

        <div class="p-6 border-b">

            <h2 class="text-xl font-bold text-gray-800">
                Daftar Transaksi
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Setiap transaksi mewakili satu pembayaran periode sewa.
            </p>

        </div>


        <div class="overflow-x-auto">

            <table class="min-w-full text-sm">

                <thead class="bg-emerald-600 text-white">

                    <tr>

                        <th class="p-4 text-center">
                            No
                        </th>

                        <th class="p-4 text-left">
                            Booking ID
                        </th>

                        <th class="p-4 text-left">
                            Tanggal Bayar
                        </th>

                        <th class="p-4 text-left">
                            Nama Penyewa
                        </th>

                        <th class="p-4 text-left">
                            Username
                        </th>

                        <th class="p-4 text-left">
                            Email
                        </th>

                        <th class="p-4 text-left">
                            Kontrakan
                        </th>

                        <th class="p-4 text-center">
                            Periode
                        </th>

                        <th class="p-4 text-center">
                            Mulai
                        </th>

                        <th class="p-4 text-center">
                            Selesai
                        </th>

                        <th class="p-4 text-center">
                            Durasi
                        </th>

                        <th class="p-4 text-left">
                            Metode
                        </th>

                        <th class="p-4 text-center">
                            Status
                        </th>

                        <th class="p-4 text-right">
                            Total
                        </th>

                    </tr>

                </thead>


                <tbody>

                @forelse($detail as $index => $item)

                    @php

                        $tanggalBayar = $item->paid_at
                            ? \Carbon\Carbon::parse($item->paid_at)
                                ->timezone('Asia/Jakarta')
                            : null;

                        $tanggalMulai = $item->booking?->tanggal_masuk
                            ? \Carbon\Carbon::parse(
                                $item->booking->tanggal_masuk
                            )
                            : null;

                        $tanggalSelesai = $item->booking?->tanggal_selesai
                            ? \Carbon\Carbon::parse(
                                $item->booking->tanggal_selesai
                            )
                            : null;

                        $status = $item->transaction_status ?? 'pending';

                    @endphp


                    <tr class="border-b hover:bg-gray-50">

                        {{-- NO --}}
                        <td class="p-4 text-center">
                            {{ $index + 1 }}
                        </td>


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


                        {{-- TANGGAL BAYAR --}}
                        <td class="p-4 whitespace-nowrap">

                            @if($tanggalBayar)

                                <div class="font-medium text-gray-700">
                                    {{ $tanggalBayar->translatedFormat('d F Y') }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    {{ $tanggalBayar->format('H:i') }} WIB
                                </div>

                            @else

                                <span class="text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- NAMA PENYEWA --}}
                        <td class="p-4">

                            <span class="font-semibold text-gray-800">
                                {{ $item->booking?->user?->nama_lengkap ?? '-' }}
                            </span>

                        </td>


                        {{-- USERNAME --}}
                        <td class="p-4">

                            {{ $item->booking?->user?->username ?? '-' }}

                        </td>


                        {{-- EMAIL --}}
                        <td class="p-4">

                            {{ $item->booking?->user?->email ?? '-' }}

                        </td>


                        {{-- KONTRAKAN --}}
                        <td class="p-4">

                            <span class="font-semibold text-gray-800">
                                {{ $item->booking?->kamar?->nama_kamar ?? '-' }}
                            </span>

                        </td>


                        {{-- PERIODE --}}
                        <td class="p-4 text-center">

                            @if($item->periode_ke)

                                <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-semibold whitespace-nowrap">
                                    Periode {{ $item->periode_ke }}
                                </span>

                            @else

                                <span class="text-gray-400">
                                    -
                                </span>

                            @endif

                        </td>


                        {{-- TANGGAL MULAI --}}
                        <td class="p-4 text-center whitespace-nowrap">

                            @if($tanggalMulai)

                                {{ $tanggalMulai->translatedFormat('d F Y') }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- TANGGAL SELESAI --}}
                        <td class="p-4 text-center whitespace-nowrap">

                            @if($tanggalSelesai)

                                {{ $tanggalSelesai->translatedFormat('d F Y') }}

                            @else

                                -

                            @endif

                        </td>


                        {{-- DURASI --}}
                        <td class="p-4 text-center whitespace-nowrap">

                            @if($item->booking?->durasi)

                                {{ $item->booking->durasi }} Bulan

                            @else

                                -

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


                        {{-- STATUS --}}
                        <td class="p-4 text-center">

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
                        <td class="p-4 text-right font-bold text-green-600 whitespace-nowrap">

                            Rp {{ number_format(
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
                            colspan="14"
                            class="p-8 text-center text-gray-500"
                        >
                            Belum ada transaksi pada bulan ini.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>



    {{-- =========================================================
        BACK
    ========================================================== --}}
    <div>

        <a
            href="{{ route('admin.pembayaran.index') }}"
            class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-5 py-2 rounded-lg transition"
        >
            ← Kembali ke Pembayaran
        </a>

    </div>

</div>

@endsection