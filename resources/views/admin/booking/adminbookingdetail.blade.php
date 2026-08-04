@extends('layouts.adminlayouts')

@section('title', 'Detail Booking')

@section('content')

@php
    $status = $booking->payment->transaction_status ?? 'pending';
@endphp

<div class="max-w-5xl mx-auto">

    {{-- BACK BUTTON --}}
    <div class="mb-6">
        @include('components.back')
    </div>

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
                Dibuat pada {{ $booking->created_at->format('d M Y H:i') }}
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
                                {{ $booking->user->no_telp ?? '-' }}
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
                                Rp {{ number_format($booking->kamar->harga ?? 0, 0, ',', '.') }}
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

                        {{-- Tanggal Masuk --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Tanggal Masuk
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->tanggal_masuk
                                    ? \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y')
                                    : '-' }}
                            </p>
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Tanggal Selesai
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->tanggal_selesai
                                    ? \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y')
                                    : '-' }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>


            {{-- ========================================
                RIGHT COLUMN
            ========================================= --}}
            <div class="space-y-6">

                {{-- PEMBAYARAN --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Pembayaran
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 space-y-4 border">

                        {{-- Metode Pembayaran --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Metode Pembayaran
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->payment->payment_type ?? '-' }}
                            </p>
                        </div>

                        {{-- Total Pembayaran --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Total Pembayaran
                            </p>

                            <p class="text-3xl font-extrabold text-amber-700">
                                Rp {{ number_format($booking->total_harga ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        {{-- Status Pembayaran --}}
                        <div>
                            <p class="text-sm text-gray-500 mb-2">
                                Status Pembayaran
                            </p>

                            @if($status === 'pending')

                                <span class="inline-block bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Pending
                                </span>

                            @elseif(in_array($status, ['settlement', 'capture']))

                                <span class="inline-block bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Paid
                                </span>

                            @elseif($status === 'expire')

                                <span class="inline-block bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Expired
                                </span>

                            @elseif(in_array($status, ['cancel', 'deny']))

                                <span class="inline-block bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Failed
                                </span>

                            @else

                                <span class="inline-block bg-black text-white px-4 py-2 rounded-full text-sm font-bold">
                                    Unknown
                                </span>

                            @endif

                        </div>

                    </div>
                </div>


                {{-- INFO MIDTRANS --}}
                <div>
                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Info Midtrans
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 border space-y-4">

                        {{-- Order ID --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Order ID
                            </p>

                            <p class="font-semibold text-gray-800 break-all">
                                {{ $booking->payment->order_id ?? $booking->order_id ?? '-' }}
                            </p>
                        </div>

                        {{-- Transaction Status --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Transaction Status
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->payment->transaction_status ?? '-' }}
                            </p>
                        </div>

                        {{-- Paid At --}}
                        <div>
                            <p class="text-sm text-gray-500">
                                Paid At
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->payment->paid_at ?? '-' }}
                            </p>
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection