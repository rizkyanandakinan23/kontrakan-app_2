@extends('layouts.app')

@section('title', 'Pembayaran Midtrans')

@section('content')

<div class="max-w-3xl mx-auto">

    <div class="bg-white rounded-3xl shadow-xl p-10">

        <!-- TITLE -->
        <h1 class="text-3xl font-bold text-gray-800 mb-6">
            Pembayaran Booking
        </h1>

        <!-- INFO KAMAR -->
        <div class="mb-8">

            <h2 class="text-2xl font-bold text-amber-700">
                {{ $kamar->nama_kamar }}
            </h2>

            <p class="text-gray-500 mt-2">
                Silakan selesaikan pembayaran melalui Midtrans
            </p>

        </div>

        <!-- TOTAL -->
        <div class="bg-amber-50 rounded-2xl p-6 mb-8">

            <p class="text-gray-500 mb-2">
                Total Pembayaran
            </p>

            <h2 class="text-4xl font-extrabold text-amber-700">

                Rp {{ number_format($booking->total_harga, 0, ',', '.') }}

            </h2>

        </div>

        <!-- STATUS INFO -->
        <div class="mb-6 text-sm text-gray-500">
            <p>• Pembayaran diproses oleh Midtrans</p>
            <p>• Anda bisa memilih QRIS, e-wallet, atau transfer bank</p>
        </div>

        <!-- BUTTON -->
        <button
            id="pay-button"
            class="w-full bg-amber-700 hover:bg-amber-800 text-white py-4 rounded-2xl text-lg font-bold transition"
        >
            Bayar Sekarang
        </button>

    </div>

</div>

<!-- MIDTRANS SNAP -->
<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>

const payButton = document.getElementById('pay-button');

payButton.addEventListener('click', function () {

    payButton.disabled = true;
    payButton.innerText = 'Memproses pembayaran...';

    snap.pay('{{ $snapToken }}', {

        onSuccess: function (result) {

            console.log('SUCCESS:', result);

            window.location.href =
                "{{ route('booking.riwayat') }}";

        },

        onPending: function (result) {

            console.log('PENDING:', result);

            window.location.href =
                "{{ route('booking.riwayat') }}";

        },

        onError: function (result) {

            console.log('ERROR:', result);

            alert('Pembayaran gagal, silakan coba lagi');

            payButton.disabled = false;
            payButton.innerText = 'Bayar Sekarang';
        },

        onClose: function () {

            alert('Kamu menutup popup pembayaran');

            payButton.disabled = false;
            payButton.innerText = 'Bayar Sekarang';
        }

    });

});

</script>

@endsection