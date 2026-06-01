@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')

@php
    $status = $booking->transaction_status ?? 'pending';
@endphp

<div class="max-w-6xl mx-auto">

    <div class="mb-6">
        @include('components.back')
    </div>

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
                    <th class="p-4 text-left">Metode</th>
                    <th class="p-4 text-left">Tanggal</th>
                </tr>
            </thead>

            <tbody>

            @forelse($bookings as $booking)

            @php
                $status = $booking->transaction_status ?? 'pending';
            @endphp

            <!-- MAIN ROW -->
            <tr class="border-b hover:bg-gray-50">

                <td class="p-4 font-semibold text-gray-700">
                    {{ $booking->kamar->nama_kamar ?? '-' }}
                </td>

                <td class="p-4">
                    {{ $booking->durasi }} bulan
                </td>

                <td class="p-4 text-amber-700 font-bold">
                    Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                </td>

                <td class="p-4">

                    @if($status == 'pending')
                        <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-sm">
                            Pending
                        </span>

                    @elseif($status == 'settlement' || $status == 'capture')
                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-sm">
                            Lunas
                        </span>

                    @elseif($status == 'expire')
                        <span class="px-3 py-1 bg-gray-200 text-gray-700 rounded-full text-sm">
                            Expired
                        </span>

                    @elseif($status == 'cancel' || $status == 'deny')
                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">
                            Dibatalkan
                        </span>

                    @else
                        <span class="px-3 py-1 bg-black text-white rounded-full text-sm">
                            Unknown
                        </span>
                    @endif

                </td>

                <td class="p-4 text-sm text-gray-600">
                    {{ $booking->payment_type ?? 'Midtrans' }}
                </td>

                <td class="p-4 text-gray-500 text-sm">
                    {{ $booking->created_at->format('d M Y') }}
                </td>

            </tr>

            <!-- ACTION -->
            @if($status == 'pending')
            <tr class="bg-gray-50 border-b">
                <td colspan="6" class="px-4 py-3">

                    <div class="flex gap-2 flex-wrap">

                        <!-- BAYAR ULANG -->
                        @if($booking->snap_token)
                            <button
                                onclick="payAgain('{{ $booking->snap_token }}')"
                                class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg text-sm">
                                Bayar
                            </button>
                        @endif

                        <!-- CANCEL -->
                        <form action="{{ route('booking.cancel', $booking->id) }}"
                              method="POST">
                            @csrf
                            @method('PATCH')

                            <button
    type="submit"
    onclick="return confirm('Yakin batalkan booking?')"
    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm">
    Batalkan
</button>

                        </form>

                    </div>

                </td>
            </tr>
            @endif

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

<!-- MIDTRANS -->
<script src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.client_key') }}"></script>

<script>
function payAgain(token){

    snap.pay(token, {

        onSuccess: function(){
            location.reload();
        },

        onPending: function(){
            location.reload();
        },

        onError: function(){
            alert('Pembayaran gagal');
        }

    });

}
</script>

@endsection