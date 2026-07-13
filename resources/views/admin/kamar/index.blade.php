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

                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Foto</th>
                        <th class="p-4 text-left">Nomor Kontrakan</th>
                        <th class="p-4 text-left">Harga</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Penghuni</th>
                        <th class="p-4 text-left">Booking ID</th>
                        <th class="p-4 text-left">Mulai</th>
                        <th class="p-4 text-left">Selesai</th>
                        <th class="p-4 text-center">Aksi</th>
                    </tr>
                </thead>

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
                                >

                            @else

                                <div class="w-24 h-24 bg-gray-200 rounded-2xl flex items-center justify-center text-gray-500 text-sm">
                                    No Image
                                </div>

                            @endif

                        </td>

                        <!-- NAMA -->
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
                            Rp {{ number_format($kamar->harga,0,',','.') }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-4">

                            @if($kamar->status_booking == 'tersedia')

                                <span class="bg-green-100 text-green-700 px-4 py-1 rounded-full text-sm font-semibold">
                                    Tersedia
                                </span>

                            @elseif($kamar->status_booking == 'booking')

                                <span class="bg-yellow-100 text-yellow-700 px-4 py-1 rounded-full text-sm font-semibold">
                                    Sudah Dibooking
                                </span>

                            @else

                                <span class="bg-red-100 text-red-700 px-4 py-1 rounded-full text-sm font-semibold">
                                    Sedang Ditempati
                                </span>

                            @endif

                        </td>

                        <td class="p-4">

    @if($bookingAktif)

{{ $bookingAktif->user->nama_lengkap }}

@else

Belum ada penghuni

@endif

</td>

                        <!-- ID booking -->
                        <td class="p-4">
    @if($bookingAktif)
#{{ $bookingAktif->id }}
@else
-
@endif
</td>



<td class="p-4">
    @if($bookingAktif)
        {{ \Carbon\Carbon::parse($bookingAktif->tanggal_masuk)->format('d M Y') }}

    @else
        -
    @endif
</td>


<td class="p-4">
    @if($bookingAktif)
        {{ \Carbon\Carbon::parse($bookingAktif->tanggal_selesai)->format('d M Y') }}
    @else
        -
    @endif
</td>

                        <!-- AKSI -->
                        <td class="p-4">

                            <div class="flex justify-center gap-3">

                                <a
                                    href="{{ route('admin.kamar.edit',$kamar->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-xl text-sm"
                                >
                                    Edit
                                </a>

                                <form
                                    action="{{ route('admin.kamar.destroy',$kamar->id) }}"
                                    method="POST"
                                    onsubmit="return confirm('Yakin ingin menghapus kontrakan ini?')"
                                >

                                    @csrf
                                    @method('DELETE')

                                    <button
                                        class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl text-sm"
                                    >
                                        Hapus
                                    </button>

                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6" class="text-center p-10 text-gray-500">
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