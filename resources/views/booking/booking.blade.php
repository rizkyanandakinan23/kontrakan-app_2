@extends('layouts.app')

@section('title', 'Booking Kamar')

@section('content')

@php

    /*
    |--------------------------------------------------------------------------
    | FOTO
    |--------------------------------------------------------------------------
    */

    $foto = $kamar->foto_kamar ?? [];

    if (is_string($foto)) {

        $decoded = json_decode($foto, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            $foto = $decoded;

        } else {

            $foto = [];
        }
    }

    if (!is_array($foto)) {
        $foto = [];
    }

    /*
    |--------------------------------------------------------------------------
    | FASILITAS
    |--------------------------------------------------------------------------
    */

    $fasilitas = $kamar->fasilitas ?? [];

    if (is_string($fasilitas)) {

        $decoded = json_decode($fasilitas, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            $fasilitas = $decoded;

        } else {

            $fasilitas = [];
        }
    }

    if (!is_array($fasilitas)) {
        $fasilitas = [];
    }

@endphp

<div class="max-w-7xl mx-auto">

    <!-- BACK -->
    <div class="mb-6">
        @include('components.back')
    </div>

    <div class="grid lg:grid-cols-2 gap-8 items-start">

        <!-- ========================= -->
        <!-- DETAIL KAMAR -->
        <!-- ========================= -->

        <div class="bg-white rounded-3xl shadow-xl p-5 max-w-3xl mx-auto">

            <!-- FOTO -->
            <img
                src="{{ asset('storage/' . ($foto[0] ?? 'default.jpg')) }}"
                class="w-full h-80 object-cover"
            >

            <!-- CONTENT -->
            <div class="p-8">

                <!-- STATUS -->
                <div class="mb-4">

                    @if($kamar->status == 'terisi')

                        <span class="bg-red-100 text-red-700 px-4 py-2 rounded-full text-sm font-bold">
                            Sudah Disewa
                        </span>

                    @else

                        <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full text-sm font-bold">
                            Masih Tersedia
                        </span>

                    @endif

                </div>

                <!-- NAMA -->
                <h1 class="text-4xl font-bold text-gray-800 mb-4">

                    {{ $kamar->nama_kamar }}

                </h1>

                <!-- DESKRIPSI -->
                <p class="text-gray-600 leading-relaxed mb-6">

                    {!! nl2br(e($kamar->deskripsi)) !!}

                </p>

                <!-- HARGA -->
                <div class="mb-6">

                    <p class="text-gray-500">
                        Harga Sewa
                    </p>

                    <h2 class="text-4xl font-extrabold text-amber-700">

                        <span id="hargaKamar" data-harga="{{ $kamar->harga }}">
    Rp {{ number_format($kamar->harga, 0, ',', '.') }}
</span>

                    </h2>

                    <p class="text-gray-400">
                        / bulan
                    </p>

                </div>


            </div>

        </div>

        <!-- ========================= -->
        <!-- FORM BOOKING -->
        <!-- ========================= -->

        <div class="bg-white rounded-3xl shadow-2xl p-8">

            <h2 class="text-3xl font-bold text-gray-800 mb-8">
                Form Booking
            </h2>

            <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-5">

    <h3 class="font-bold text-blue-700 mb-2">
        Aturan Booking
    </h3>

    <ul class="list-disc ml-5 text-sm text-gray-700 space-y-1">
        <li>Booking maksimal 6 bulan ke depan.</li>
        <li>Minimal durasi sewa 1 bulan.</li>
        <li>Check-in penyewa baru harus memiliki jeda 2 hari setelah penyewa sebelumnya check-out.</li>
        <li>Jika kamar sudah dibooking di masa depan, durasi sewa tidak boleh melewati jadwal booking berikutnya.</li>
    </ul>

</div>

            @if ($errors->any())
    <div class="mb-5 rounded-xl bg-red-100 border border-red-300 p-4">
        <ul class="list-disc ml-5 text-red-700">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

            <form id="bookingForm" action="{{ route('booking.store', $kamar->id) }}" method="POST">

                @csrf



                <!-- TANGGAL MASUK -->
                <div class="mb-5">

                    @php
    $today = now()->format('Y-m-d');
    $maxDate = now()->addMonths(6)->format('Y-m-d');
@endphp
<input
    type="hidden"
    id="tanggal_masuk"
    name="tanggal_masuk"
    value="{{ $tanggalMasuk }}"
>
<div class="mb-5">
    <label class="block mb-2 font-semibold">
        Tanggal Masuk
    </label>

    <input
        type="text"
        value="{{ \Carbon\Carbon::parse($tanggalMasuk)->translatedFormat('d F Y') }}"
        readonly
        class="w-full border rounded-2xl bg-gray-100 px-4 py-3"
    >
</div>

                </div>

                <!-- DURASI -->
<div class="mb-8">

    <label class="block mb-2 font-semibold">
        Durasi Sewa (Bulan)
    </label>

    <input
        type="number"
        name="durasi"
        id="durasi"
        min="1"
        value="1"
        class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
    >

    <p class="text-sm text-gray-500 mt-2">
        Masukkan jumlah bulan sewa
    </p>

</div>

<!-- TANGGAL SELESAI -->
<div class="mb-5">

    <label class="block mb-2 font-semibold">
        Tanggal Selesai
    </label>

    <input
        type="date"
        name="tanggal_selesai"
        id="tanggal_selesai"
        class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-gray-100"
        readonly
    >

    <p
        id="infoBooking"
        class="hidden mt-2 text-sm text-red-600 font-medium"
    >
        Durasi sewa melewati jadwal booking penyewa berikutnya.
        Silakan kurangi durasi atau pilih tanggal check-in lain.
    </p>

</div>

<!-- TOTAL HARGA -->
<div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">

    <p class="text-gray-600 mb-2">
        Total Harga
    </p>

    <h2
        id="totalHarga"
        class="text-3xl font-bold text-amber-700"
    >
        Rp {{ number_format($kamar->harga, 0, ',', '.') }}
    </h2>

</div>

                <!-- BUTTON -->
                <button
    type="button"
    id="btnBooking"
    class="w-full bg-amber-700 hover:bg-amber-800 disabled:bg-gray-400 disabled:cursor-not-allowed text-white py-4 rounded-2xl font-bold text-lg transition"
>
    Booking Sekarang
</button>

            </form>

        </div>

    </div>

</div>

<script>

const bookingAktif = @json($bookingAktif);

const tanggalMasuk = document.getElementById('tanggal_masuk');
const tanggalSelesai = document.getElementById('tanggal_selesai');
const durasi = document.getElementById('durasi');

const totalHarga = document.getElementById('totalHarga');
const hargaKamar = document.getElementById('hargaKamar');

const harga = parseInt(hargaKamar.dataset.harga);

const infoBooking = document.getElementById('infoBooking');
const btnBooking = document.getElementById('btnBooking');

function hitungSemua() {

    // ==========================
    // TOTAL HARGA
    // ==========================

    let bulan = parseInt(durasi.value) || 1;

    let total = harga * bulan;

    totalHarga.innerText =
        'Rp ' + total.toLocaleString('id-ID');

    // ==========================
    // HITUNG TANGGAL SELESAI
    // ==========================

    if (tanggalMasuk.value) {

        let masuk = new Date(tanggalMasuk.value);

        let selesai = new Date(masuk.getTime());

selesai.setMonth(selesai.getMonth() + bulan);

tanggalSelesai.value =
    selesai.toISOString().split('T')[0];

        let yyyy = selesai.getFullYear();
        let mm = String(selesai.getMonth() + 1).padStart(2, '0');
        let dd = String(selesai.getDate()).padStart(2, '0');

        tanggalSelesai.value = `${yyyy}-${mm}-${dd}`;

    } else {

        tanggalSelesai.value = '';

    }

    // ==========================
    // CEK BENTROK BOOKING
    // ==========================

    let bentrok = false;

    bookingAktif.forEach(function(item) {

        let mulaiBooking = new Date(item.tanggal_masuk);

        let selesaiBooking = new Date(item.tanggal_selesai);

        // beri jeda 2 hari
        selesaiBooking.setDate(
            selesaiBooking.getDate() + 2
        );

        let mulaiBaru = new Date(tanggalMasuk.value);

        let selesaiBaru = new Date(tanggalSelesai.value);

        if (
            mulaiBaru < selesaiBooking &&
            selesaiBaru > mulaiBooking
        ) {
            bentrok = true;
        }

    });

    if (bentrok) {

        btnBooking.disabled = true;

        infoBooking.classList.remove('hidden');

    } else {

        btnBooking.disabled = false;

        infoBooking.classList.add('hidden');

    }

}

// ==========================
// EVENT
// ==========================

durasi.addEventListener('input', hitungSemua);

tanggalMasuk.addEventListener('change', hitungSemua);

// ==========================
// LOAD PERTAMA
// ==========================

hitungSemua();

// ==========================
// KONFIRMASI BOOKING
// ==========================

btnBooking.addEventListener('click', function () {

    if (btnBooking.disabled) return;

    Swal.fire({
        title: 'Konfirmasi Booking',
        html: `
            <div class="text-left">
                <p class="mb-3">
                    Apakah Anda yakin ingin melakukan booking kontrakan ini?
                </p>

                <ul style="text-align:left;">
                    <li>✔ Pastikan tanggal masuk sudah sesuai.</li>
                    <li>✔ Pastikan durasi sewa sudah benar.</li>
                    <li>✔ Booking akan dilanjutkan ke halaman pembayaran.</li>
                    <li>✔ Booking yang sudah dibayar tidak dapat diubah secara sepihak.</li>
                </ul>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#b45309',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Ya, Lanjut Booking',
        cancelButtonText: 'Periksa Lagi'
    }).then((result) => {

        if (result.isConfirmed) {

            document.getElementById('bookingForm').submit();

        }

    });

});
</script>


@endsection