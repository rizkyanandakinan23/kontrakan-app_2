@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')

<h1 class="text-3xl font-bold text-white mb-6">
    Riwayat Booking
</h1>

<div class="bg-white rounded-3xl shadow-xl overflow-hidden">
<div class="overflow-x-auto">
    <table class="w-full">
        <thead class="bg-amber-600 text-white">
            <tr>
                <th class="p-4 text-left whitespace-nowrap">Booking ID</th>
                <th class="p-4 text-left whitespace-nowrap">Kontrakan</th>
                <th class="p-4 text-left whitespace-nowrap">Durasi</th>
                <th class="p-4 text-left whitespace-nowrap">Total Booking</th>
                <th class="p-4 text-left whitespace-nowrap">Status Booking</th>
                <th class="p-4 text-left whitespace-nowrap">Periode Pembayaran</th>
                <th class="p-4 text-left whitespace-nowrap">Mulai</th>
                <th class="p-4 text-left whitespace-nowrap">Selesai</th>
                <th class="p-4 text-left whitespace-nowrap">Keterangan</th>
                <th class="p-4 text-left whitespace-nowrap">Aksi</th>
            </tr>
        </thead>
        <tbody>

        @forelse($bookings as $booking)
            @php
                /*
                |--------------------------------------------------------------------------
                | TANGGAL
                |--------------------------------------------------------------------------
                */
                $tanggalMasuk = \Carbon\Carbon::parse(
                    $booking->tanggal_masuk
                );

                $tanggalSelesai = \Carbon\Carbon::parse(
                    $booking->tanggal_selesai
                );

                /*
                |--------------------------------------------------------------------------
                | KETERANGAN BOOKING
                |--------------------------------------------------------------------------
                */
                $keterangan = $booking->keterangan;

                /*
                |--------------------------------------------------------------------------
                | PAYMENT
                |--------------------------------------------------------------------------
                */
                $payments = $booking->payments
                    ->sortBy('periode_ke')
                    ->values();

                /*
                |--------------------------------------------------------------------------
                | CARI PERIODE BERIKUTNYA
                |
                | Periode berikutnya adalah periode pertama
                | yang belum berhasil dibayar.
                |--------------------------------------------------------------------------
                */
                $periodeBerikutnya = null;
                $paymentBerikutnya = null;

                for ($i = 1; $i <= $booking->durasi; $i++) {
                    $payment = $payments->firstWhere(
                        'periode_ke',
                        $i
                    );

                    if (!$payment || $payment->status !== 'success') {
                        $periodeBerikutnya = $i;
                        $paymentBerikutnya = $payment;
                        break;
                    }
                }
            @endphp

            <tr class="border-b hover:bg-gray-50 align-top">

                <!-- ================================================= -->
                <!-- BOOKING ID -->
                <!-- ================================================= -->
                <td class="p-4 font-semibold text-gray-800">
                    #{{ $booking->id }}
                </td>

                <!-- ================================================= -->
                <!-- KONTRAKAN -->
                <!-- ================================================= -->
                <td class="p-4">
                    <a
                        href="{{ route('kamar.show', $booking->kamar->id) }}"
                        class="text-blue-600 font-semibold hover:underline"
                    >
                        {{ $booking->kamar->nama_kamar }}
                    </a>

                    {{-- REVIEW --}}
                    @if($booking->status === 'paid')
                        @if(\Carbon\Carbon::today()->gte($tanggalMasuk))
                            <div class="mt-2">
                                <a
                                    href="{{ route('kamar.show', $booking->kamar->id) }}#review"
                                    class="text-xs bg-amber-100 text-amber-700 px-3 py-1 rounded-xl hover:bg-amber-200"
                                >
                                    ⭐ Beri ulasan
                                </a>
                            </div>
                        @endif
                    @endif
                </td>

                <!-- ================================================= -->
                <!-- DURASI -->
                <!-- ================================================= -->
                <td class="p-4 whitespace-nowrap">
                    {{ $booking->durasi }} bulan
                </td>

                <!-- ================================================= -->
                <!-- TOTAL BOOKING -->
                <!-- ================================================= -->
                <td class="p-4 font-bold text-amber-700 whitespace-nowrap">
                    Rp {{ number_format(
                        $booking->total_harga,
                        0,
                        ',',
                        '.'
                    ) }}
                </td>

                <!-- ================================================= -->
                <!-- STATUS BOOKING -->
                <!-- ================================================= -->
                <td class="p-4">
                    @if($booking->status === 'cancel')
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm whitespace-nowrap">
                            Dibatalkan
                        </span>
                    @elseif($booking->status === 'paid')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm whitespace-nowrap">
                            Aktif
                        </span>
                    @else
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm whitespace-nowrap">
                            Menunggu Pembayaran
                        </span>
                    @endif
                </td>

                <!-- ================================================= -->
                <!-- PERIODE PEMBAYARAN -->
                <!-- ================================================= -->
                <td class="p-4">
                    <div class="space-y-3 min-w-[180px]">
                        @forelse($payments as $payment)
                            <div class="border rounded-xl p-3 bg-gray-50">
                                <div class="font-semibold text-gray-800">
                                    Periode ke-{{ $payment->periode_ke }}
                                </div>

                                <div class="text-sm text-gray-600 mt-1">
                                    Rp {{ number_format(
                                        $payment->jumlah,
                                        0,
                                        ',',
                                        '.'
                                    ) }}
                                </div>

                                <div class="text-xs text-gray-500 mt-1">
                                    {{ $payment->tanggal_periode_mulai
                                        ? $payment->tanggal_periode_mulai->format('d M Y')
                                        : '-'
                                    }}
                                    s/d
                                    {{ $payment->tanggal_periode_selesai
                                        ? $payment->tanggal_periode_selesai->format('d M Y')
                                        : '-'
                                    }}
                                </div>

                                @if($payment->status === 'success')
                                    <span class="inline-block mt-2 px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs">
                                        Lunas
                                    </span>

                                @elseif($payment->status === 'pending')
                                    <span class="inline-block mt-2 px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">
                                        Belum Dibayar
                                    </span>

                                @elseif($payment->status === 'failed')
                                    <span class="inline-block mt-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs">
                                        Gagal
                                    </span>
                                @else

                                    <span class="inline-block mt-2 px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">
                                        {{ ucfirst($payment->status) }}
                                    </span>

                                @endif

                            </div>

                        @empty

                            <span class="text-gray-500 text-sm">
                                Belum ada pembayaran
                            </span>

                        @endforelse

                    </div>
                </td>

                <!-- ================================================= -->
                <!-- MULAI -->
                <!-- ================================================= -->
                <td class="p-4 text-sm whitespace-nowrap">
                    {{ $tanggalMasuk->format('d M Y') }}
                </td>

                <!-- ================================================= -->
                <!-- SELESAI -->
                <!-- ================================================= -->

                <td class="p-4 text-sm whitespace-nowrap">
                    {{ $tanggalSelesai->format('d M Y') }}
                </td>

                <!-- ================================================= -->
                <!-- KETERANGAN -->
                <!-- ================================================= -->

                <td class="p-4">
                    @if($keterangan['color'] === 'yellow')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm whitespace-nowrap">
                            ⚠ {{ $keterangan['text'] }}
                        </span>

                    @elseif($keterangan['color'] === 'orange')
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm whitespace-nowrap">
                            ⚠ {{ $keterangan['text'] }}
                        </span>
                    @elseif($keterangan['color'] === 'blue')
                        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm whitespace-nowrap">
                            📅 {{ $keterangan['text'] }}
                        </span>
                    @elseif($keterangan['color'] === 'green')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm whitespace-nowrap">
                            ✅ {{ $keterangan['text'] }}
                        </span>

                    @elseif($keterangan['color'] === 'red')
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm whitespace-nowrap">
                            ❌ {{ $keterangan['text'] }}
                        </span>
                    @else

                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm whitespace-nowrap">
                            {{ $keterangan['text'] }}
                        </span>

                    @endif

                </td>

                <!-- ================================================= -->
            <!-- AKSI -->
            <!-- ================================================= -->
            <td class="p-4">
                <div class="space-y-2 min-w-[170px]">

                    {{-- ========================================== --}}
                    {{-- BOOKING DIBATALKAN --}}
                    {{-- ========================================== --}}
                    @if($booking->status === 'cancel')
                        <span class="text-sm text-gray-500">
                            Booking dibatalkan
                        </span>

                    {{-- ========================================== --}}
                    {{-- BOOKING BELUM PERNAH BAYAR --}}
                    {{-- ========================================== --}}
                    @elseif(!$payments->contains('status', 'success'))

                        {{-- LANJUT BAYAR --}}
                        @if($periodeBerikutnya !== null)
                            <a
                                href="{{ route('payment.next-period',$booking->id) }}"
                                class="inline-block bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm text-center"                >
                                @if($paymentBerikutnya && $paymentBerikutnya->status === 'pending')
                                    Lanjut Bayar
                                @else
                                    Bayar Sekarang
                                @endif
                            </a>
                        @endif

                        {{-- BATALKAN BOOKING --}}
                        <form
                            action="{{ route('booking.cancel', $booking->id) }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin membatalkan booking ini?')"
                        >
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                class="w-full bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm text-center"
                            >
                                Batalkan Booking
                            </button>
                        </form>

        {{-- ========================================== --}}
        {{-- SEMUA PERIODE LUNAS --}}
        {{-- ========================================== --}}
        @elseif($periodeBerikutnya === null)
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                Semua periode lunas
            </span>

        {{-- ========================================== --}}
        {{-- PERIODE BERIKUTNYA --}}
        {{-- ========================================== --}}
        @else

            <a
                href="{{ route(
                    'payment.next-period',
                    $booking->id
                ) }}"
                class="inline-block bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm text-center"
            >

                @if($paymentBerikutnya && $paymentBerikutnya->status === 'pending')

                    Lanjut Bayar Periode {{ $periodeBerikutnya }}

                @else

                    Bayar Periode {{ $periodeBerikutnya }}

                @endif
            </a>
        @endif

        {{-- ========================================== --}}
        {{-- INVOICE PAYMENT TERAKHIR --}}
        {{-- ========================================== --}}
        @if($payments->contains('status', 'success'))

            <a
                href="{{ route('payment.invoice', $booking->id) }}"
                class="inline-block bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg text-sm text-center"
            >
                🧾 Bukti Pembayaran
            </a>

        @endif

    </div>

</td>

            </tr>

        @empty

            <tr>
                <td   colspan="10"class="p-8 text-center text-gray-500">Belum ada booking </td>
            </tr>

        @endforelse
        </tbody>
    </table>

</div>

</div>

@endsection
