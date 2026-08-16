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

                    <span
                        id="hargaKamar"
                        data-harga="{{ $kamar->harga }}"
                    >
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


        <!-- ATURAN BOOKING -->
        <div class="mb-6 rounded-2xl border border-blue-200 bg-blue-50 p-5">

            <h3 class="font-bold text-blue-700 mb-2">
                Aturan Booking
            </h3>

            <ul class="list-disc ml-5 text-sm text-gray-700 space-y-1">

                <li>
                    Booking maksimal 6 bulan ke depan.
                </li>

                <li>
                    Minimal durasi sewa 1 bulan.
                </li>

                <li>
                    Pembayaran dilakukan secara bertahap setiap periode sewa.
                </li>

                <li>
                    Pembayaran pertama dilakukan untuk periode sewa bulan pertama.
                </li>

                <li>
                    Pembayaran periode berikutnya dilakukan sebelum memasuki periode sewa berikutnya.
                </li>

                <li>
                    Check-in penyewa baru harus memiliki jeda 2 hari setelah penyewa sebelumnya check-out.
                </li>

                <li>
                    Jika kamar sudah memiliki jadwal booking berikutnya, durasi sewa tidak boleh melewati jadwal tersebut.
                </li>

            </ul>

        </div>


        <!-- ERROR VALIDASI -->
        @if ($errors->any())

            <div class="mb-5 rounded-xl bg-red-100 border border-red-300 p-4">

                <p class="font-semibold text-red-700 mb-2">
                    Booking tidak dapat diproses.
                </p>

                <ul class="list-disc ml-5 text-red-700">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif


        <!-- SESSION ERROR -->
        @if (session('error'))

            <div class="mb-5 rounded-xl bg-red-100 border border-red-300 p-4">

                <p class="text-red-700">
                    {{ session('error') }}
                </p>

            </div>

        @endif


        <form
            id="bookingForm"
            action="{{ route('booking.store', $kamar->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf


            <!-- TANGGAL MASUK -->
            <div class="mb-5">

                @php

                    $today = now()->format('Y-m-d');

                    $maxDate = now()
                        ->addMonths(6)
                        ->format('Y-m-d');

                @endphp


                <input
                    type="hidden"
                    id="tanggal_masuk"
                    name="tanggal_masuk"
                    value="{{ $tanggalMasuk }}"
                >


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
                    max="24"
                    value="{{ old('durasi', 1) }}"
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
                >

                <p class="text-sm text-gray-500 mt-2">
                    Tentukan berapa bulan Anda ingin menyewa kontrakan.
                </p>

            </div>


            <!-- TANGGAL SELESAI -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Tanggal Selesai Sewa
                </label>

                <input
                    type="date"
                    name="tanggal_selesai"
                    id="tanggal_selesai"
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 bg-gray-100"
                    readonly
                >

                <p class="text-sm text-gray-500 mt-2">
                    Tanggal selesai dihitung otomatis berdasarkan tanggal masuk dan durasi sewa.
                </p>

                <p
                    id="infoBooking"
                    class="hidden mt-2 text-sm text-red-600 font-medium"
                >
                    Durasi sewa melewati jadwal booking penyewa berikutnya.
                    Silakan kurangi durasi atau pilih tanggal masuk lain.
                </p>

            </div>


            <!-- FOTO IDENTITAS -->
            <div class="mb-6">

                <label class="block mb-2 font-semibold">
                    Foto KTP / Identitas
                </label>

                <input
                    type="file"
                    name="foto_identitas"
                    accept=".jpg,.jpeg,.png"
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3"
                    required
                >

                <p class="text-sm text-gray-500 mt-2">
                    Upload foto KTP, SIM, atau Paspor
                    (JPG, JPEG, PNG maksimal 2 MB).
                </p>

            </div>


            <!-- RINGKASAN SEWA -->
            <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 mb-6">

                <p class="text-gray-600 mb-1">
                    Total Nilai Sewa
                </p>

                <h2
                    id="totalHarga"
                    class="text-3xl font-bold text-amber-700"
                >
                    Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                </h2>

                <p class="text-sm text-gray-500 mt-2">
                    Total nilai sewa berdasarkan seluruh durasi yang dipilih.
                </p>


                <div class="border-t border-amber-200 mt-4 pt-4">

                    <p class="text-gray-600 mb-1">
                        Pembayaran Periode Pertama
                    </p>

                    <p
                        id="pembayaranPertama"
                        class="text-xl font-bold text-gray-800"
                    >
                        Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                    </p>

                    <p class="text-sm text-gray-500 mt-1">
                        Pembayaran pertama hanya untuk 1 bulan masa sewa.
                    </p>

                </div>

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
const pembayaranPertama = document.getElementById('pembayaranPertama');

const hargaKamar = document.getElementById('hargaKamar');

const harga = parseInt(
    hargaKamar.dataset.harga
);

const infoBooking = document.getElementById('infoBooking');
const btnBooking = document.getElementById('btnBooking');


function hitungSemua()
{

    // ==========================================
    // DURASI
    // ==========================================

    let bulan = parseInt(durasi.value) || 1;

    if (bulan < 1) {
        bulan = 1;
    }


    // ==========================================
    // TOTAL NILAI SEWA
    // ==========================================

    let total = harga * bulan;

    totalHarga.innerText =
        'Rp ' + total.toLocaleString('id-ID');


    // ==========================================
    // PEMBAYARAN PERIODE PERTAMA
    // ==========================================

    pembayaranPertama.innerText =
        'Rp ' + harga.toLocaleString('id-ID');


    // ==========================================
    // HITUNG TANGGAL SELESAI
    // ==========================================

    if (tanggalMasuk.value) {

        let masuk = new Date(
            tanggalMasuk.value + 'T00:00:00'
        );

        let selesai = new Date(masuk);

        selesai.setMonth(
            selesai.getMonth() + bulan
        );

        let yyyy = selesai.getFullYear();

        let mm = String(
            selesai.getMonth() + 1
        ).padStart(2, '0');

        let dd = String(
            selesai.getDate()
        ).padStart(2, '0');

        tanggalSelesai.value =
            `${yyyy}-${mm}-${dd}`;

    } else {

        tanggalSelesai.value = '';

    }


    // ==========================================
    // CEK BENTROK BOOKING
    // ==========================================

    let bentrok = false;


    if (
        tanggalMasuk.value &&
        tanggalSelesai.value
    ) {

        bookingAktif.forEach(function(item) {

            let mulaiBooking =
                new Date(
                    item.tanggal_masuk + 'T00:00:00'
                );

            let selesaiBooking =
                new Date(
                    item.tanggal_selesai + 'T00:00:00'
                );


            // Jeda 2 hari setelah checkout
            selesaiBooking.setDate(
                selesaiBooking.getDate() + 2
            );


            let mulaiBaru =
                new Date(
                    tanggalMasuk.value + 'T00:00:00'
                );

            let selesaiBaru =
                new Date(
                    tanggalSelesai.value + 'T00:00:00'
                );


            if (
                mulaiBaru < selesaiBooking &&
                selesaiBaru > mulaiBooking
            ) {

                bentrok = true;

            }

        });

    }


    // ==========================================
    // STATUS TOMBOL
    // ==========================================

    if (bentrok) {

        btnBooking.disabled = true;

        infoBooking.classList.remove('hidden');

    } else {

        btnBooking.disabled = false;

        infoBooking.classList.add('hidden');

    }

}


// ==========================================
// EVENT
// ==========================================

durasi.addEventListener(
    'input',
    hitungSemua
);

tanggalMasuk.addEventListener(
    'change',
    hitungSemua
);


// ==========================================
// LOAD PERTAMA
// ==========================================

hitungSemua();


// ==========================================
// KONFIRMASI BOOKING
// ==========================================

btnBooking.addEventListener(
    'click',
    function()
    {

        if (btnBooking.disabled) {
            return;
        }


        Swal.fire({

            title: 'Konfirmasi Booking',

            html: `

                <div class="text-left leading-relaxed">

                    <p class="mb-4">
                        Anda akan melakukan booking kontrakan
                        dengan durasi sewa yang telah dipilih.
                        Pembayaran dilakukan secara bertahap
                        sesuai periode sewa.
                    </p>

                    <p class="font-semibold mb-2">
                        Dengan menekan tombol
                        <b>"Ya, Lanjut Booking"</b>,
                        Anda menyatakan bahwa:
                    </p>

                    <ul class="list-disc pl-5 space-y-2 text-left">

                        <li>
                            Tanggal mulai sewa yang dipilih
                            telah sesuai dengan rencana Anda.
                        </li>

                        <li>
                            Durasi sewa yang dipilih
                            sudah benar.
                        </li>

                        <li>
                            Data yang Anda masukkan
                            merupakan data yang benar.
                        </li>

                        <li>
                            Pembayaran dilakukan secara bertahap
                            untuk setiap periode sewa.
                        </li>

                        <li>
                            Pembayaran pertama dilakukan
                            untuk periode sewa bulan pertama.
                        </li>

                        <li>
                            Pembayaran periode berikutnya
                            harus dilakukan sesuai jadwal
                            pembayaran yang ditentukan sistem.
                        </li>

                    </ul>

                    <p class="mt-4 text-sm text-red-600 font-medium">
                        Pastikan seluruh informasi booking
                        sudah sesuai sebelum melanjutkan.
                    </p>

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

                document
                    .getElementById('bookingForm')
                    .submit();

            }

        });

    }
);

</script>

@endsection
