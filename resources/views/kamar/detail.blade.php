@extends('layouts.app')

@section('title', 'Detail Kamar')

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

    <!-- ================================= -->
    <!-- DETAIL KAMAR -->
    <!-- ================================= -->

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="grid lg:grid-cols-2 gap-10">

            <!-- ================================= -->
            <!-- SLIDER FOTO -->
            <!-- ================================= -->

            <div class="p-6">

                <!-- FOTO UTAMA -->

                <div class="mb-4">

                    <img
                        id="mainImage"
                        src="{{ asset('storage/' . ($foto[0] ?? 'default.jpg')) }}"
                        class="w-full h-[450px] object-cover rounded-3xl shadow-lg transition duration-300"
                    >

                </div>

                <!-- THUMBNAIL -->

                <div class="flex gap-4 overflow-x-auto pb-2">

                    @foreach($foto as $img)

                        <img
                            src="{{ asset('storage/' . $img) }}"
                            onclick="changeImage(this)"
                            class="w-28 h-28 object-cover rounded-2xl cursor-pointer border-4 border-transparent hover:border-amber-500 transition"
                        >

                    @endforeach

                </div>

            </div>

            <!-- ================================= -->
            <!-- DETAIL -->
            <!-- ================================= -->

            <div class="p-8">

                <!-- STATUS -->

                <div class="mb-4">

                    @if($kamar->status == 'terisi')

                        <span class="bg-red-100 text-red-700 px-5 py-2 rounded-full text-sm font-bold">
                            Sudah Disewa
                        </span>

                    @else

                        <span class="bg-green-100 text-green-700 px-5 py-2 rounded-full text-sm font-bold">
                            Masih Tersedia
                        </span>

                    @endif

                </div>

                <!-- NAMA -->

                <h1 class="text-5xl font-bold text-gray-800 mb-4">

                    {{ $kamar->nama_kamar }}

                </h1>

                <!-- HARGA -->

                <div class="mb-8">

                    <p class="text-gray-500 text-lg">
                        Harga Sewa
                    </p>

                    <h2 class="text-5xl font-extrabold text-amber-700 mt-2">

                        Rp {{ number_format($kamar->harga, 0, ',', '.') }}

                    </h2>

                    <p class="text-gray-400 mt-1">
                        / bulan
                    </p>

                </div>

                <!-- DESKRIPSI -->

                <div class="mb-8">

                    <h3 class="text-2xl font-bold text-gray-800 mb-3">
                        Deskripsi
                    </h3>

                    <p class="text-gray-600 leading-relaxed text-lg">

                        {{ $kamar->deskripsi }}

                    </p>

                </div>

                <!-- FASILITAS -->

                <div class="mb-10">

                    <h3 class="text-2xl font-bold text-gray-800 mb-4">
                        Fasilitas
                    </h3>

                    <div class="flex flex-wrap gap-3">

                        @foreach($fasilitas as $item)

                            <div class="bg-gray-100 px-5 py-3 rounded-2xl text-gray-700 font-medium shadow-sm">

                                ✔ {{ $item }}

                            </div>

                        @endforeach

                    </div>

                </div>

                <!-- BUTTON -->

                <div class="flex flex-wrap gap-4">

                    <a
                        href="{{ route('kamar.index') }}"
                        class="bg-gray-200 hover:bg-gray-300 px-6 py-4 rounded-2xl font-semibold transition"
                    >
                        Kembali
                    </a>

                    @if($kamar->status != 'terisi')

                        <a
                            href="{{ route('booking.index', $kamar->id) }}"
                            class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg transition"
                        >
                            Sewa Sekarang
                        </a>

                    @endif

                </div>

            </div>

        </div>

    </div>

    @include('components.maps')

    <!-- ================================= -->
    <!-- REVIEW -->
    <!-- ================================= -->

    <div class="bg-white rounded-3xl shadow-2xl mt-10 p-8">

        <div class="flex items-center justify-between mb-8">

            <h2 class="text-3xl font-bold text-gray-800">
                Review Penghuni
            </h2>

            <div class="text-right">

                <p class="text-gray-500 text-sm">
                    Total Review
                </p>

                <h3 class="text-2xl font-bold text-amber-700">

                    {{ $kamar->reviews->count() }}

                </h3>

            </div>

        </div>

        <!-- SUCCESS -->

        @if(session('success'))

            <div class="bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-8">

                {{ session('success') }}

            </div>

        @endif

        <!-- ERROR -->

        @if(session('error'))

            <div class="bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-8">

                {{ session('error') }}

            </div>

        @endif

        <!-- VALIDATION -->

        @if ($errors->any())

            <div class="bg-red-100 border border-red-200 text-red-700 px-5 py-4 rounded-2xl mb-8">

                <ul class="list-disc ml-5">

                    @foreach ($errors->all() as $error)

                        <li>{{ $error }}</li>

                    @endforeach

                </ul>

            </div>

        @endif

        <!-- FORM REVIEW -->

        @auth

            <form
                action="{{ route('review.store', $kamar->id) }}"
                method="POST"
                class="mb-12"
            >
                @csrf

                <!-- RATING -->

                <div class="mb-6">

                    <label class="block font-semibold text-gray-700 mb-3">
                        Rating
                    </label>

                    <select
                        name="rating"
                        class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
                    >
                        <option value="5">⭐⭐⭐⭐⭐ (5)</option>
                        <option value="4">⭐⭐⭐⭐ (4)</option>
                        <option value="3">⭐⭐⭐ (3)</option>
                        <option value="2">⭐⭐ (2)</option>
                        <option value="1">⭐ (1)</option>
                    </select>

                </div>

                <!-- KOMENTAR -->

                <div class="mb-6">

                    <label class="block font-semibold text-gray-700 mb-3">
                        Komentar
                    </label>

                    <textarea
                        name="komentar"
                        rows="5"
                        required
                        class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-amber-500"
                        placeholder="Bagikan pengalaman Anda selama tinggal di kamar ini..."
                    ></textarea>

                </div>

                <!-- BUTTON -->

                <button
                    type="submit"
                    class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg transition"
                >
                    Kirim Review
                </button>

            </form>

        @else

            <div class="bg-yellow-100 border border-yellow-200 text-yellow-700 px-5 py-4 rounded-2xl mb-10">

                Silakan login terlebih dahulu untuk memberikan review.

            </div>

        @endauth


        <!-- LIST REVIEW -->

        <div class="space-y-6">

            @forelse($kamar->reviews->sortByDesc('created_at') as $review)

                <div class="border border-gray-200 rounded-3xl p-6 hover:shadow-lg transition">

                    <!-- HEADER -->

                    <div class="flex justify-between items-start gap-4 mb-4">

                        <div>

                            <h4 class="font-bold text-lg text-gray-800">

                                {{ $review->user->name }}

                            </h4>

                            <div class="text-yellow-500 text-lg mt-1">

                                @for($i = 1; $i <= $review->rating; $i++)

                                    ⭐

                                @endfor

                            </div>

                        </div>

                        <span class="text-sm text-gray-400 whitespace-nowrap">

                            {{ $review->created_at->format('d M Y') }}

                        </span>

                    </div>

                    <!-- KOMENTAR -->

                    <p class="text-gray-600 leading-relaxed mb-5">

                        {{ $review->komentar }}

                    </p>

                    <!-- ACTION -->

                    @auth

                        <div class="flex flex-wrap gap-3">

                            <!-- REVIEW SENDIRI -->

                            @if(auth()->id() == $review->user_id)

                                <form
                                    action="{{ route('review.destroy', $review->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Hapus review ini?')"
                                >
                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="bg-red-100 hover:bg-red-200 text-red-700 px-4 py-2 rounded-xl text-sm font-semibold transition"
                                    >
                                        Hapus Review
                                    </button>

                                </form>

                            @else

                                <!-- REPORT REVIEW -->

                                <button
                                    type="button"
                                    onclick="openReportModal({{ $review->id }})"
                                    class="bg-yellow-100 hover:bg-yellow-200 text-yellow-700 px-4 py-2 rounded-xl text-sm font-semibold transition"
                                >
                                    Report Review
                                </button>

                            @endif

                        </div>

                    @endauth

                </div>

            @empty

                <div class="text-center py-16">

                    <div class="text-6xl mb-4">
                        ⭐
                    </div>

                    <h3 class="text-2xl font-bold text-gray-700 mb-2">

                        Belum Ada Review

                    </h3>

                    <p class="text-gray-500">

                        Jadilah penghuni pertama yang memberikan review kamar ini.

                    </p>

                </div>

            @endforelse

        </div>

    </div>

</div>

<!-- ================================= -->
<!-- MODAL REPORT -->
<!-- ================================= -->

<div
    id="reportModal"
    class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50 p-4"
>

    <div class="bg-white rounded-3xl w-full max-w-md p-8 relative">

        <!-- CLOSE -->

        <button
            onclick="closeReportModal()"
            class="absolute top-4 right-4 text-gray-400 hover:text-red-500 text-2xl"
        >
            &times;
        </button>

        <h2 class="text-2xl font-bold text-gray-800 mb-6">

            Report Review

        </h2>

        <form
            id="reportForm"
            method="POST"
        >
            @csrf
            @method('PATCH')

            <!-- ALASAN -->

            <div class="mb-6">

                <label class="block font-semibold text-gray-700 mb-3">

                    Pilih Alasan

                </label>

                <select
                    name="alasan"
                    required
                    class="w-full border border-gray-300 rounded-2xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-yellow-500"
                >
                    <option value="">-- Pilih Alasan --</option>

                    <option value="Spam">
                        Spam
                    </option>

                    <option value="Bahasa Kasar">
                        Bahasa Kasar
                    </option>

                    <option value="Informasi Palsu">
                        Informasi Palsu
                    </option>

                    <option value="Mengandung Promosi">
                        Mengandung Promosi
                    </option>

                    <option value="Konten Tidak Pantas">
                        Konten Tidak Pantas
                    </option>

                    <option value="Lainnya">
                        Lainnya
                    </option>

                </select>

            </div>

            <!-- BUTTON -->

            <div class="flex justify-end gap-3">

                <button
                    type="button"
                    onclick="closeReportModal()"
                    class="bg-gray-200 hover:bg-gray-300 px-5 py-3 rounded-2xl font-semibold transition"
                >
                    Batal
                </button>

                <button
                    type="submit"
                    class="bg-yellow-500 hover:bg-yellow-600 text-white px-5 py-3 rounded-2xl font-bold transition"
                >
                    Kirim Report
                </button>

            </div>

        </form>

    </div>

</div>

<!-- ================================= -->
<!-- SCRIPT -->
<!-- ================================= -->

<script>

    /*
    |--------------------------------------------------------------------------
    | SLIDER FOTO
    |--------------------------------------------------------------------------
    */

    function changeImage(element)
    {
        document.getElementById('mainImage').src = element.src;
    }

    /*
    |--------------------------------------------------------------------------
    | REPORT MODAL
    |--------------------------------------------------------------------------
    */

    function openReportModal(reviewId)
    {
        const modal = document.getElementById('reportModal');

        const form = document.getElementById('reportForm');

        form.action = `/review/${reviewId}/report`;

        modal.classList.remove('hidden');

        modal.classList.add('flex');
    }

    function closeReportModal()
    {
        const modal = document.getElementById('reportModal');

        modal.classList.remove('flex');

        modal.classList.add('hidden');
    }

</script>



@endsection