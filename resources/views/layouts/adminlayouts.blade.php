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
</head>

<body class="font-['Plus_Jakarta_Sans']">

<!-- ================= BACKGROUND ================= -->
<div class="fixed inset-0 -z-10">
    <img src="https://wallpapercave.com/wp/wp12225686.jpg" class="w-full h-full object-cover">
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
                    Jl. Raden Panghulu Djaja no.23 Cimahpar, Bogor Utara
                </p>
            </div>

        </a>

        <!-- USER ACTION -->
        <div class="flex items-center gap-4">

            <span class="text-sm text-gray-700">
                {{ Auth::user()->nama_lengkap ?? 'Admin' }}
            </span>

            <a href="{{ route('home') }}"
               class="text-sm bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800">
                Lihat Website
            </a>

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
                Admin Panel
            </h1>
            <p class="text-sm text-gray-500">
                Control System Kontrakan Raden Panghulu Djaja
            </p>
        </div>

        <span class="bg-red-600 text-white text-xs font-bold px-4 py-2 rounded-full">
            ADMIN ACCESS
        </span>

    </div>

</div>

<!-- ================= CONTENT ================= -->
<main class="p-6">

    <div class="max-w-7xl mx-auto flex gap-6">

        <!-- SIDEBAR -->
        <aside class="w-72 bg-white/95 backdrop-blur rounded-3xl shadow-2xl p-6 h-fit">

            <h2 class="text-xl font-bold text-amber-700 mb-6">
                Admin Menu
            </h2>

            <nav class="space-y-3 text-sm font-medium">

                <a href="{{ route('admin.panel') }}" class="block px-4 py-3 rounded-xl hover:bg-amber-100">
                    📊 Dashboard
                </a>

                <a href="{{ route('admin.kamar.index') }}" class="block px-4 py-3 rounded-xl hover:bg-amber-100">
                    🏠 Kelola Kamar
                </a>

                <a href="{{ route('admin.booking.index') }}" class="block px-4 py-3 rounded-xl hover:bg-amber-100">
                    📑 Kelola Booking
                </a>

                <a href="{{ route('admin.user.index') }}" class="block px-4 py-3 rounded-xl hover:bg-amber-100">
                    👤 Kelola User
                </a>

                <a href="{{ route('admin.review.index') }}" class="block px-4 py-3 rounded-xl hover:bg-amber-100">
                    ⭐ Review
                </a>

                <a href="{{ route('admin.chat.index') }}" class="block px-4 py-3 rounded-xl hover:bg-amber-100">
                    💬 Chat User
                </a>

                <hr class="my-4">

                <a href="{{ route('home') }}" class="block px-4 py-3 rounded-xl hover:bg-gray-100">
                    ↩ Kembali ke Website
                </a>

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
                    Admin system pengelolaan kontrakan, booking, user, dan review.
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
                        <span>Cimahpar, Bogor Utara, Kota Bogor</span>
                    </div>

                </div>

            </div>

        </div>

        <div class="border-t border-amber-500 mt-8 pt-6 text-center text-xs text-amber-100">
            © {{ date('Y') }} Kontrakan Raden Panghulu Djaja. All rights reserved.
        </div>

    </div>

</footer>

</body>
</html>