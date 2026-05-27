@extends('layouts.app')

@section('title', 'Kelola Booking')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8">

        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Kelola Booking
        </h1>

        <p class="text-white/80 mt-2">
            Manajemen booking dan verifikasi pembayaran
        </p>

    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <!-- TITLE -->
        <div class="p-6 border-b">
            <h2 class="text-xl font-bold text-gray-800">
                Daftar Booking
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="p-4 text-left">User</th>
                        <th class="p-4 text-left">Kamar</th>
                        <th class="p-4 text-left">WhatsApp</th>
                        <th class="p-4 text-left">Durasi</th>
                        <th class="p-4 text-left">Metode</th>
                        <th class="p-4 text-left">Total</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Bukti</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($bookings as $booking)

                    <tr class="border-b hover:bg-gray-50">

                        <!-- USER -->
                        <td class="p-4">
                            <div class="font-semibold">
                                {{ $booking->user->nama_lengkap ?? '-' }}
                            </div>
                            <div class="text-xs text-gray-500">
                                {{ $booking->user->username ?? '-' }}
                            </div>
                        </td>

                        <!-- KAMAR -->
                        <td class="p-4">
                            {{ $booking->kamar->nama_kamar ?? '-' }}
                        </td>

                        <!-- WHATSAPP -->
                        <td class="p-4">
                            {{ $booking->whatsapp }}
                        </td>

                        <!-- DURASI -->
                        <td class="p-4">
                            {{ $booking->durasi }} bulan
                        </td>

                        <!-- METODE -->
                        <td class="p-4">
                            {{ $booking->metode_pembayaran }}
                        </td>

                        <!-- TOTAL -->
                        <td class="p-4 font-bold text-amber-700">
                            Rp {{ number_format($booking->total_harga, 0, ',', '.') }}
                        </td>

                        <!-- STATUS -->
                        <td class="p-4">

                            @if($booking->status_pembayaran == 'pending')

    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
        Pending
    </span>

@elseif($booking->status_pembayaran == 'dibayar')

    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
        Dibayar
    </span>

@else

    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
        Ditolak
    </span>

@endif

                        </td>

                        <!-- BUKTI -->
                        <td class="p-4">

                            @if($booking->bukti_pembayaran)

    <img src="{{ asset('storage/'.$booking->bukti_pembayaran) }}"
         class="w-20 h-20 object-cover rounded-lg border">

@else
    <span class="text-gray-400 text-sm">Belum upload</span>
@endif

                        </td>

                        <!-- ACTION -->
                        <td class="p-4 flex gap-2">

                            <!-- APPROVE -->
<form action="{{ route('admin.booking.approve', $booking->id) }}" method="POST">
    @csrf
    @method('PATCH')

    <button
        class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-lg text-xs"
    >
        Approve
    </button>
</form>

                            <!-- REJECT -->
                            <form action="{{ route('admin.booking.reject', $booking->id) }}" method="POST">
                                @csrf
                                @method('PATCH')

                                <button class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-lg text-xs">
                                    Reject
                                </button>
                            </form>

                            <!-- DELETE -->
                            <form action="{{ route('admin.booking.delete', $booking->id) }}" method="POST">
                                @csrf
                                @method('DELETE')

                                <button class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg text-xs">
                                    Delete
                                </button>
                            </form>

                        </td>

                    </tr>

                    @empty

                    <tr>
                        <td colspan="9" class="p-8 text-center text-gray-500">
                            Belum ada data booking
                        </td>
                    </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@if(session('wa_link'))

<script>

    window.open("{{ session('wa_link') }}", "_blank");

</script>

@endif

    @include('components.back')

@endsection