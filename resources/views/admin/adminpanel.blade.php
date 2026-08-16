@extends('layouts.adminlayouts')

@section('title', 'Dashboard')

@section('content')

<div class="space-y-8">

{{-- =========================================================
    HEADER
========================================================== --}}
<div>
    <h1 class="text-4xl font-bold text-white drop-shadow-lg">
        Dashboard Admin
    </h1>

    <p class="text-white/80 mt-2">
        Monitoring sistem penyewaan Kontrakan Raden Panghulu Djaja
    </p>
</div>


{{-- =========================================================
    STATISTIK UTAMA
========================================================== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

    {{-- TOTAL USER --}}
    <a href="{{ route('admin.user.index') }}"
       class="block bg-white rounded-2xl shadow-sm p-5 hover:shadow-lg transition">

        <p class="text-gray-500 text-sm">
            Total User
        </p>

        <h2 class="text-3xl font-bold text-blue-600 mt-2">
            {{ $totalUser ?? 0 }}
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Akun penyewa terdaftar
        </p>

    </a>


    {{-- TOTAL KONTRAKAN --}}
    <a href="{{ route('admin.kamar.index') }}"
       class="block bg-white rounded-2xl shadow-sm p-5 hover:shadow-lg transition">

        <p class="text-gray-500 text-sm">
            Total Kontrakan
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-2">
            {{ $totalKamar ?? 0 }}
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Seluruh unit kontrakan
        </p>

    </a>


    {{-- RATA-RATA TRANSAKSI --}}
    <a href="{{ route('admin.pembayaran.index') }}"
       class="block bg-white rounded-2xl shadow-sm p-5 hover:shadow-lg transition">

        <p class="text-gray-500 text-sm">
            Rata-rata Transaksi / Bulan
        </p>

        <h2 class="text-3xl font-bold text-orange-600 mt-2">
            {{ $rataTransaksi ?? 0 }}
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Transaksi pembayaran berhasil
        </p>

    </a>


    {{-- TOTAL TRANSAKSI --}}
    <a href="{{ route('admin.pembayaran.index') }}"
       class="block bg-white rounded-2xl shadow-sm p-5 hover:shadow-lg transition">

        <p class="text-gray-500 text-sm">
            Total Transaksi
        </p>

        <h2 class="text-3xl font-bold text-purple-600 mt-2">
            {{ $totalTransaksi ?? 0 }}
        </h2>

        <p class="text-xs text-gray-400 mt-2">
            Pembayaran berhasil
        </p>

    </a>

</div>


{{-- =========================================================
    PENDAPATAN
========================================================== --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-5">

    {{-- RATA-RATA PENDAPATAN --}}
    <a href="{{ route('admin.pembayaran.index') }}"
       class="block bg-white rounded-2xl shadow-sm p-6
              hover:shadow-lg transition">

        <p class="text-gray-500 text-sm">
            Rata-rata Pendapatan / Bulan
        </p>

        <h2 class="text-3xl font-bold text-indigo-600 mt-2 break-words">
            Rp {{ number_format($rataPendapatan ?? 0, 0, ',', '.') }}
        </h2>

    </a>

</div>


{{-- =========================================================
    GRAFIK PENDAPATAN
========================================================== --}}
<div class="bg-white rounded-3xl shadow overflow-hidden">

    <div class="p-6 border-b">

        <a href="{{ route('admin.pembayaran.index') }}"
           class="block">

            <h2 class="text-xl font-bold text-gray-800">
                Grafik Pendapatan Bulanan
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Perkembangan pendapatan berdasarkan transaksi berhasil
            </p>

        </a>

    </div>

    <div class="p-6">

        @if(count($labelGrafik ?? []) > 0)

            <div class="relative w-full" style="height: 350px;">
                <canvas id="pendapatanChart"></canvas>
            </div>

        @else

            <div class="text-center text-gray-500 py-12">

                <div class="text-4xl mb-3">
                    📊
                </div>

                <p class="font-semibold">
                    Belum ada data pendapatan
                </p>

                <p class="text-sm mt-1">
                    Grafik akan muncul setelah terdapat transaksi pembayaran berhasil.
                </p>

            </div>

        @endif

    </div>

</div>


{{-- =========================================================
    BOOKING TERBARU
========================================================== --}}
<div class="bg-white rounded-3xl shadow overflow-hidden">

    <div class="p-6 border-b flex items-center justify-between">

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                Booking Terbaru
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Aktivitas booking terbaru dari penyewa
            </p>

        </div>

        <a href="{{ route('admin.booking.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">
            Lihat Semua →
        </a>

    </div>


    <div class="p-6 space-y-3">

        @forelse($bookingTerbaru ?? [] as $booking)

            <a href="{{ route('admin.booking.detail', $booking->id) }}"
               class="block">

                <div class="flex items-start gap-4 p-4 rounded-2xl border
                    hover:bg-gray-50 transition">

                    {{-- ICON --}}
                    <div class="w-10 h-10 rounded-xl
                        flex items-center justify-center shrink-0
                        {{ $booking->status_pembayaran == 'dibayar'
                            ? 'bg-green-100 text-green-600'
                            : 'bg-yellow-100 text-yellow-600' }}">

                        🔔

                    </div>


                    {{-- INFORMASI --}}
                    <div class="min-w-0 flex-1">

                        <p class="text-sm text-gray-800">

                            <span class="font-semibold">
                                {{ $booking->user?->nama_lengkap ?? 'User tidak ditemukan' }}
                            </span>

                            melakukan booking kontrakan

                            <span class="font-semibold text-amber-700">
                                {{ $booking->kamar?->nama_kamar ?? 'Kamar tidak ditemukan' }}
                            </span>

                        </p>


                        <div class="flex flex-wrap items-center gap-2 mt-2">

                            <span class="text-xs text-gray-500">
                                Booking #{{ $booking->id }}
                            </span>

                            @if($booking->payment?->transaction_status === 'settlement')

                                <span class="bg-green-100 text-green-700
                                             px-2 py-1 rounded-full text-xs font-semibold">
                                    Lunas
                                </span>

                            @elseif($booking->payment?->transaction_status === 'pending')

                                <span class="bg-yellow-100 text-yellow-700
                                             px-2 py-1 rounded-full text-xs font-semibold">
                                    Menunggu Pembayaran
                                </span>

                            @elseif($booking->payment)

                                <span class="bg-red-100 text-red-700
                                             px-2 py-1 rounded-full text-xs font-semibold">
                                    {{ ucfirst($booking->payment->transaction_status) }}
                                </span>

                            @else

                                <span class="bg-gray-100 text-gray-600
                                             px-2 py-1 rounded-full text-xs font-semibold">
                                    Belum Ada Pembayaran
                                </span>

                            @endif

                        </div>


                        <p class="text-xs text-gray-400 mt-2">
                            {{ $booking->created_at?->diffForHumans() ?? '-' }}
                        </p>

                    </div>

                </div>

            </a>

        @empty

            <div class="text-center text-gray-500 py-10">

                <div class="text-4xl mb-3">
                    📭
                </div>

                <p class="font-semibold">
                    Belum ada booking terbaru
                </p>

            </div>

        @endforelse

    </div>

</div>


{{-- =========================================================
    USER TERBARU
========================================================== --}}
<div class="bg-white rounded-3xl shadow overflow-hidden">

    <div class="p-6 border-b flex items-center justify-between">

        <div>

            <h2 class="text-xl font-bold text-gray-800">
                Daftar Akun Penyewa Terbaru
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Pengguna yang baru melakukan registrasi
            </p>

        </div>

        <a href="{{ route('admin.user.index') }}"
           class="text-sm font-semibold text-blue-600 hover:text-blue-800">
            Lihat Semua →
        </a>

    </div>


    <div class="overflow-x-auto">

        <table class="w-full text-sm">

            <thead class="bg-amber-600 text-white">

                <tr>

                    <th class="p-4 text-left">
                        Nama
                    </th>

                    <th class="p-4 text-left">
                        Username
                    </th>

                    <th class="p-4 text-left">
                        Email
                    </th>

                    <th class="p-4 text-left">
                        Tanggal Registrasi
                    </th>

                </tr>

            </thead>


            <tbody>

                @forelse($users ?? [] as $user)

                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-4 font-semibold text-gray-800">
                            {{ $user->nama_lengkap ?? '-' }}
                        </td>

                        <td class="p-4 text-gray-700">
                            {{ $user->username ?? '-' }}
                        </td>

                        <td class="p-4 text-gray-700">
                            {{ $user->email ?? '-' }}
                        </td>

                        <td class="p-4 text-gray-500">

                            {{ $user->created_at
                                ? $user->created_at->format('d M Y H:i')
                                : '-' }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="4"
                            class="p-8 text-center text-gray-500">

                            Belum ada data user.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

</div>

{{-- =========================================================
CHART JS
========================================================= --}}
@if(count($labelGrafik ?? []) > 0)

<script>
document.addEventListener('DOMContentLoaded', function () {

    const canvas = document.getElementById('pendapatanChart');

    if (!canvas) {
        return;
    }

    new Chart(canvas, {
        type: 'bar',

        data: {
            labels: @json($labelGrafik),

            datasets: [{
                label: 'Pendapatan',
                data: @json($dataGrafik),

                borderWidth: 1
            }]
        },

        options: {
            responsive: true,
            maintainAspectRatio: false,

            scales: {
                y: {
                    beginAtZero: true,

                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                        }
                    }
                }
            },

            plugins: {
                legend: {
                    display: true
                },

                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Rp ' +
                                new Intl.NumberFormat('id-ID')
                                    .format(context.raw);
                        }
                    }
                }
            }
        }
    });

});
</script>

@endif

@endsection
