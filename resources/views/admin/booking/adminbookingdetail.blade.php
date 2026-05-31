@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')

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
                Dibuat pada
                {{ $booking->created_at->format('d M Y H:i') }}
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
                            <p class="text-sm text-gray-500">
                                Nama Lengkap
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->user->nama_lengkap ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Username
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->user->username ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Email
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->user->email ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                WhatsApp
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->whatsapp }}
                            </p>
                        </div>

                    </div>

                </div>

                <!-- KAMAR -->
                <div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Data Kamar
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 space-y-3 border">

                        <div>
                            <p class="text-sm text-gray-500">
                                Nama Kamar
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->kamar->nama_kamar ?? '-' }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Harga per Bulan
                            </p>

                            <p class="font-semibold text-amber-700">
                                Rp {{ number_format($booking->kamar->harga ?? 0, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Durasi Sewa
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->durasi }} bulan
                            </p>
                        </div>

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
                            <p class="text-sm text-gray-500">
                                Metode Pembayaran
                            </p>

                            <p class="font-semibold text-gray-800">
                                {{ $booking->metode_pembayaran }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500">
                                Total Pembayaran
                            </p>

                            <p class="text-3xl font-extrabold text-amber-700">
                                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                            </p>
                        </div>

                        <div>
                            <p class="text-sm text-gray-500 mb-2">
                                Status Pembayaran
                            </p>

                            @if($booking->status_pembayaran == 'pending')

                                <span class="bg-yellow-100 text-yellow-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Pending
                                </span>

                            @elseif($booking->status_pembayaran == 'dibayar' || $booking->status_pembayaran == 'settlement')

                                <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Paid
                                </span>

                            @elseif($booking->status_pembayaran == 'expired')

                                <span class="bg-gray-200 text-gray-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Expired
                                </span>

                            @else

                                <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold">
                                    Failed
                                </span>

                            @endif

                        </div>

                    </div>

                </div>

                <!-- BUKTI -->
                <div>

                    <h3 class="text-lg font-bold text-gray-800 mb-3">
                        Bukti Pembayaran
                    </h3>

                    <div class="bg-gray-50 rounded-2xl p-5 border">

                        @if($booking->bukti_pembayaran)

                            <img
                                src="{{ asset('storage/' . $booking->bukti_pembayaran) }}"
                                class="w-full rounded-2xl shadow border"
                            >

                        @else

                            <div class="text-center py-10 text-gray-400">

                                Belum ada bukti pembayaran

                            </div>

                        @endif

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection