@extends('layouts.adminlayouts')

@section('title', 'Tambah Kamar')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-3xl shadow-2xl p-8">

        <h1 class="text-3xl font-bold mb-8 text-gray-800">
            Tambah Kontrakan
        </h1>

        <form
            action="{{ route('admin.kamar.store') }}"
            method="POST"
            enctype="multipart/form-data"
        >

            @csrf

            <!-- NAMA -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Nama / Nomor Kontrakan
                </label>

                <input
                    type="text"
                    name="nama_kamar"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3"
                    required
                >

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
                ></textarea>

            </div>

            <!-- HARGA -->
            <div class="mb-5">

                <label class="block mb-2 font-semibold">
                    Harga
                </label>

                <input
                    type="number"
                    name="harga"
                    class="w-full border border-gray-300 rounded-xl px-4 py-3"
                    required
                >

            </div>

            <!-- FOTO -->
            <div class="mb-8">

                <label class="block mb-2 font-semibold">
                    Foto Kontrakan
                </label>

                <input    type="file"    name="foto_kamar[]"    multiple    class="w-full border rounded-lg p-3">

            </div>

            <!-- BUTTON -->
            <button
                type="submit"
                class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-semibold"
            >
                Simpan Kontrakan
            </button>

        </form>

    </div>

</div>

@endsection