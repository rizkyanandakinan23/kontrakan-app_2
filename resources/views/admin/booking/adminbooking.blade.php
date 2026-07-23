@extends('layouts.adminlayouts')

@section('title', 'Kelola Booking')

@section('content')

<div class="max-w-7xl mx-auto">

    <!-- HEADER -->
    <div class="mb-8">
        <h1 class="text-4xl font-bold text-white drop-shadow-lg">
            Kelola Booking
        </h1>
        <p class="text-white/80 mt-2">
            Monitoring booking kontrakan
        </p>
    </div>

    <!-- TABLE -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden">

        <div class="p-6 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold text-gray-800">
                Daftar Booking
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full text-sm">

                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Booking ID</th>
                        <th class="p-4 text-left">Tanggal Booking</th>
                        <th class="p-4 text-left">User</th>
                        <th class="p-4 text-left">Kamar</th>
                        <th class="p-4 text-left">WhatsApp</th>
                        <th class="p-4 text-left">Foto Identitas</th>
                        <th class="p-4 text-left">Durasi</th>
                        <th class="p-4 text-left">Mulai</th>
                        <th class="p-4 text-left">Selesai</th>
                        <th class="p-4 text-left">Metode</th>
                        <th class="p-4 text-left">Total</th>
                        <th class="p-4 text-left">Status</th>
                        <th class="p-4 text-left">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                @forelse($bookings as $booking)
                
                    <!-- USER -->
                    <tr class="border-b hover:bg-gray-50">

                        <td class="p-4">

    <span class="font-mono font-semibold text-blue-600">
        #{{ $booking->id }}
    </span>

</td>

<td class="p-4 text-sm">

    {{ $booking->payment?->created_at?->setTimezone('Asia/Jakarta')->format('d M Y H:i') ?? '-' }}

</td>

                        <td class="p-4">
                            <a href="{{ route('admin.user.index', $booking->id) }}">
                            <div class="font-semibold">
                                {{ $booking->user->nama_lengkap ?? '-' }}
                            </div>

                            <div class="text-xs text-gray-500">
                                {{ $booking->user->email ?? '-' }}
                            </div></a>
                        </td>
                        

                        <!-- KAMAR -->
                        <td class="p-4">
                            {{ $booking->kamar->nama_kamar ?? '-' }}
                        </td>

                        <!-- NO. TELEPON -->
<td class="p-4">
    {{ $booking->user->no_telp }}
</td>

<!-- FOTO IDENTITAS -->
<td class="p-4">

@if($booking->foto_identitas)

    <img
        src="{{ asset('storage/'.$booking->foto_identitas) }}"
        data-image="{{ asset('storage/'.$booking->foto_identitas) }}"
        class="w-16 h-16 object-cover rounded-lg border cursor-pointer preview-identitas"
    >

@else

    <span class="text-gray-500 text-xs">
        Belum Upload
    </span>

@endif

</td>

                        <!-- DURASI -->
                        <td class="p-4">
                            {{ $booking->durasi }} bulan
                        </td>

                        <!-- MULAI -->
                        <td class="p-4 text-gray-500">
                            {{ \Carbon\Carbon::parse($booking->tanggal_masuk)->format('d M Y') }}
                        </td>

                        <!-- SELESAI -->
                        <td class="p-4 text-gray-500">
                            {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
                        </td>

                        <!-- METODE -->
<td class="p-4">
    {{ strtoupper($booking->payment->payment_type ?? '-') }}
</td>

                        <!-- TOTAL -->
                        <td class="p-4 font-bold text-amber-700">
                            Rp {{ number_format($booking->payment->jumlah ?? 0, 0, ',', '.') }}
                        </td>

                       <!-- STATUS -->
<td class="p-4">

@php
    $paymentStatus = $booking->payment->transaction_status ?? 'pending';
@endphp


@if($paymentStatus == 'pending')

    <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-xs">
        Pending
    </span>


@elseif($paymentStatus == 'settlement' || $paymentStatus == 'capture')

    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs">
        Paid
    </span>


@elseif($paymentStatus == 'expire')

    <span class="bg-gray-200 text-gray-700 px-3 py-1 rounded-full text-xs">
        Expired
    </span>


@elseif($paymentStatus == 'cancel' || $paymentStatus == 'deny')

    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs">
        Failed
    </span>


@else

    <span class="bg-black text-white px-3 py-1 rounded-full text-xs">
        {{ ucfirst($paymentStatus) }}
    </span>

@endif

</td>

                        <!-- ACTION -->
                        <td class="p-4">

                            <div class="flex gap-2 flex-wrap">

                                <!-- DETAIL -->
                                <a href="{{ route('admin.booking.detail', $booking->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-lg text-xs">
                                    Detail
                                </a>

                                <!-- DELETE -->
                                <form action="{{ route('admin.booking.delete', $booking->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')

                                    <button onclick="return confirm('Hapus booking ini?')"
                                        class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1 rounded-lg text-xs">
                                        Delete
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty
                    <tr>
                        <td colspan="10" class="p-8 text-center text-gray-500">
                            Belum ada data booking
                        </td>
                    </tr>
                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>


@endsection