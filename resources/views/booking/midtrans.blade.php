@extends('layouts.app')

@section('title', 'Pembayaran')

@section('content')

<div class="max-w-4xl mx-auto">


    <div class="mb-6 flex justify-between items-center">

    <!-- KIRI: BACK -->
    <div>
        @include('components.back')
    </div>

    <!-- KANAN: KE BERANDA -->
    <a href="{{ route('home') }}"
       class="bg-amber-700 hover:bg-amber-800 text-white px-4 py-2 rounded-lg text-sm font-semibold transition shadow">
        Kembali ke Halaman Utama
    </a>

</div>

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

            <!-- NOTIFIKASI -->
<div class="bg-yellow-50 border-l-4 border-yellow-500 p-4 rounded-xl mb-6">
    <div class="flex items-start gap-3">
        <span class="text-xl">🔔</span>

        <div>
            <h3 class="font-semibold text-yellow-800">
                Menunggu Pembayaran
            </h3>

            <p class="text-sm text-yellow-700 mt-1">
                Silahkan lakukan pembayaran untuk menyelesaikan proses booking kamar.
                Setelah pembayaran berhasil, status booking akan otomatis diperbarui oleh sistem.
            </p>
        </div>
    </div>
</div>

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
            Tanggal Selesai :
        </span>
        {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
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
                            Rp {{ number_format($payment->jumlah,0,',','.') }}
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
                        ✓ Pembayaran diproses aman
                    </li>

                    <li>
                        ✓ Mendukung QRIS, GoPay, ShopeePay, Transfer Bank, dan E-Wallet lainnya
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
const snapToken = "{{ $payment->snap_token }}";

// ❌ kalau token kosong
if (!snapToken) {
    payButton.disabled = true;
    payButton.innerText = "Token tidak tersedia";
}

payButton.addEventListener('click', function(){

    if (!snapToken) return;

    // 🔒 disable button + loading
    payButton.disabled = true;
    payButton.innerHTML = `
        <span class="animate-pulse">
            Memproses Pembayaran...
        </span>
    `;

    snap.pay(snapToken, {

        // ✅ SUCCESS
        onSuccess: function(result){

    console.log(result);


    payButton.innerHTML =
    "Pembayaran berhasil, memproses...";


    setTimeout(()=>{

        window.location.href =
        "{{ route('booking.riwayat') }}";

    },2000);


},

        // ⏳ PENDING
        onPending: function(result){

            console.log('PENDING:', result);

            window.location.href =
                "{{ route('booking.riwayat') }}?pending=1";
        },

        // ❌ ERROR
        onError: function(result){

            console.error('ERROR:', result);

            window.location.href =
                "{{ route('booking.riwayat') }}?error=1";
        },

        // ❌ USER CLOSE
        onClose: function(){

            console.log('USER CLOSED');

            // balikin button
            payButton.disabled = false;
            payButton.innerText = 'Bayar Sekarang';
        }

    });

});

</script>

@endsection