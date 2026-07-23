@extends('layouts.adminlayouts')

@section('title', 'Edit Kamar')

@section('content')

<div class="max-w-4xl mx-auto">

    <div class="bg-white rounded-3xl shadow-2xl p-8">

        <h1 class="text-3xl font-bold mb-8 text-gray-800">
            Edit Kontrakan
        </h1>

       
        @php

    $foto = $kamar->foto_kamar ?? [];

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
                    Nama / Nomor Kontrakan
                </label>

                <input
                    type="text"
                    name="nama_kamar"
                    value="{{ old('nama_kamar', $kamar->nama_kamar) }}"
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

    <p class="text-sm text-gray-500 mb-4">
        Geser foto menggunakan ikon ☰ untuk mengubah urutan.
    </p>

    <div
        id="gallery"
        class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6"
    >

        @foreach($foto as $img)

        <div
            class="bg-white rounded-xl border shadow p-2"
            data-path="{{ $img }}"
        >

            <div
                class="drag-handle cursor-grab text-center text-2xl mb-2"
            >
                ☰
            </div>

            <img
                src="{{ asset('storage/'.$img) }}"
                class="w-full h-40 object-cover rounded-lg"
            >

            <label class="flex items-center gap-2 mt-3 text-red-600">

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
              <input
    type="hidden"
    name="urutan_foto"
    id="urutan_foto"
>

            <button
                type="submit"
                class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-xl font-semibold"
            >
                Update Kontrakan
            </button>

        </form>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.6/Sortable.min.js"></script>

<script>

document.addEventListener('DOMContentLoaded',function(){

    const gallery=document.getElementById('gallery');

    if(!gallery) return;

    const urutan=document.getElementById('urutan_foto');

    function simpanUrutan(){

        let data=[];

        gallery.querySelectorAll('[data-path]').forEach(function(item){

            data.push(item.dataset.path);

        });

        urutan.value=JSON.stringify(data);

    }

    new Sortable(gallery,{
    animation:300,
    handle:'.drag-handle',
    onEnd:simpanUrutan
});

simpanUrutan();

});

</script>

@endsection