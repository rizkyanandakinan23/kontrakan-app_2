@extends('layouts.adminlayouts')

@section('title', 'Edit Kamar')

@section('content')

<div class="max-w-5xl mx-auto">

    {{-- BACK --}}
    <div class="mb-6">
        @include('components.back')
    </div>


    {{-- CARD --}}
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        {{-- HEADER --}}
        <div class="bg-amber-600 px-8 py-6 text-white">

            <h1 class="text-3xl font-bold">
                Edit Kontrakan
            </h1>

            <p class="text-amber-100 mt-1">
                Ubah informasi dan foto kontrakan.
            </p>

        </div>


        <div class="p-8">

            {{-- ERROR --}}
            @if($errors->any())

                <div class="mb-6 bg-red-50 border border-red-200 text-red-700 rounded-xl p-4">

                    <p class="font-semibold mb-2">
                        Terdapat kesalahan:
                    </p>

                    <ul class="list-disc list-inside text-sm">

                        @foreach($errors->all() as $error)

                            <li>
                                {{ $error }}
                            </li>

                        @endforeach

                    </ul>

                </div>

            @endif


            @php

                $foto = $kamar->foto_kamar ?? [];

                /*
                 * Pastikan foto selalu berupa array.
                 */
                if (is_string($foto)) {

                    $decodedFoto = json_decode($foto, true);

                    if (
                        json_last_error() === JSON_ERROR_NONE &&
                        is_array($decodedFoto)
                    ) {
                        $foto = $decodedFoto;
                    } else {
                        $foto = [];
                    }
                }

                if (!is_array($foto)) {
                    $foto = [];
                }

            @endphp


            {{-- =====================================================
                FORM
            ====================================================== --}}
            <form
                action="{{ route('admin.kamar.update', $kamar->id) }}"
                method="POST"
                enctype="multipart/form-data"
            >

                @csrf
                @method('PUT')


                {{-- =================================================
                    INFORMASI KONTRAKAN
                ================================================== --}}

                <div class="mb-8">

                    <h2 class="text-xl font-bold text-gray-800 mb-5">
                        Informasi Kontrakan
                    </h2>


                    {{-- NAMA --}}
                    <div class="mb-5">

                        <label
                            for="nama_kamar"
                            class="block mb-2 font-semibold text-gray-700"
                        >
                            Nama / Nomor Kontrakan
                        </label>

                        <input
                            id="nama_kamar"
                            type="text"
                            name="nama_kamar"
                            value="{{ old('nama_kamar', $kamar->nama_kamar) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-amber-500
                                   focus:border-amber-500"
                            required
                        >

                    </div>


                    {{-- DESKRIPSI --}}
                    <div class="mb-5">

                        <label
                            for="deskripsi"
                            class="block mb-2 font-semibold text-gray-700"
                        >
                            Deskripsi
                        </label>

                        <textarea
                            id="deskripsi"
                            name="deskripsi"
                            rows="5"
                            class="w-full border border-gray-300 rounded-xl px-4 py-3
                                   focus:outline-none focus:ring-2 focus:ring-amber-500
                                   focus:border-amber-500"
                        >{{ old('deskripsi', $kamar->deskripsi) }}</textarea>

                    </div>


                    {{-- HARGA --}}
                    <div class="mb-5">

                        <label
                            for="harga"
                            class="block mb-2 font-semibold text-gray-700"
                        >
                            Harga Sewa per Bulan
                        </label>

                        <div class="relative">

                            <span
                                class="absolute left-4 top-1/2 -translate-y-1/2
                                       text-gray-500 font-semibold"
                            >
                                Rp
                            </span>

                            <input
                                id="harga"
                                type="number"
                                name="harga"
                                min="0"
                                value="{{ old('harga', $kamar->harga) }}"
                                class="w-full border border-gray-300 rounded-xl
                                       pl-12 pr-4 py-3
                                       focus:outline-none focus:ring-2
                                       focus:ring-amber-500
                                       focus:border-amber-500"
                                required
                            >

                        </div>

                        <p class="text-xs text-gray-500 mt-2">
                            Harga yang digunakan sebagai dasar pembayaran sewa.
                        </p>

                    </div>

                </div>



                {{-- =================================================
                    FOTO BARU
                ================================================== --}}

                <div class="border-t pt-8 mb-8">

                    <h2 class="text-xl font-bold text-gray-800 mb-5">
                        Foto Kontrakan
                    </h2>


                    <label
                        for="foto_kamar"
                        class="block mb-2 font-semibold text-gray-700"
                    >
                        Upload Foto Baru
                    </label>

                    <input
                        id="foto_kamar"
                        type="file"
                        name="foto_kamar[]"
                        multiple
                        accept="image/jpeg,image/png,image/jpg,image/webp"
                        class="w-full border border-gray-300 rounded-xl p-3
                               bg-gray-50"
                    >

                    <p class="text-sm text-gray-500 mt-2">
                        Bisa upload lebih dari satu foto.
                        Format JPG, JPEG, PNG, atau WEBP.
                    </p>

                </div>



                {{-- =================================================
                    FOTO LAMA
                ================================================== --}}

                @if(count($foto) > 0)

                    <div class="border-t pt-8 mb-8">

                        <h2 class="text-xl font-bold text-gray-800 mb-2">
                            Foto Saat Ini
                        </h2>

                        <p class="text-sm text-gray-500 mb-5">
                            Gunakan ikon ☰ untuk mengatur urutan foto.
                            Centang <span class="font-semibold text-red-600">
                            Hapus Foto
                            </span>
                            jika foto ingin dihapus.
                        </p>


                        <div
                            id="gallery"
                            class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
                        >

                            @foreach($foto as $img)

                                <div
                                    class="bg-white rounded-2xl border
                                           shadow-sm p-3"
                                    data-path="{{ $img }}"
                                >

                                    {{-- DRAG HANDLE --}}
                                    <div
                                        class="drag-handle cursor-grab
                                               text-center text-2xl mb-3
                                               text-gray-400 hover:text-gray-700
                                               select-none"
                                        title="Geser untuk mengatur urutan"
                                    >
                                        ☰
                                    </div>


                                    {{-- FOTO --}}
                                    <img
                                        src="{{ asset('storage/' . $img) }}"
                                        class="w-full h-40 object-cover rounded-xl
                                               border"
                                        alt="Foto {{ $kamar->nama_kamar }}"
                                    >


                                    {{-- PATH --}}
                                    <p
                                        class="text-xs text-gray-400 mt-2 truncate"
                                        title="{{ $img }}"
                                    >
                                        {{ $img }}
                                    </p>


                                    {{-- HAPUS --}}
                                    <label
                                        class="flex items-center gap-2
                                               mt-3 text-red-600 text-sm
                                               font-semibold cursor-pointer"
                                    >

                                        <input
                                            type="checkbox"
                                            name="hapus_foto[]"
                                            value="{{ $img }}"
                                            class="rounded border-gray-300
                                                   text-red-600
                                                   focus:ring-red-500"
                                        >

                                        Hapus Foto

                                    </label>

                                </div>

                            @endforeach

                        </div>

                    </div>

                @else

                    <div class="border-t pt-8 mb-8">

                        <div
                            class="bg-gray-50 border border-dashed
                                   border-gray-300 rounded-2xl p-8
                                   text-center"
                        >

                            <p class="text-gray-500">
                                Belum ada foto kontrakan.
                            </p>

                            <p class="text-sm text-gray-400 mt-1">
                                Upload foto baru menggunakan form di atas.
                            </p>

                        </div>

                    </div>

                @endif



                {{-- =================================================
                    HIDDEN URUTAN FOTO
                ================================================== --}}

                <input
                    type="hidden"
                    name="urutan_foto"
                    id="urutan_foto"
                    value=""
                >



                {{-- =================================================
                    BUTTON
                ================================================== --}}

                <div
                    class="border-t pt-6 flex flex-col sm:flex-row
                           justify-end gap-3"
                >

                    <a
                        href="{{ route('admin.kamar.index') }}"
                        class="px-6 py-3 rounded-xl
                               border border-gray-300
                               text-gray-700 font-semibold
                               hover:bg-gray-100 text-center"
                    >
                        Batal
                    </a>


                    <button
                        type="submit"
                        class="bg-amber-600 hover:bg-amber-700
                               text-white px-6 py-3 rounded-xl
                               font-semibold transition"
                    >
                        Update Kontrakan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


{{-- =============================================================
    SORTABLE FOTO
============================================================= --}}
<script>

document.addEventListener('DOMContentLoaded', function () {

    const gallery = document.getElementById('gallery');
    const hiddenInput = document.getElementById('urutan_foto');

    if (!gallery || !hiddenInput) {
        return;
    }


    function updatePhotoOrder() {

        const items = gallery.querySelectorAll('[data-path]');

        const paths = Array.from(items).map(function (item) {
            return item.dataset.path;
        });

        hiddenInput.value = JSON.stringify(paths);
    }


    /*
     * Update urutan ketika form pertama kali dibuka.
     */
    updatePhotoOrder();


    /*
     * Drag sederhana menggunakan HTML5.
     */
    let draggedItem = null;


    gallery.querySelectorAll('[data-path]').forEach(function (item) {

        const handle = item.querySelector('.drag-handle');

        if (!handle) {
            return;
        }


        handle.setAttribute('draggable', 'true');


        handle.addEventListener('dragstart', function (event) {

            draggedItem = item;

            item.classList.add('opacity-50');

            event.dataTransfer.effectAllowed = 'move';

        });


        handle.addEventListener('dragend', function () {

            if (draggedItem) {
                draggedItem.classList.remove('opacity-50');
            }

            draggedItem = null;

            updatePhotoOrder();

        });


        item.addEventListener('dragover', function (event) {

            event.preventDefault();

            if (!draggedItem || draggedItem === item) {
                return;
            }


            const rect = item.getBoundingClientRect();

            const middle =
                rect.top + rect.height / 2;


            if (event.clientY < middle) {

                gallery.insertBefore(
                    draggedItem,
                    item
                );

            } else {

                gallery.insertBefore(
                    draggedItem,
                    item.nextSibling
                );

            }

        });

    });


    /*
     * Pastikan urutan terakhir masuk ke hidden input
     * sebelum submit.
     */
    const form = gallery.closest('form');

    if (form) {

        form.addEventListener('submit', function () {
            updatePhotoOrder();
        });

    }

});

</script>

@endsection