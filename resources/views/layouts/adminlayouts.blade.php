<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin - Kontrakan Raden Panghulu Djaja')</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="font-['Plus Jakarta Sans'] bg-cream text-gray-800 antialiased">
    
<!-- ================= BACKGROUND ================= -->
<div class="fixed inset-0 -z-10">
    <img
        src="{{ asset('images/Halaman Utama.png') }}"
        class="w-full h-full object-cover"
        alt="Background">
</div>

<div class="fixed inset-0 bg-black/30 backdrop-blur-[2px] -z-10"></div>

<!-- ================= NAVBAR ================= -->
<nav class="bg-gray-100 shadow-sm border-b border-gray-200">

    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- LOGO -->
        <a href="{{ route('admin.panel') }}" class="flex items-center gap-3">

            <img src="{{ asset('images/logo project.png') }}" class="w-10 h-10 rounded-full object-cover">

            <div>
                <span class="text-2xl font-bold text-amber-700 block">
                    Kontrakan Raden Panghulu Djaja
                </span>
                <p class="text-xs text-gray-500">
                    Jl. Raden Panghulu Djaja Gg. Insinyur RT 4 RW 3 Cimahpar, Bogor Utara, Kota Bogor
                </p>
            </div>

        </a>

        <!-- USER ACTION -->
        <div class="flex items-center gap-4">

            <!-- 🔔 NOTIFICATION -->
<div class="relative">

    <a href="{{ route('admin.notifications') }}"
       class="text-xl relative">

        🔔

        @php
            $unread = auth()->user()->unreadNotifications->count();
        @endphp

        @if($unread > 0)
            <span class="absolute -top-2 -right-2 bg-red-600 text-white text-xs px-2 py-0.5 rounded-full">
                {{ $unread }}
            </span>
        @endif

    </a>

</div>

            <span class="text-sm text-gray-700">
                {{ Auth::user()->nama_lengkap ?? 'Admin' }}
            </span>
{{-- 
            <a href="{{ route('home') }}"
               class="text-sm bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800">
                Lihat Website
            </a> --}}

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                    Logout
                </button>
            </form>

        </div>

    </div>
</nav>

<!-- ================= ADMIN TITLE BAR ================= -->
<div class="max-w-7xl mx-auto px-6 mt-4">

    <div class="flex items-center justify-between bg-white/80 backdrop-blur rounded-2xl shadow px-6 py-4">

        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                Halaman Admin
            </h1>
            <p class="text-sm text-gray-500">
                Halaman Admin Kontrakan Raden Panghulu Djaja
            </p>
        </div>

        <span class="bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full">
            AKSES ADMIN
        </span>

    </div>

</div>

<!-- ================= CONTENT ================= -->
<main class="p-6">

    <div class="max-w-7xl mx-auto flex gap-6">

        <!-- SIDEBAR -->
<aside id="sidebar"
    class="w-72 bg-white/95 backdrop-blur rounded-3xl shadow-2xl p-6 h-fit transition-all duration-300">

    <!-- HEADER SIDEBAR -->
    <div class="flex items-center justify-between mb-6">

        <h2 id="menuTitle"
            class="text-xl font-bold text-amber-700 whitespace-nowrap">
            Admin Menu
        </h2>

        <button onclick="toggleSidebar()"
            class="bg-amber-600 hover:bg-amber-700 text-white w-9 h-9 rounded-xl flex items-center justify-center">
            ☰
        </button>

    </div>

    <nav class="space-y-3 text-sm font-medium">

        <a href="{{ route('admin.panel') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-100 transition">
            <span class="text-lg">📊</span>
            <span class="menu-text">Dashboard</span>
        </a>

        <a href="{{ route('admin.kamar.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-100 transition">
            <span class="text-lg">🏠</span>
            <span class="menu-text">Kelola Kontrakan</span>
        </a>

        <a href="{{ route('admin.booking.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-100 transition">
            <span class="text-lg">📑</span>
            <span class="menu-text">Kelola Booking</span>
        </a>

        <a href="{{ route('admin.pembayaran.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-100 transition">
            <span class="text-lg">💰</span>
            <span class="menu-text">Kelola Pembayaran</span>
        </a>

        <a href="{{ route('admin.user.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-100 transition">
            <span class="text-lg">👤</span>
            <span class="menu-text">Kelola Penyewa</span>
        </a>

        <a href="{{ route('admin.review.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-100 transition">
            <span class="text-lg">⭐</span>
            <span class="menu-text">Review</span>
        </a>

        <a href="{{ route('admin.chat.index') }}"
            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-amber-100 transition">
            <span class="text-lg">💬</span>
            <span class="menu-text">Chat User</span>
        </a>

        <hr class="my-4">

    </nav>

</aside>

        <!-- PAGE CONTENT -->
        <div class="flex-1">
            @yield('content')
        </div>

    </div>

</main>

<!-- ================= FOOTER ================= -->
<footer class="bg-amber-700 text-white mt-10">

    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="grid md:grid-cols-3 gap-8">

            <div>
                <h2 class="font-bold text-2xl mb-2">
                    Kontrakan Raden Panghulu Djaja
                </h2>
                <p class="text-sm text-amber-100">
                    Sistem Administrasi Pengelolaan Kontrakan, Booking, Penyewa, Pembayaran, dll.
                </p>
            </div>


            <div>
                <h3 class="font-semibold text-lg mb-3">Kontak</h3>

                <div class="text-sm text-amber-100 space-y-2">

                    <!-- WHATSAPP -->
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/images.png') }}" class="w-5 h-5">
                        <span>+62 857 9222 3092</span>
                    </div>

                    <!-- EMAIL -->
                    <div class="flex items-center gap-2">
                        <img src="{{ asset('images/Gmail_icon_(2020).svg.png') }}" class="w-5 h-5">
                        <span>kontrakanRDP@gmail.com</span>
                    </div>

                    <!-- ALAMAT -->
                    <div class="flex items-center gap-2">
                        <span>📍</span>
                        <span>Jl. Raden Panghulu Djaja Gg. Insinyur RT 4 RW 3 Cimahpar, Bogor Utara, Kota Bogor</span>
                    </div>

                </div>

            </div>

        </div>

        <div class="border-t border-amber-500 mt-8 pt-6 text-center text-xs text-amber-100">
            © {{ date('Y') }} Kontrakan Raden Panghulu Djaja. All rights reserved.
        </div>

    </div>

</footer>

<!-- ================= PREVIEW FOTO IDENTITAS ================= -->

<div
id="identityModal"
class="fixed inset-0 bg-black/70 hidden items-center justify-center z-50">

    <div class="relative">

        <button
            onclick="closeIdentity()"
            class="absolute -top-3 -right-3 bg-white rounded-full w-8 h-8 shadow font-bold">
            ✕
        </button>

        <img
            id="identityImage"
            src=""
            class="max-w-5xl max-h-[90vh] rounded-xl shadow-2xl">

    </div>

</div>

<script>

    // ======================
// PREVIEW FOTO IDENTITAS
// ======================

function showIdentity(src){

    document.getElementById('identityImage').src = src;

    document.getElementById('identityModal').classList.remove('hidden');
    document.getElementById('identityModal').classList.add('flex');

}

function closeIdentity(){

    document.getElementById('identityModal').classList.remove('flex');
    document.getElementById('identityModal').classList.add('hidden');

}

// klik area gelap untuk menutup
document.getElementById('identityModal').addEventListener('click', function(e){

    if(e.target === this){
        closeIdentity();
    }

});

function toggleSidebar() {

    const sidebar = document.getElementById('sidebar');
    const menuTitle = document.getElementById('menuTitle');
    const menuTexts = document.querySelectorAll('.menu-text');

    if (sidebar.classList.contains('w-72')) {

        sidebar.classList.remove('w-72');
        sidebar.classList.add('w-20');

        menuTitle.classList.add('hidden');

        menuTexts.forEach(item => {
            item.classList.add('hidden');
        });

    } else {

        sidebar.classList.remove('w-20');
        sidebar.classList.add('w-72');

        menuTitle.classList.remove('hidden');

        menuTexts.forEach(item => {
            item.classList.remove('hidden');
        });

    }

}

</script>

<script>
document.querySelectorAll('.preview-identitas').forEach(function(img){

    img.addEventListener('click', function(){

        Swal.fire({
            title: 'Foto Identitas Penyewa',
            imageUrl: this.dataset.image,
            imageAlt: 'Foto Identitas',
            imageWidth: 700,
            showCloseButton: true,
            showConfirmButton: false
        });

    });

});
</script>

</body>
</html>