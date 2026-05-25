@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold text-white mb-6 drop-shadow">
        Riwayat Booking
    </h1>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-amber-600 text-white">

                <tr>
                    <th class="p-4 text-left">Kamar</th>
                    <th class="p-4 text-left">Durasi</th>
                    <th class="p-4 text-left">Total</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Bukti</th>
                    <th class="p-4 text-left">Tanggal</th>
                </tr>

            </thead>

            <tbody>

                @forelse($bookings as $booking)

                    <tr class="border-b hover:bg-gray-50 transition">

                        <!-- KAMAR -->
                        <td class="p-4 font-semibold text-gray-700">
                            {{ $booking->kamar->nama_kamar ?? '-' }}
                        </td>

                        <!-- DURASI -->
                        <td class="p-4">
                            {{ $booking->durasi }} bulan
                        </td>

                        <!-- TOTAL -->
                        <td class="p-4 text-amber-700 font-bold">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-4">

                            @if($booking->status_pembayaran == 'pending')
                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-sm font-semibold">
                                    Pending
                                </span>

                            @elseif($booking->status_pembayaran == 'dibayar')
                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-semibold">
                                    Dibayar
                                </span>

                            @elseif($booking->status_pembayaran == 'ditolak')
                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-sm font-semibold">
                                    Ditolak
                                </span>

                            @else
                                <span class="text-gray-400">-</span>
                            @endif

                        </td>

                        <!-- BUKTI PEMBAYARAN -->
                        <td class="p-4">

                            @if($booking->bukti_pembayaran)

                                <img
                                    src="{{ asset('storage/' . $booking->bukti_pembayaran) }}"
                                    class="w-20 h-20 object-cover rounded-lg border cursor-pointer hover:scale-105 transition"
                                    onclick="window.open(this.src)"
                                >

                            @else
                                <span class="text-gray-400 text-sm">
                                    Belum upload
                                </span>
                            @endif

                        </td>

                        <!-- TANGGAL -->
                        <td class="p-4 text-gray-500 text-sm">
                            {{ $booking->created_at->format('d M Y') }}
                        </td>

                    </tr>

                @empty

                    <tr>
                        <td colspan="6" class="p-8 text-center text-gray-500">
                            Belum ada booking
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection