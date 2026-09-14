@extends('layouts.app')

@section('title', 'Ajukan Pembatalan Booking')

@section('content')

<div class="max-w-3xl mx-auto">
    <div class="bg-white rounded-3xl shadow-xl p-6 md:p-8">


    <h1 class="text-2xl font-bold text-gray-800 mb-6">
        Ajukan Pembatalan Booking
    </h1>

    <div class="bg-gray-50 rounded-2xl p-5 space-y-3">
        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Booking ID</span>
            <span class="font-semibold text-gray-800">#{{ $booking->id }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Kontrakan</span>
            <span class="font-semibold text-gray-800">{{ $booking->kamar->nama_kamar }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Tanggal Masuk</span>
            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Tanggal Selesai</span>
            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Durasi</span>
            <span class="font-semibold text-gray-800">{{ $booking->durasi }} bulan</span>
        </div>

        <div class="flex justify-between gap-4">
            <span class="text-gray-500">Total Booking</span>
            <span class="font-semibold text-amber-700">Rp {{ number_format($booking->total_harga, 0, ',', '.') }}</span>
        </div>
    </div>

    <div class="mt-6 bg-orange-50 border border-orange-200 rounded-2xl p-5">
        <h2 class="font-semibold text-orange-700 mb-2">
            Perhatian
        </h2>

        <p class="text-sm text-orange-700 leading-relaxed">
            Pengajuan pembatalan tidak langsung membatalkan booking.
            Permintaan akan diproses terlebih dahulu oleh pengelola.
            Booking tetap aktif sampai pengelola menyetujui permintaan pembatalan.
        </p>
    </div>

    <form action="{{ route('booking.request-cancellation', $booking->id) }}" method="POST" class="mt-6">
        @csrf
        @method('PATCH')

        <label class="flex items-start gap-3 cursor-pointer">
            <input
                type="checkbox"
                name="confirmation"
                value="1"
                required
                class="mt-1 rounded border-gray-300 text-amber-600 focus:ring-amber-500"
            >

            <span class="text-sm text-gray-700">
                Saya memahami bahwa pengajuan ini merupakan permintaan pembatalan
                dan pembatalan baru berlaku setelah dikonfirmasi oleh pengelola.
            </span>
        </label>

        @error('confirmation')
            <p class="text-sm text-red-600 mt-2">{{ $message }}</p>
        @enderror

        <div class="flex gap-3 mt-6">
            <a
                href="{{ route('booking.riwayat') }}"
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-700 px-4 py-3 rounded-xl text-center font-semibold"
            >
                Kembali
            </a>

            <button
                type="submit"
                class="flex-1 bg-red-600 hover:bg-red-700 text-white px-4 py-3 rounded-xl font-semibold"
            >
                Ajukan Pembatalan
            </button>
        </div>
    </form>

</div>


</div>

@endsection
