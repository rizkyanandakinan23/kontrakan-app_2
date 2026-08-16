@extends('layouts.adminlayouts')

@section('title', 'Detail Booking')

@section('content')

@php


/*
|--------------------------------------------------------------------------
| PAYMENT PER PERIODE
|--------------------------------------------------------------------------
*/

$payments = $booking->payments
    ->sortBy('periode_ke')
    ->values();

$totalDibayar = $payments
    ->where('status', 'success')
    ->sum('jumlah');

$jumlahPeriodeLunas = $payments
    ->where('status', 'success')
    ->count();


@endphp

<div class="max-w-5xl mx-auto">

{{-- HEADER --}}
<div class="mb-8">

    <h1 class="text-4xl font-bold text-white drop-shadow-lg">
        Detail Booking
    </h1>

    <p class="text-white/80 mt-2">
        Informasi lengkap booking dan pembayaran penyewa
    </p>

</div>


{{-- MAIN CARD --}}
<div class="bg-white rounded-3xl shadow-2xl overflow-hidden">


    {{-- TOP HEADER --}}

    <div class="bg-amber-600 px-8 py-6 text-white">

        <h2 class="text-2xl font-bold">
            Booking #{{ $booking->id }}
        </h2>

        <p class="text-amber-100 mt-1">

            Dibuat pada

            {{ $booking->created_at
                ? $booking->created_at
                    ->timezone('Asia/Jakarta')
                    ->format('d M Y H:i')
                : '-'
            }}

        </p>

    </div>


    {{-- CONTENT --}}

    <div class="p-8 grid md:grid-cols-2 gap-8">


        {{-- ========================================
            LEFT COLUMN
        ========================================= --}}

        <div class="flex flex-col gap-6">


            {{-- DATA PENYEWA --}}

            <div>

                <h3 class="text-lg font-bold text-gray-800 mb-3">
                    Data Penyewa
                </h3>


                <div class="bg-gray-50 rounded-2xl p-5 space-y-4 border">


                    {{-- Nama Lengkap --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Nama Lengkap
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $booking->user->nama_lengkap ?? '-' }}
                        </p>

                    </div>


                    {{-- Username --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Username
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $booking->user->username ?? '-' }}
                        </p>

                    </div>


                    {{-- Email --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Email
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $booking->user->email ?? '-' }}
                        </p>

                    </div>


                    {{-- WhatsApp --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            WhatsApp
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $booking->user->no_telp ?? $booking->whatsapp ?? '-' }}
                        </p>

                    </div>


                    {{-- Foto Identitas --}}

                    <div>

                        <p class="text-sm text-gray-500 mb-2">
                            Foto Identitas
                        </p>


                        @if($booking->foto_identitas)

                            <img
                                src="{{ asset('storage/' . $booking->foto_identitas) }}"
                                onclick="showIdentity(this.src)"
                                class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition"
                                title="Klik untuk memperbesar"
                                alt="Foto Identitas"
                            >

                        @else

                            <span class="text-gray-500 text-sm">
                                Belum Upload
                            </span>

                        @endif

                    </div>


                </div>

            </div>



            {{-- DATA KAMAR --}}

            <div>

                <h3 class="text-lg font-bold text-gray-800 mb-3">
                    Data Kontrakan
                </h3>


                <div class="bg-gray-50 rounded-2xl p-5 space-y-4 border">


                    {{-- Nama Kamar --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Nama Kontrakan
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $booking->kamar->nama_kamar ?? '-' }}
                        </p>

                    </div>


                    {{-- Harga per Bulan --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Harga per Bulan
                        </p>

                        <p class="text-2xl font-extrabold text-amber-700">

                            Rp {{ number_format(
                                $booking->kamar->harga ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>


                    {{-- Durasi Sewa --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Durasi Sewa
                        </p>

                        <p class="font-semibold text-gray-800">
                            {{ $booking->durasi }} bulan
                        </p>

                    </div>


                    {{-- Total Booking --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Total Booking
                        </p>

                        <p class="font-bold text-amber-700">

                            Rp {{ number_format(
                                $booking->total_harga ?? 0,
                                0,
                                ',',
                                '.'
                            ) }}

                        </p>

                    </div>


                    {{-- Tanggal Masuk --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Tanggal Masuk
                        </p>

                        <p class="font-semibold text-gray-800">

                            {{ $booking->tanggal_masuk
                                ? \Carbon\Carbon::parse(
                                    $booking->tanggal_masuk
                                )->format('d M Y')
                                : '-'
                            }}

                        </p>

                    </div>


                    {{-- Tanggal Selesai --}}

                    <div>

                        <p class="text-sm text-gray-500">
                            Tanggal Selesai
                        </p>

                        <p class="font-semibold text-gray-800">

                            {{ $booking->tanggal_selesai
                                ? \Carbon\Carbon::parse(
                                    $booking->tanggal_selesai
                                )->format('d M Y')
                                : '-'
                            }}

                        </p>

                    </div>


                    {{-- Status Booking --}}

                    <div>

                        <p class="text-sm text-gray-500 mb-2">
                            Status Booking
                        </p>


                        @if($booking->status === 'cancel')

                            <span class="inline-block
                                bg-red-100
                                text-red-700
                                px-4 py-2
                                rounded-full
                                text-sm font-bold">

                                Dibatalkan

                            </span>


                        @elseif($booking->status === 'paid')

                            <span class="inline-block
                                bg-green-100
                                text-green-700
                                px-4 py-2
                                rounded-full
                                text-sm font-bold">

                                Aktif

                            </span>


                        @else

                            <span class="inline-block
                                bg-yellow-100
                                text-yellow-700
                                px-4 py-2
                                rounded-full
                                text-sm font-bold">

                                Menunggu Pembayaran

                            </span>

                        @endif

                    </div>


                </div>

            </div>

        </div>



        {{-- ========================================
            RIGHT COLUMN
        ========================================= --}}

        <div class="space-y-6">


            {{-- PEMBAYARAN PER PERIODE --}}

            <div>

                <h3 class="text-lg font-bold text-gray-800 mb-3">
                    Pembayaran Per Periode
                </h3>


                <div class="bg-gray-50 rounded-2xl p-5 border space-y-4">


                    @forelse($payments as $payment)


                        <div class="bg-white border rounded-xl p-4">


                            {{-- HEADER PERIODE --}}

                            <div class="flex justify-between items-center">

                                <div>

                                    <p class="text-sm text-gray-500">
                                        Periode Pembayaran
                                    </p>

                                    <p class="font-bold text-gray-800">
                                        Periode ke-{{ $payment->periode_ke }}
                                    </p>

                                </div>


                                {{-- STATUS --}}

                                @if($payment->status === 'success')

                                    <span class="bg-green-100
                                        text-green-700
                                        px-3 py-1
                                        rounded-full
                                        text-xs font-bold">

                                        Lunas

                                    </span>


                                @elseif($payment->status === 'pending')

                                    <span class="bg-yellow-100
                                        text-yellow-700
                                        px-3 py-1
                                        rounded-full
                                        text-xs font-bold">

                                        Pending

                                    </span>


                                @elseif($payment->status === 'failed')

                                    <span class="bg-red-100
                                        text-red-700
                                        px-3 py-1
                                        rounded-full
                                        text-xs font-bold">

                                        Gagal

                                    </span>


                                @else

                                    <span class="bg-gray-200
                                        text-gray-700
                                        px-3 py-1
                                        rounded-full
                                        text-xs font-bold">

                                        {{ ucfirst($payment->status ?? '-') }}

                                    </span>

                                @endif

                            </div>


                            <div class="mt-4 space-y-3">


                                {{-- JUMLAH --}}

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Jumlah Pembayaran
                                    </p>

                                    <p class="font-bold text-amber-700">

                                        Rp {{ number_format(
                                            $payment->jumlah ?? 0,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    </p>

                                </div>


                                {{-- METODE --}}

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Metode Pembayaran
                                    </p>

                                    <p class="font-semibold text-gray-800">

                                        {{ $payment->payment_type ?? 'Belum dibayar' }}

                                    </p>

                                </div>


                                {{-- ORDER ID --}}

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Order ID
                                    </p>

                                    <p class="text-sm font-semibold
                                        text-gray-800 break-all">

                                        {{ $payment->order_id ?? '-' }}

                                    </p>

                                </div>


                                {{-- TRANSACTION STATUS --}}

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Transaction Status
                                    </p>

                                    <p class="font-semibold text-gray-800">

                                        {{ $payment->transaction_status ?? '-' }}

                                    </p>

                                </div>


                                {{-- WAKTU BAYAR --}}

                                <div>

                                    <p class="text-xs text-gray-500">
                                        Waktu Pembayaran
                                    </p>

                                    <p class="font-semibold text-gray-800">

                                        {{ $payment->paid_at
                                            ? \Carbon\Carbon::parse(
                                                $payment->paid_at
                                            )
                                            ->timezone('Asia/Jakarta')
                                            ->format('d M Y H:i')
                                            : '-'
                                        }}

                                    </p>

                                </div>


                            </div>

                        </div>


                    @empty

                        <div class="text-center py-6">

                            <p class="text-gray-500">
                                Belum ada pembayaran.
                            </p>

                        </div>

                    @endforelse


                    {{-- RINGKASAN PEMBAYARAN --}}

                    @if($payments->isNotEmpty())

                        <div class="border-t pt-4 space-y-2">


                            <div class="flex justify-between">

                                <span class="text-sm text-gray-500">
                                    Periode Lunas
                                </span>

                                <span class="font-bold text-gray-800">

                                    {{ $jumlahPeriodeLunas }}
                                    / {{ $booking->durasi }}

                                </span>

                            </div>


                            <div class="flex justify-between">

                                <span class="text-sm text-gray-500">
                                    Total Dibayar
                                </span>

                                <span class="font-bold text-green-700">

                                    Rp {{ number_format(
                                        $totalDibayar,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </div>


                            <div class="flex justify-between">

                                <span class="text-sm text-gray-500">
                                    Sisa Pembayaran
                                </span>

                                <span class="font-bold text-amber-700">

                                    Rp {{ number_format(
                                        max(
                                            0,
                                            ($booking->total_harga ?? 0)
                                            - $totalDibayar
                                        ),
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </div>


                        </div>

                    @endif


                </div>

            </div>


        </div>


    </div>

</div>


</div>

@endsection
