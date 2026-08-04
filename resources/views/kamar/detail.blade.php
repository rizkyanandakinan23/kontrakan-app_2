@extends('layouts.app')

@section('title', 'Detail Kamar')

@section('content')

@php
    // ======================
    // FOTO SAFE
    // ======================
    $foto = $kamar->foto_kamar ?? [];

    if (is_string($foto)) {
        $decoded = json_decode($foto, true);
        $foto = json_last_error() === JSON_ERROR_NONE ? $decoded : [];
    }

    if (!is_array($foto)) {
        $foto = [];
    }

    $foto = array_values($foto); // reset index
    $mainImage = $foto[0] ?? 'default.jpg';

@endphp

<div class="max-w-7xl mx-auto">

    <!-- BACK -->
    <div class="mb-6">
        @include('components.back')
    </div>

    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="grid lg:grid-cols-2 gap-10">

            <!-- =========================
                 IMAGE SECTION
            ========================== -->
            <div class="p-6">

                <!-- MAIN IMAGE + ARROW -->
                <div class="relative">

                    <button
                        type="button"
                        onclick="prevImage()"
                        class="absolute left-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white shadow-lg w-10 h-10 rounded-full flex items-center justify-center z-10"
                    >
                        ◀
                    </button>

                    <img
    id="mainImage"
    src="{{ asset('storage/' . $mainImage) }}"
    class="max-w-full max-h-[450px] w-auto h-auto object-contain rounded-3xl shadow-lg transition duration-300 mx-auto cursor-zoom-in"
    onclick="openPreview()"
>

                    <button
                        type="button"
                        onclick="nextImage()"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white shadow-lg w-10 h-10 rounded-full flex items-center justify-center z-10"
                    >
                        ▶
                    </button>

                </div>

                <!-- THUMBNAIL -->
                @if(count($foto) > 1)
                <div class="mt-4 flex gap-4 overflow-x-auto px-2 pb-2">

                    @foreach($foto as $index => $img)

                        <img
                            src="{{ asset('storage/' . $img) }}"
                            onclick="setImage({{ $index }})"
                            class="thumb w-24 h-24 object-cover rounded-xl cursor-pointer border-4 border-transparent hover:border-amber-500 transition flex-shrink-0"
                        >

                    @endforeach

                </div>
                @endif

            </div>

            <!-- =========================
                 DETAIL SECTION
            ========================== -->
            <div class="p-8">

                <!-- STATUS -->
                <div class="mb-4">

                    @if($statusBooking == 'tersedia')

    <span class="bg-green-100 text-green-700 px-5 py-2 rounded-full text-sm font-bold">
        Tersedia
    </span>

@elseif($statusBooking == 'booking')

    <span class="bg-green-100 text-white-700 px-5 py-2 rounded-full text-sm font-bold">
        Sudah Dibooking
    </span>

@else

    <span class="bg-red-100 text-red-700 px-5 py-2 rounded-full text-sm font-bold">
        Sedang Ditempati
    </span>

@endif

                </div>

                <!-- NAMA -->
                <h1 class="text-5xl font-bold text-gray-800 mb-4">
                    {{ $kamar->nama_kamar }}
                </h1>

                <!-- HARGA -->
                <div class="mb-8">
                    <p class="text-gray-500 text-lg">Harga Sewa</p>
                    <h2 class="text-5xl font-extrabold text-amber-700 mt-2">
                        Rp {{ number_format($kamar->harga, 0, ',', '.') }}
                    </h2>
                    <p class="text-gray-400 mt-1">/ bulan</p>
                </div>

                <!-- DESKRIPSI -->
                <div class="mb-8">
                    <h3 class="text-2xl font-bold text-gray-800 mb-3">
                        Deskripsi
                    </h3>
                    <p class="text-gray-600 leading-relaxed text-lg">
                        {!! nl2br(e($kamar->deskripsi)) !!}
                    </p>
                </div>

                <!-- BUTTON -->
                <div class="flex gap-4 flex-wrap">

                    <a href="{{ route('kamar.index') }}"
                       class="bg-gray-200 hover:bg-gray-300 px-6 py-4 rounded-2xl font-semibold transition">
                        Kembali
                    </a>

                    @if($statusBooking == 'tersedia')

<a
    href="{{ route('booking.index', [
        'id' => $kamar->id,
        'tanggal' => $tanggal
    ]) }}"
    class="bg-amber-700 hover:bg-amber-800 text-white px-8 py-4 rounded-2xl font-bold shadow-lg transition"
>
    Sewa Sekarang
</a>

@elseif($statusBooking == 'booking')

<button
    disabled
    class="bg-green-500 text-white px-8 py-4 rounded-2xl font-bold cursor-not-allowed"
>
    Sudah Dibooking
</button>

@else

<button
    disabled
    class="bg-red-500 text-white px-8 py-4 rounded-2xl font-bold cursor-not-allowed"
>
    Sedang Ditempati
</button>

@endif

                </div>

            </div>

        </div>

    </div>

    @include('components.maps')
    @include('components.review', ['kamar' => $kamar])
    @include('components.floating-chat')

    <!-- ======================
     IMAGE PREVIEW
====================== -->
<div
    id="imagePreview"
    class="fixed inset-0 bg-black/90 hidden items-center justify-center z-[9999]"
>

    <button
        onclick="closePreview()"
        class="absolute top-5 right-8 text-white text-5xl z-20"
    >
        &times;
    </button>

    <img
        id="previewImage"
        src=""
        class="max-w-[90vw] max-h-[90vh] object-contain cursor-zoom-in transition duration-300"
    >

</div>

</div>

<!-- ======================
     IMAGE GALLERY SCRIPT
====================== -->
<script>
let images = @json($foto);
let currentIndex = 0;

function updateImage() {
    if (!images.length) return;

    document.getElementById('mainImage').src = '/storage/' + images[currentIndex];

    document.querySelectorAll('.thumb').forEach((el, i) => {
        el.classList.remove('border-amber-500');
        if (i === currentIndex) {
            el.classList.add('border-amber-500');
        }
    });
}

function nextImage() {
    if (!images.length) return;
    currentIndex = (currentIndex + 1) % images.length;
    updateImage();
}

function prevImage() {
    if (!images.length) return;
    currentIndex = (currentIndex - 1 + images.length) % images.length;
    updateImage();
}

function setImage(index) {
    currentIndex = index;
    updateImage();
}
</script>

<script>
    let zoom = 1;

function openPreview(){

    const modal = document.getElementById('imagePreview');
    const img = document.getElementById('previewImage');

    img.src = document.getElementById('mainImage').src;

    zoom = 1;

    img.style.transform = 'scale(1)';

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closePreview(){

    document.getElementById('imagePreview').classList.remove('flex');
    document.getElementById('imagePreview').classList.add('hidden');

}

document.getElementById('previewImage').addEventListener('wheel',function(e){

    e.preventDefault();

    if(e.deltaY < 0){

        zoom += 0.2;

    }else{

        zoom -= 0.2;

    }

    zoom = Math.max(1,Math.min(5,zoom));

    this.style.transform='scale('+zoom+')';

});
</script>

@endsection