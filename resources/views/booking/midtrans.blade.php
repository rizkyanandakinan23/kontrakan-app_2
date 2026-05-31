@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- HEADER -->
        <div class="bg-amber-700 p-8 text-white">

            <h1 class="text-3xl font-bold">
                Pembayaran Booking
            </h1>

            <p class="mt-2 text-amber-100">
                Selesaikan pembayaran untuk mengamankan kamar pilihan Anda
            </p>

        </div>

        <div class="p-8">

            <!-- INFO KAMAR -->
            <div class="grid md:grid-cols-2 gap-8 mb-8">

                <div>

                    <h2 class="text-2xl font-bold text-gray-800 mb-4">
                        {{ $kamar->nama_kamar }}
                    </h2>

                    <div class="space-y-3 text-gray-600">

                        <div>
                            <span class="font-semibold">
                                Tanggal Masuk :
                            </span>
                            {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }}
                        </div>

                        <div>
                            <span class="font-semibold">
                                Durasi :
                            </span>
                            {{ $booking->durasi }} Bulan
                        </div>

                        <div>
                            <span class="font-semibold">
                                WhatsApp :
                            </span>
                            {{ $booking->whatsapp }}
                        </div>

                    </div>

                </div>

                <!-- TOTAL -->
                <div>

                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">

                        <p class="text-gray-500 mb-2">
                            Total Pembayaran
                        </p>

                        <h2 class="text-4xl font-extrabold text-amber-700">
                            Rp {{ number_format($booking->total_harga,0,',','.') }}
                        </h2>

                    </div>

                </div>

            </div>

            <!-- INFORMASI -->
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 mb-8">

                <h3 class="font-bold text-blue-700 mb-2">
                    Informasi Pembayaran
                </h3>

                <ul class="space-y-2 text-sm text-gray-700">

                    <li>
                        ✓ Pembayaran diproses aman melalui Midtrans
                    </li>

                    <li>
                        ✓ Mendukung QRIS, GoPay, ShopeePay, Transfer Bank, dan E-Wallet
                    </li>

                    <li>
                        ✓ Status booking akan otomatis diperbarui setelah pembayaran berhasil
                    </li>

                    <li>
                        ✓ Simpan bukti pembayaran jika diperlukan
                    </li>

                </ul>

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

</div>

<script
src="https://app.sandbox.midtrans.com/snap/snap.js"
data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>

const payButton = document.getElementById('pay-button');

// 🔒 Validasi token dulu
const snapToken = "{{ $snapToken }}";

if (!snapToken) {
    alert('Token pembayaran tidak tersedia');
}

payButton.addEventListener('click', function(){

    if (!snapToken) return;

    payButton.disabled = true;

    payButton.innerHTML = `
        <span class="animate-pulse">
            Memproses Pembayaran...
        </span>
    `;

    snap.pay(snapToken, {

        // ✅ BERHASIL
        onSuccess: function(result){

            console.log('SUCCESS:', result);

            alert('Pembayaran berhasil');

            window.location.href =
                "{{ route('booking.riwayat') }}";

        },

        // ⏳ PENDING
        onPending: function(result){

            console.log('PENDING:', result);

            alert('Menunggu pembayaran');

            window.location.href =
                "{{ route('booking.riwayat') }}";

        },

        // ❌ ERROR
        onError: function(result){

            console.error('ERROR:', result);

            alert('Pembayaran gagal, silakan coba lagi');

            payButton.disabled = false;

            payButton.innerText = 'Bayar Sekarang';

        },

        // ❌ USER CLOSE (FIX UTAMA)
        onClose: function(){

            console.log('USER CLOSED POPUP');

            // ❗ TIDAK REDIRECT LAGI
            alert('Kamu menutup pembayaran sebelum selesai');

            payButton.disabled = false;

            payButton.innerText = 'Bayar Sekarang';

        }

    });

});

</script>

@endsection