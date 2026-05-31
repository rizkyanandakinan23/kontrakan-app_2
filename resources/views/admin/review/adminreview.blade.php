@extends('layouts.app')

@section('title', 'Kelola Review')

@section('content')

<div class="max-w-7xl mx-auto">

    <div class="mb-6">
        @include('components.backtoadminpanel')
    </div>

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Kelola Review
        </h1>

        <p class="text-white/80 mt-2">
            Monitoring review dan report user
        </p>

    </div>

    <!-- SUCCESS MESSAGE -->
    @if(session('success'))

        <div class="bg-green-100 border border-green-200 text-green-700 px-5 py-4 rounded-2xl mb-6">

            {{ session('success') }}

        </div>

    @endif

    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- TITLE -->
        <div class="p-6 border-b">

            <h2 class="text-xl font-bold text-gray-800">
                Daftar Review
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <!-- HEAD -->
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

                <!-- BODY -->
                <tbody>

                    @forelse($reviews as $review)

                        <tr class="border-b hover:bg-gray-50 align-top transition">

                            <!-- USER -->
                            <td class="p-4">

                                <div class="font-semibold text-gray-800">
                                    {{ $review->user->name ?? '-' }}
                                </div>

                            </td>

                            <!-- KAMAR -->
                            <td class="p-4 text-gray-600">

                                {{ $review->kamar->nama_kamar ?? '-' }}

                            </td>

                            <!-- RATING -->
                            <td class="p-4">

                                <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs font-bold">

                                    {{ $review->rating }} ⭐

                                </span>

                            </td>

                            <!-- KOMENTAR -->
                            <td class="p-4 text-gray-600 max-w-md">

                                <div class="line-clamp-3">
                                    {{ $review->komentar }}
                                </div>

                            </td>

                            <!-- REPORT -->
                            <td class="p-4">

                                @if($review->reports_count > 0)

                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">

                                        {{ $review->reports_count }} Report

                                    </span>

                                @else

                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">

                                        Aman

                                    </span>

                                @endif

                            </td>

                            <!-- AKSI -->
                            <td class="p-4">

                                <form
                                    action="{{ route('admin.review.delete', $review->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus review ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-xs font-semibold transition"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="6" class="p-10 text-center text-gray-500">

                                Belum ada review

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection