@extends('layouts.adminlayouts')

@section('title', 'Detail Booking')

@section('content')

@php
    $status = $booking->payment->transaction_status ?? 'pending';
@endphp

<div class="max-w-5xl mx-auto">

    <!-- BACK -->
    <div class="mb-6">
        @include('components.back')
    </div>

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Detail Booking
        </h1>

        <p class="text-white/80 mt-2">
            Informasi lengkap booking dan pembayaran user
        </p>

    </div>

    <!-- CARD -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- TOP -->
        <div class="bg-amber-600 px-8 py-6 text-white">

            <h2 class="text-2xl font-bold">
                Booking #{{ $booking->id }}
            </h2>

            <p class="text-amber-100 mt-1">
                Dibuat pada {{ $booking->created_at->format('d M Y H:i') }}
            </p>

        </div>

        <!-- CONTENT -->
        <div class="p-8 grid md:grid-cols-2 gap-8">

            <!-- LEFT -->
            <div class="space-y-6">

                <!-- USER -->
                <div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Data Penyewa
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 border">

                        <div>
                            <p class="text-sm text-gray-500">Nama Lengkap</p>
                            <p class="font-semibold">{{ $booking->user->nama_lengkap ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Username</p>
                            <p class="font-semibold">{{ $booking->user->username ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Email</p>
                            <p class="font-semibold">{{ $booking->user->email ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">WhatsApp</p>
                            <p class="font-semibold"> {{ $booking->user->no_telp }}</p>
                        </div>

                        <td class="p-4">

@if($booking->foto_identitas)

    <img
        src="{{ asset('storage/'.$booking->foto_identitas) }}"
        onclick="showIdentity(this.src)"
        class="w-16 h-16 object-cover rounded-lg border cursor-pointer hover:scale-105 transition"
        title="Klik untuk memperbesar"
    >

@else

    <span class="text-gray-500 text-xs">
        Belum Upload
    </span>

@endif

</td>

                    </div>

                </div>

                <!-- KAMAR -->
                <div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Data Kamar
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 border">

                        <div>
                            <p class="text-sm text-gray-500">Nama Kamar</p>
                            <p class="font-semibold">{{ $booking->kamar->nama_kamar ?? '-' }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Harga per Bulan</p>
                            <p class="text-3xl font-extrabold text-amber-700">
    Rp {{ number_format($booking->payment->jumlah ?? 0, 0, ',', '.') }}
</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Durasi Sewa</p>
                            <p class="font-semibold">{{ $booking->durasi }} bulan</p>
                        </div>

                        <!-- Tanggal_masuk -->
                        <p class="text-sm text-gray-500">Tanggal masuk</p>
                        <td class="p-4 text-gray-500 text-sm">
    {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }}
</td>

                        <!-- Tanggal_selesai -->
                        <p class="text-sm text-gray-500">Tanggal Selesai</p>
                        <td class="p-4 text-gray-500 text-sm">
    {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
</td>

                    </div>

                </div>

            </div>

            <!-- RIGHT -->
            <div class="space-y-6">

                <!-- PEMBAYARAN -->
                <div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Pembayaran
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 space-y-4 border">

                        <div>
                            <p class="text-sm text-gray-500">Metode Pembayaran</p>
                            <p class="font-semibold">
                                {{ $booking->payment->payment_type ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Total Pembayaran</p>
                            <p class="text-3xl font-extrabold text-amber-700">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-2">Status Pembayaran</p>

                            @if($status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Pending
                                </span>

                            @elseif($status == 'settlement' || $status == 'capture')
                                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Paid
                                </span>

                            @elseif($status == 'expire')
                                <span class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Expired
                                </span>

                            @elseif($status == 'cancel' || $status == 'deny')
                                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Failed
                                </span>

                            @else
                                <span class="bg-black text-white px-4 py-2 rounded-full text-sm font-bold">
                                    Unknown
                                </span>
                            @endif

                        </div>

                    </div>

                </div>

                <!-- INFO MIDTRANS -->
                <div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Info Midtrans
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 border space-y-3">

                        <div>
                            <p class="text-sm text-gray-500">Order ID</p>
                            <p class="font-semibold">{{ $booking->order_id }}</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Transaction Status</p>
                            <p class="font-semibold">
    {{ $booking->payment->transaction_status ?? '-' }}
</p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">Paid At</p>
                            <p class="font-semibold">
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