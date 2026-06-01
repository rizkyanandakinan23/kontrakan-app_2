@extends('layouts.adminlayouts')

@section('title', 'Edit Kamar')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-3xl shadow-2xl p-8">

        <h1 class="text-3xl font-bold mb-8 text-gray-800">
            Edit Kamar
        </h1>

       
        @php

    $foto = $kamar->foto_kamar ?? [];

    $fasilitas = $kamar->fasilitas ?? [];

    // convert JSON string -> array
    if (is_string($fasilitas)) {

        $decoded = json_decode($fasilitas, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            $fasilitas = $decoded;

        } else {

            $fasilitas = [];
        }
    }

    // safety
    if (!is_array($fasilitas)) {
        $fasilitas = [];
    }

    // foto juga diamankan
    if (is_string($foto)) {

        $decodedFoto = json_decode($foto, true);

        if (json_last_error() === JSON_ERROR_NONE) {

            $foto = $decodedFoto;

        } else {

            $foto = [];
        }
    }

    if (!is_array($foto)) {
        $foto = [];
    }

@endphp


        <form
            action="{{ route('admin.kamar.update', $kamar->id) }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf
            @method('PUT')

            <!-- NAMA -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Nama / Nomor Kamar
                </label>

                <input
                    type="text"
                    name="nama_kamar"
                    value="{{ old('nama_kamar', $kamar->nama_kamar) }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3"
                    required
                >

            </div>

            <!-- FASILITAS -->
            <div class="mb-5">

                <label class="block font-semibold mb-3">
                    Fasilitas
                </label>

                <div class="grid grid-cols-2 gap-3">

                    @php
                        $listFasilitas = [
                            'WiFi',
                            'Kasur',
                            'AC',
                            'Kamar Mandi',
                            'Lemari',
                            'Dapur & Wastafel'
                        ];
                    @endphp

                    @foreach($listFasilitas as $item)

                        <label class="flex items-center gap-2">

                            <input
                                type="checkbox"
                                name="fasilitas[]"
                                value="{{ $item }}"
                                {{ in_array($item, $fasilitas ?? []) ? 'checked' : '' }}
                            >

                            {{ $item }}

                        </label>

                    @endforeach

                </div>

            </div>

            <!-- DESKRIPSI -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Deskripsi
                </label>

                <textarea
                    name="deskripsi"
                    rows="5"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3"
                >{{ old('deskripsi', $kamar->deskripsi) }}</textarea>

            </div>

            <!-- HARGA -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    value="{{ old('harga', $kamar->harga) }}"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3"
                    required
                >

            </div>

            <!-- STATUS -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Status Kamar
                </label>

                <select
                    name="status"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3"
                >

                    <option
                        value="kosong"
                        {{ $kamar->status == 'kosong' ? 'selected' : '' }}
                    >
                        Tersedia
                    </option>

                    <option
                        value="terisi"
                        {{ $kamar->status == 'terisi' ? 'selected' : '' }}
                    >
                        Terisi
                    </option>

                </select>

            </div>

            <!-- FOTO -->
            <div class="mb-6">

                <label class="block mb-2 font-semibold">
                    Upload Foto Baru
                </label>

                <input
                    type="file"
                    name="foto_kamar[]"
                    multiple
                    class="w-full border rounded-lg p-3"
                >

                <p class="text-sm text-gray-500 mt-2">
                    Bisa upload lebih dari 1 foto
                </p>

            </div>

            
<!-- PREVIEW FOTO -->
@if(count($foto) > 0)

    <div class="mb-8">

        <label class="block mb-3 font-semibold">
            Foto Saat Ini
        </label>

        <div class="flex flex-wrap gap-6">

            @foreach($foto as $img)

                <div class="w-40">

                    <!-- FOTO -->
                    <img
                        src="{{ asset('storage/' . $img) }}"
                        class="w-40 h-40 object-cover rounded-2xl border shadow"
                    >

                    <!-- HAPUS -->
                    <label class="flex items-center gap-2 mt-2 text-sm text-red-600">

                        <input
                            type="checkbox"
                            name="hapus_foto[]"
                            value="{{ $img }}"
                        >

                        Hapus Foto

                    </label>

                </div>

            @endforeach

        </div>

    </div>

@endif



            <!-- BUTTON -->
            <button
                type="submit"
                class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-semibold"
            >
                Update Kamar
            </button>

        </form>

    </div>

</div>

@endsection