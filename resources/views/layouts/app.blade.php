<!DOCTYPE html>
<html lang="id">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Kontrakan RDP')</title>

    <!-- FONT -->
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- TAILWIND CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- SWEETALERT -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>

<body class="font-['Plus_Jakarta_Sans']">

    <!-- BACKGROUND -->
    <div class="fixed inset-0 -z-10">

        <img
            src="https://wallpapercave.com/wp/wp12225686.jpg"
            class="w-full h-full object-cover"
            alt="Background"
        >

    </div>

    <!-- OVERLAY -->
    <div class="fixed inset-0 bg-black/30 backdrop-blur-[2px] -z-10"></div>

    <!-- NAVBAR -->
    <nav class="bg-white shadow">

        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

            <!-- LOGO -->
            <a href="{{ route('home') }}" class="flex items-center gap-3">

                <img
                    src="{{ asset('images\logo project.png') }}"
                    class="w-10 h-10 rounded-full object-cover"
                    alt="Logo"
                >

                <span class="text-2xl font-bold text-amber-700">
                    Kontrakan RDP
                </span>

            </a>

            <!-- MENU -->
            <div class="flex items-center gap-5 text-sm font-medium">

                <a href="{{ route('home') }}" class="text-gray-700 hover:text-amber-700 transition">
                    Beranda
                </a>

                <a href="{{ route('kamar.index') }}" class="text-gray-700 hover:text-amber-700 transition">
                    Kamar
                </a>

                @auth

                    @if(auth()->user()->is_admin)

                        <a href="{{ route('admin.panel') }}"
                           class="px-4 py-2 rounded-lg bg-amber-700 text-white hover:bg-amber-800 transition">
                            Admin
                        </a>

                    @endif

                    <span class="text-gray-700">
                        Halo, {{ auth()->user()->name }}
                    </span>

                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-red-500 hover:text-red-600 transition">
                            Logout
                        </button>
                    </form>

                @else

                    <a href="{{ route('login') }}" class="text-gray-700 hover:text-amber-700 transition">
                        Login
                    </a>

                    <a href="{{ route('register') }}"
                       class="bg-amber-700 text-white px-4 py-2 rounded-lg hover:bg-amber-800 transition">
                        Daftar
                    </a>

                @endauth

            </div>

        </div>

    </nav>

    <!-- CONTENT -->
    <main class="flex-1 p-6">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="bg-amber-700 text-white mt-10">

        <div class="max-w-7xl mx-auto px-6 py-10">

            <div class="grid md:grid-cols-3 gap-8">

                <!-- BRAND -->
                <div>

                    <h2 class="font-bold text-2xl mb-2">
                        Kontrakan RDP
                    </h2>

                    <p class="text-sm text-amber-100 leading-relaxed">
                        Platform pencarian dan penyewaan kamar kontrakan
                        yang mudah, nyaman, dan terpercaya.
                    </p>

                </div>

                <!-- MENU -->
                <div>

                    <h3 class="font-semibold text-lg mb-3">
                        Menu
                    </h3>

                    <ul class="space-y-2 text-sm">

                        <li>
                            <a href="{{ route('home') }}" class="hover:underline">
                                Beranda
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('kamar.index') }}" class="hover:underline">
                                Kamar
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('ketentuan') }}" class="hover:underline">
                                Ketentuan
                            </a>
                        </li>
                        <li>
    <a href="{{ route('qna') }}" class="hover:underline">
        QnA
    </a>
</li>


                        @auth

                            @if(auth()->user()->is_admin)

                                <li class="pt-3 font-semibold text-amber-300">
                                    Admin Panel
                                </li>

                                <li>
                                    <a href="{{ route('admin.panel') }}" class="hover:underline">
                                        Dashboard Admin
                                    </a>
                                </li>

                            @endif

                        @endauth

                    </ul>

                </div>

                <!-- CONTACT -->
                <div>

                    <h3 class="font-semibold text-lg mb-3">
                        Kontak
                    </h3>

                    <div class="space-y-2 text-sm text-amber-100">

                        <p>WhatsApp: 0812-3456-7890</p>
                        <p>Email: kontrakanrdp@gmail.com</p>
                        <p>Cimahpar, Bogor</p>

                    </div>

                </div>

            </div>

            <div class="border-t border-amber-500 mt-8 pt-6 text-center text-xs text-amber-100">

                © {{ date('Y') }} Kontrakan RDP. All rights reserved.

            </div>

        </div>

    </footer>

</body>
</html>