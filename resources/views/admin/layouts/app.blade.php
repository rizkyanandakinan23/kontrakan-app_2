<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kontrakan Raden Panghulu Djaja')</title>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-['Plus_Jakarta_Sans']">

<!-- BACKGROUND -->
<div class="fixed inset-0 -z-10">
    <img src="https://wallpapercave.com/wp/wp12225686.jpg" class="w-full h-full object-cover">
</div>
<div class="fixed inset-0 bg-black/30 backdrop-blur-[2px] -z-10"></div>

<!-- ===================== NAVBAR ===================== -->
<nav class="bg-gray-100 shadow-sm border-b border-gray-200">

    <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img src="{{ asset('images/logo project.png') }}" class="w-10 h-10 rounded-full object-cover">

            <div>
                <span class="text-2xl font-bold text-amber-700 block">
                    Kontrakan Raden Panghulu Djaja
                </span>
                <p class="text-xs text-gray-500">
                    Jl. Raden Panghulu Djaja no.23 Cimahpar, Bogor
                </p>
            </div>
        </a>

        <!-- MENU BUTTON -->
        <button onclick="toggleSidebar()"
                class="text-gray-700 text-3xl font-bold">
            ☰
        </button>

    </div>
</nav>

<!-- ===================== OVERLAY ===================== -->
<div id="overlay"
     onclick="toggleSidebar()"
     class="fixed inset-0 bg-black/40 hidden z-40">
</div>

<!-- SIDEBAR -->
<div id="sidebar"
     class="fixed top-0 right-0 w-80 h-full bg-gray-50 shadow-2xl
            transform translate-x-full transition-transform duration-300 z-50 flex flex-col">

    <!-- HEADER -->
    <div class="p-5 border-b bg-gray-100 flex items-center justify-between">

        <h2 class="text-xl font-bold text-gray-700">
    {{ Auth::user()->nama_lengkap ?? 'Menu' }}
</h2>

        <!-- CLOSE BUTTON -->
        <button onclick="toggleSidebar()"
                class="text-gray-600 hover:text-red-500 text-2xl leading-none">
            ✕
        </button>

    </div>

    <!-- MENU CONTENT -->
    <div class="p-6 flex flex-col gap-4 text-gray-700 flex-1">

        @auth

            <!-- USER BOX -->
            <div class="bg-gray-100 rounded-xl p-4 border">

                <p class="text-xs text-gray-500 mb-1">
                    Login sebagai:
                </p>

                <p class="font-semibold text-gray-800">
                    {{ auth()->user()->username }}
                </p>

                <p class="text-xs text-gray-500 mt-1">
                    {{ auth()->user()->email }}
                </p>

            </div>

            <!-- ADMIN PANEL -->
            @if(auth()->user()->is_admin)

                <a href="{{ route('admin.panel') }}"
                   class="bg-gray-800 text-white px-4 py-3 rounded-xl text-center hover:bg-gray-900 transition">
                    Admin Panel
                </a>

            @endif

            <a href="{{ route('profile.edit') }}"
   class="hover:text-gray-900 transition">
    Edit Profile
</a>

        @endauth


            <!-- LOGOUT -->
            <form action="{{ route('logout') }}" method="POST" class="mt-auto">
                @csrf

                <button
                    class="w-full bg-red-500 hover:bg-red-600 text-white py-3 rounded-xl transition">
                    Logout
                </button>

            </form>

        @else

            <a href="{{ route('login') }}"
               class="hover:text-gray-900 transition">
                Login
            </a>

            <a href="{{ route('register') }}"
               class="bg-amber-700 text-white px-4 py-3 rounded-xl text-center hover:bg-amber-800 transition">
                Register
            </a>

        @endauth

    </div>

</div>

<!-- CONTENT -->
<main class="p-6">
    @yield('content')
</main>

<!-- FOOTER -->
<footer class="bg-amber-700 text-white mt-10">
    <div class="max-w-7xl mx-auto px-6 py-10">

        <div class="grid md:grid-cols-3 gap-8">

            <div>
                <h2 class="font-bold text-2xl mb-2">Kontrakan Raden Panghulu Djaja</h2>
                <p class="text-sm text-amber-100">
                    Platform penyewaan kamar yang mudah, cepat, dan terpercaya.
                </p>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-3">Menu</h3>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('home') }}">Beranda</a></li>
                    <li><a href="{{ route('kamar.index') }}">Kamar</a></li>
                    <li><a href="{{ route('ketentuan') }}">Ketentuan</a></li>
                    <li><a href="{{ route('qna') }}">QnA</a></li>
                </ul>
            </div>

            <div>
                <h3 class="font-semibold text-lg mb-3">Kontak</h3>
                <div>
    

    <div class="text-sm text-amber-100 space-y-2">

        <!-- WHATSAPP -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/images.png') }}" class="w-5 h-5" alt="Email">
            <span>+62 857 9222 3092</span>
        </div>

        <!-- EMAIL -->
        <div class="flex items-center gap-2">
            <img src="{{ asset('images/Gmail_icon_(2020).svg.png') }}" class="w-5 h-5" alt="Email">
            <span>rizkyanandasmancis@gmail.com</span>
        </div>

        <!-- ALAMAT -->
        <div class="flex items-center gap-2">
            <span>📍</span>
            <span>Cimahpar, Bogor Utara, Kota Bogor</span>
        </div>

    </div>
</div>
            </div>

        </div>

         <!-- ALAMAT + COPYRIGHT -->
        <div class="border-t border-amber-500 mt-8 pt-6 text-center text-xs text-amber-100">

            <!-- ALAMAT (FIX YANG HILANG) -->
            <p class="text-sm mb-2">
                Jl. Raden Panghulu Djaja No.23, Cimahpar, Bogor Utara, Kota Bogor 16155
            </p>

            <!-- COPYRIGHT -->
            © {{ date('Y') }} Kontrakan Raden Panghulu Djaja. All rights reserved.

        </div>

    </div>
</footer>

<!-- SCRIPT SIDEBAR -->
<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');

        sidebar.classList.toggle('translate-x-full');
        overlay.classList.toggle('hidden');
    }
</script>

</body>
</html>