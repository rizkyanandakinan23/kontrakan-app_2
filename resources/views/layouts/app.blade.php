<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kontrakan Raden Panghulu Djaja')</title>

   <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@200;300;400;500;600;700;800&display=swap" rel="stylesheet">

<script src="https://cdn.tailwindcss.com"></script>

<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: '#8B5E3C',
                secondary: '#EAD7B7',
                accent: '#F4C95D',
                cream: '#FAF8F5'
            }
        }
    }
}
</script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body class="font-['Plus Jakarta Sans'] bg-cream text-gray-800 antialiased">

<!-- BACKGROUND -->
<div class="fixed inset-0 -z-10">
    <img
        src="{{ asset('images/IMG-20260607-WA0008.jpg') }}"
        class="w-full h-full object-cover"
        alt="Background">
</div>

<div class="fixed inset-0 bg-[#2D2018]/55 backdrop-blur-sm -z-10"></div>

<!-- ===================== NAVBAR ===================== -->
<nav class="sticky top-0 z-30 bg-white/90 backdrop-blur-xl border-b border-[#E8DDD1] shadow-lg">

    <div class="max-w-7xl mx-auto flex items-center justify-between px-6 py-4">

        <!-- LOGO -->
        <a href="{{ route('home') }}" class="flex items-center gap-3">
            <img
    src="{{ asset('images/logo project.png') }}"
    class="w-12 h-12 rounded-2xl object-cover shadow-lg border border-amber-200"
    alt="Logo">

            <div>
                <span class="block text-xl lg:text-2xl font-extrabold tracking-wide text-amber-700">
    Kontrakan Raden Panghulu Djaja
</span>
                <p class="text-xs text-gray-500 mt-1">
                    Jl. Raden Panghulu Djaja no.23 Cimahpar, Bogor
                </p>
            </div>
        </a>

        <!-- RIGHT SIDE -->
<div class="flex items-center gap-4">

    @auth
        <div class="flex items-center gap-3">

 <img
    src="{{ Auth::user()->foto ? asset('storage/' . Auth::user()->foto) : asset('images/default-avatar.png') }}"
    alt="Avatar"
    class="w-12 h-12 rounded-full object-cover border-2 border-secondary shadow-md"
>

    <div class="text-right leading-tight">

        <p class="text-sm font-semibold text-gray-800">
            {{ auth()->user()->nama_lengkap }}
        </p>

        <p class="text-xs text-gray-500">
            {{ auth()->user()->username }}
        </p>

    </div>

</div>

        <!-- NOTIFICATION -->
        <a href="{{ route('notifications.user') }}"
           class="relative text-2xl">

            🔔

            @if(auth()->user()->unreadNotifications->count() > 0)

                <span
                    class="absolute -top-2 -right-2
                           bg-red-500 text-white
                           text-[10px]
                           min-w-[18px]
                           h-[18px]
                           flex items-center justify-center
                           rounded-full">

                    {{ auth()->user()->unreadNotifications->count() }}

                </span>

            @endif

        </a>

    @endauth

    <!-- MENU BUTTON -->
    <button onclick="toggleSidebar()"
            class="text-gray-700 text-3xl font-bold">
        ☰
    </button>

</div>

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

    <div class="flex items-center gap-4">

        <!-- AVATAR -->
        <img
            src="{{ auth()->user()->foto ? asset('storage/' . auth()->user()->foto) : asset('images/default-avatar.png') }}"
            alt="Avatar"
            class="w-14 h-14 rounded-full object-cover border-2 border-amber-500 shadow">

        <!-- USER INFO -->
        <div>

            <p class="font-semibold text-gray-800">
                {{ auth()->user()->nama_lengkap }}
            </p>

            <p class="text-sm text-gray-500">
                {{ auth()->user()->username }}
            </p>

            <p class="text-xs text-gray-400">
                {{ auth()->user()->email }}
            </p>

        </div>

    </div>

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

        <!-- MENU -->
        <a href="{{ route('home') }}"
           class="hover:text-gray-900 transition">
            Beranda
        </a>

        <a href="{{ route('kamar.index') }}"
           class="hover:text-gray-900 transition">
            Kamar
        </a>

        @auth

            <a href="{{ route('booking.riwayat') }}"
               class="hover:text-gray-900 transition">
                Riwayat Booking
            </a>

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
            <span>kontrakanRDP@gmail.com</span>
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