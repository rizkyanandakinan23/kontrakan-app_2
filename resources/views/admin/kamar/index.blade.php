@extends('layouts.adminlayouts')

@section('title', 'Kelola Kamar')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-8">

        <div>
            <h1 class="text-4xl font-bold text-white drop-shadow-lg">
                Kelola Kontrakan
            </h1>

            <p class="text-white/80 mt-2">
                Tambah, edit, dan hapus data kontrakan
            </p>
        </div>

        <a
            href="{{ route('admin.kamar.create') }}"
            class="bg-amber-600 hover:bg-amber-700 text-white px-6 py-3 rounded-2xl font-semibold shadow-xl transition"
        >
            + Tambah Kontrakan
        </a>

    </div>


    <!-- FILTER TANGGAL -->
    <div class="bg-white rounded-2xl shadow-lg p-5 mb-6">

        <form method="GET" class="flex items-end gap-3">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    Lihat Status pada Tanggal
                </label>

                <input
                    type="date"
                    name="tanggal"
                    value="{{ $tanggal->toDateString() }}"
                    class="border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-amber-500 focus:border-amber-500"
                >
            </div>

            <button
                type="submit"
                class="bg-amber-600 hover:bg-amber-700 text-white px-5 py-2 rounded-xl font-semibold transition"
            >
                Tampilkan
            </button>

        </form>

    </div>


    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="overflow-x-auto">

            <table class="w-full">

                <!-- TABLE HEADER -->
                <thead class="bg-amber-600 text-white">

                    <tr>

                        <th class="p-4 text-left">
                            Foto
                        </th>

                        <th class="p-4 text-left">
                            Nomor Kontrakan
                        </th>

                        <th class="p-4 text-left">
                            Harga
                        </th>

                        <th class="p-4 text-left">
                            Status
                        </th>

                        <th class="p-4 text-left">
                            Penyewa
                        </th>

                        <th class="p-4 text-left">
                            Booking ID
                        </th>

                        <th class="p-4 text-left">
                            Mulai
                        </th>

                        <th class="p-4 text-left">
                            Selesai
                        </th>

                        <th class="p-4 text-left">
                            Foto identitas penyewa
                        </th>

                        <th class="p-4 text-center">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <!-- TABLE BODY -->
                <tbody>

                @forelse($kamars as $kamar)

                    @php
                        $fotoUtama = $kamar->foto_kamar[0] ?? null;
                        $bookingAktif = $kamar->booking_aktif;
                    @endphp

                    <tr class="border-b hover:bg-gray-50 transition">

                        <!-- FOTO -->
                        <td class="p-2">

                            @if($fotoUtama)

                                <img
                                    src="{{ asset('storage/' . $fotoUtama) }}"
                                    class="w-24 h-24 object-cover rounded-2xl border"
                                    alt="{{ $kamar->nama_kamar }}"
                                >

                            @else

                                <div
                                    class="w-24 h-24 bg-gray-200 rounded-2xl flex items-center justify-center text-gray-500 text-sm"
                                >
                                    No Image
                                </div>

                            @endif

                        </td>


                        <!-- NAMA KONTRAKAN -->
                        <td class="p-4">

                            <h2 class="font-bold text-gray-800 text-lg">
                                {{ $kamar->nama_kamar }}
                            </h2>

                            <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                                {{ $kamar->deskripsi }}
                            </p>

                        </td>


                        <!-- HARGA -->
                        <td class="p-4 font-semibold text-amber-700">

                            Rp {{ number_format($kamar->harga, 0, ',', '.') }}

                        </td>


                        <!-- STATUS -->
                        <td class="p-4">

                            @if($kamar->status_booking == 'tersedia')

                                <span
                                    class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-semibold"
                                >
                                    Tersedia
                                </span>

                            @elseif($kamar->status_booking == 'booking')

                                <span
                                    class="bg-yellow-100 text-yellow-700 px-4 py-1 rounded-full text-sm font-semibold"
                                >
                                    Dibooking
                                </span>

                            @else

                                <span
                                    class="bg-red-100 text-red-700 px-4 py-1 rounded-full text-sm font-semibold"
                                >
                                    Sedang Ditempati
                                </span>

                            @endif

                        </td>


                        <!-- PENYEWA -->
                        <td class="p-4">

                            @if($bookingAktif)

                                <a href="{{ route('admin.user.index') }}">
                                    {{ $bookingAktif->user->nama_lengkap }}
                                </a>

                            @else

                                Belum ada penghuni

                            @endif

                        </td>


                        <!-- BOOKING ID -->
                        <td class="p-4">

                            @if($bookingAktif)

                                <a
                                    href="{{ route('admin.booking.index') }}"
                                    class="text-blue-600 hover:underline"
                                >
                                    #{{ $bookingAktif->id }}
                                </a>

                            @else

                                -

                            @endif

                        </td>


                        <!-- TANGGAL MULAI -->
                        <td class="p-4">

                            @if($bookingAktif)

                                {{ \Carbon\Carbon::parse($bookingAktif->tanggal_masuk)->format('d M Y') }}

                            @else

                                -

                            @endif

                        </td>


                        <!-- TANGGAL SELESAI -->
                        <td class="p-4">

                            @if($bookingAktif)

                                {{ \Carbon\Carbon::parse($bookingAktif->tanggal_selesai)->format('d M Y') }}

                            @else

                                -

                            @endif

                        </td>


                        <!-- FOTO IDENTITAS PENYEWA -->
                        <td class="p-4">

                            @if($bookingAktif && $bookingAktif->foto_identitas)

                                <img
                                    src="{{ asset('storage/' . $bookingAktif->foto_identitas) }}"
                                    data-image="{{ asset('storage/' . $bookingAktif->foto_identitas) }}"
                                    class="w-16 h-16 object-cover rounded-lg border cursor-pointer preview-identitas"
                                    alt="Foto identitas penyewa"
                                >

                            @else

                                <span class="text-gray-500 text-xs">
                                    Belum Upload
                                </span>

                            @endif

                        </td>


                    <!-- AKSI -->
                    <td class="p-4">

                        <div class="flex flex-col items-center gap-2">

                            <!-- TOMBOL AKSI -->
                            <div class="flex justify-center gap-3">

                                <!-- EDIT -->
                                <a
                                    href="{{ route('admin.kamar.edit', $kamar->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm"
                                >
                                    Edit
                                </a>

                                 <!-- SET TERSEDIA -->
                                @if($kamar->status_booking == 'terisi' && $bookingAktif)

                                    <form
                                        action="{{ route('admin.kamar.setTersedia', $bookingAktif->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('Yakin penyewa sudah meninggalkan kontrakan dan kamar ingin disediakan kembali?')"
                                    >
                                        @csrf

                                        <button
                                            type="submit"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl text-sm"
                                        >
                                            Set Tersedia
                                        </button>
                                    </form>

                                @endif

                                <!-- HAPUS -->
                                @if($kamar->tidak_bisa_dihapus)

                                    <button 
                                        type="button" 
                                        disabled 
                                        class="bg-gray-400 text-white px-4 py-2 rounded-xl text-sm cursor-not-allowed"
                                    >
                                        Hapus
                                    </button>

                                @else

                                    <form 
                                        action="{{ route('admin.kamar.destroy', $kamar->id) }}" 
                                        method="POST" 
                                        onsubmit="return confirm('Yakin ingin menghapus kontrakan ini? Data histori booking dan pembayaran tetap tersimpan.')" 
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <button 
                                            type="submit" 
                                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm"
                                        >
                                            Hapus
                                        </button>
                                    </form>

                                @endif

                            </div>


                            <!-- KETERANGAN HAPUS -->
                            @if($kamar->tidak_bisa_dihapus)

                                <span class="text-xs text-red-500 text-center max-w-[180px]">
                                    {{ $kamar->alasan_tidak_bisa_dihapus }}
                                </span>

                            @endif

                        </div>

                    </td>

                    </tr>

                @empty

                    <tr>

                        <td
                            colspan="10"
                            class="text-center p-10 text-gray-500"
                        >
                            Belum ada data kontrakan.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection