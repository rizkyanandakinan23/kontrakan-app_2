@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')

<div class="max-w-6xl mx-auto">

    <h1 class="text-3xl font-bold text-white mb-6">
        Riwayat Booking
    </h1>

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden">

        <table class="w-full">

            <thead class="bg-amber-600 text-white">
                <tr>
                    <th class="p-4 text-left">Tanggal Pembayaran</th>
                    <th class="p-4 text-left">Booking ID</th>
                    <th class="p-4 text-left">Kontrakan</th>
                    <th class="p-4 text-left">Durasi</th>
                    <th class="p-4 text-left">Total</th>
                    <th class="p-4 text-left">Status</th>
                    <th class="p-4 text-left">Metode</th>
                    <th class="p-4 text-left">Mulai</th>
                    <th class="p-4 text-left">Selesai</th>
                    <th class="p-4 text-left">Keterangan</th>
                </tr>
            </thead>

            <tbody>

            @forelse($bookings as $booking)

@php

    $status = $booking->status == 'cancel'
        ? 'cancel'
        : ($booking->payment?->status ?? 'pending');

    $tanggalMasuk = \Carbon\Carbon::parse(
        $booking->tanggal_masuk
    );

    $keterangan = $booking->keterangan;

@endphp


<tr class="border-b hover:bg-gray-50">

    <!-- TANGGAL PEMBAYARAN -->
    <td class="p-4 text-sm">

        {{ $booking->payment?->created_at?->setTimezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}

    </td>

    <!-- BOOKING ID -->
    <td class="p-4 font-semibold text-gray-800">
        #{{ $booking->id }}
    </td>

    <!-- KAMAR -->
    <td class="p-4">

        <a href="{{ route('kamar.show', $booking->kamar->id) }}"
           class="text-blue-600 font-semibold hover:underline">

            {{ $booking->kamar->nama_kamar }}

        </a>


        @if($status === 'success')

    @if(\Carbon\Carbon::today()->gte($tanggalMasuk))

        <div class="mt-2">

            {{-- SUDAH MEMASUKI MASA SEWA --}}
            <a
                href="{{ route('kamar.show', $booking->kamar->id) }}#review"
                class="text-xs bg-amber-100 text-amber-700 px-3 py-1 rounded-xl hover:bg-amber-200">

                ⭐ Beri ulasan

            </a>

        </div>

    @endif

@endif


    <!-- DURASI -->
    <td class="p-4">
        {{ $booking->durasi }} bulan
    </td>



    <!-- TOTAL -->
    <td class="p-4 font-bold text-amber-700">

        Rp {{ number_format($booking->total_harga,0,',','.') }}

    </td>




    <!-- STATUS -->
    <td class="p-4">


        @if($status == 'pending')

            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                Pending
            </span>


        @elseif($status == 'success')

            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                Lunas
            </span>


        @elseif($status == 'cancel')

            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                Dibatalkan
            </span>


        @elseif($status == 'failed')

            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                Gagal
            </span>


        @endif


    </td>




    <!-- METODE -->
    <td class="p-4 text-sm text-gray-600">

        {{ $booking->payment?->payment_type ?? 'Midtrans' }}

    </td>




    <!-- MULAI -->
    <td class="p-4 text-sm">

        {{ $tanggalMasuk->format('d M Y') }}

    </td>




    <!-- SELESAI -->
    <td class="p-4 text-sm">

        {{ \Carbon\Carbon::parse($booking->tanggal_selesai)
            ->format('d M Y') }}

    </td>




   <!-- KETERANGAN -->
<td class="p-4">

    @if($keterangan['color'] == 'yellow')

        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
            ⚠ {{ $keterangan['text'] }}
        </span>

    @elseif($keterangan['color'] == 'orange')

        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-sm">
            ⚠ {{ $keterangan['text'] }}
        </span>

    @elseif($keterangan['color'] == 'blue')

        <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm">
            📅 {{ $keterangan['text'] }}
        </span>

    @elseif($keterangan['color'] == 'green')

        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
            ✅ {{ $keterangan['text'] }}
        </span>

    @elseif($keterangan['color'] == 'red')

        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
            ❌ {{ $keterangan['text'] }}
        </span>

    @else

        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">
            {{ $keterangan['text'] }}
        </span>

    @endif
    </td>


</tr>



<!-- ACTION -->

<tr class="bg-gray-50 border-b">

<td colspan="8" class="p-4">


<div class="flex gap-2 flex-wrap">

    @if($booking->payment && $status == 'success')

<a href="{{ route('payment.invoice', $booking->id) }}"
   class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm">

    🧾 Lihat Bukti Pembayaran

</a>

@endif


@if($status == 'pending')


    @if($booking->payment?->snap_token)

        <button onclick="payAgain('{{ $booking->payment->snap_token }}')"
        class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm">

            Bayar

        </button>

    @endif



    <form action="{{ route('booking.cancel',$booking->id) }}"
          method="POST">

        @csrf
        @method('PATCH')


        <button
        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">

            Batalkan

        </button>


    </form>


@endif


</div>


</td>

</tr>

            @empty

                <tr>
                    <td colspan="8" class="p-8 text-center text-gray-500">
                        Belum ada booking
                    </td>
                </tr>

            @endforelse

            </tbody>

        </table>

    </div>
</div>

<!-- MIDTRANS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
function payAgain(token){
    snap.pay(token, {
        onSuccess: () => location.href='?success=1',
        onPending: () => location.href='?pending=1',
        onError: () => location.href='?error=1'
    });
}
</script>

@endsection