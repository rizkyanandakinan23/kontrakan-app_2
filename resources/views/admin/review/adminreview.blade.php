@extends('layouts.app')

@section('title', 'Kelola Review')

@section('content')

<div class="max-w-7xl mx-auto flex gap-6">

    <!-- ========================= -->
    <!-- SIDEBAR -->
    <!-- ========================= -->

    <aside class="w-72 bg-white/95 backdrop-blur rounded-3xl shadow-2xl p-6 h-fit">

        <h2 class="text-xl font-bold text-amber-700 mb-6">
            Admin Menu
        </h2>

        <nav class="space-y-3 text-sm font-medium">

            <a href="{{ route('admin.panel') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
                📊 Dashboard
            </a>

            <a href="{{ route('admin.kamar.index') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
                🏠 Kelola Kamar
            </a>

            <a href="{{ route('admin.user.index') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
                👤 Kelola User
            </a>

            <a href="{{ route('admin.booking.index') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
                📑 Kelola Booking
            </a>

            <a href="{{ route('admin.review.index') }}"
               class="block px-4 py-3 rounded-xl bg-amber-100 text-amber-800 font-semibold">
                ⭐ Kelola Review
            </a>

            <hr class="my-4">

            <a href="{{ route('home') }}"
               class="block px-4 py-3 rounded-xl hover:bg-gray-100 transition text-gray-700">
                ↩ Kembali ke Website
            </a>

        </nav>

    </aside>

    <!-- ========================= -->
    <!-- MAIN CONTENT -->
    <!-- ========================= -->

    <main class="flex-1">

        <!-- HEADER -->

        <div class="mb-6">

            <h1 class="text-4xl font-bold text-white drop-shadow-lg">
                Kelola Review
            </h1>

            <p class="text-white/80 mt-1">
                Monitoring review dan report user
            </p>

        </div>

        <!-- SUCCESS -->

        @if(session('success'))

            <div class="bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6">

                {{ session('success') }}

            </div>

        @endif

        <!-- TABLE -->

        <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

            <div class="overflow-x-auto">

                <table class="w-full">

                    <thead class="bg-amber-600 text-white">

                        <tr>

                            <th class="p-4 text-left">
                                User
                            </th>

                            <th class="p-4 text-left">
                                Kamar
                            </th>

                            <th class="p-4 text-left">
                                Rating
                            </th>

                            <th class="p-4 text-left">
                                Komentar
                            </th>

                            <th class="p-4 text-left">
                                Report
                            </th>

                            <th class="p-4 text-left">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($reviews as $review)

                            <tr class="border-b hover:bg-gray-50 align-top">

                                <!-- USER -->

                                <td class="p-4 font-semibold text-gray-800">

                                    {{ $review->user->name }}

                                </td>

                                <!-- KAMAR -->

                                <td class="p-4 text-gray-600">

                                    {{ $review->kamar->nama_kamar }}

                                </td>

                                <!-- RATING -->

                                <td class="p-4">

                                    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-bold">

                                        {{ $review->rating }} ⭐

                                    </span>

                                </td>

                                <!-- KOMENTAR -->

                                <td class="p-4 text-gray-600 max-w-md">

                                    {{ $review->komentar }}

                                </td>

                                <!-- REPORT -->

                                <td class="p-4">

                                    @if($review->reports_count > 0)

                                        <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-bold">

                                            {{ $review->reports_count }} Report

                                        </span>

                                    @else

                                        <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-bold">

                                            Aman

                                        </span>

                                    @endif

                                </td>

                                <!-- AKSI -->

                                <td class="p-4">

                                    <form
                                        action="{{ route('admin.review.delete', $review->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Hapus review ini?')"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-xl text-sm font-semibold transition"
                                        >
                                            Hapus
                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6" class="p-8 text-center text-gray-500">

                                    Belum ada review

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </main>

</div>

@endsection