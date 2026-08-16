@extends('layouts.adminlayouts')

@section('title', 'Kelola Booking')

@section('content')

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

                <th class="p-4 text-left">
                    Booking ID
                </th>

                <th class="p-4 text-left">
                    Tanggal Booking
                </th>

                <th class="p-4 text-left">
                    Penyewa
                </th>

                <th class="p-4 text-left">
                    Nomor Kontrakan
                </th>

                <th class="p-4 text-left">
                    Nomor Telepon
                </th>

                <th class="p-4 text-left">
                    Foto Identitas
                </th>

                <th class="p-4 text-left">
                    Durasi
                </th>

                <th class="p-4 text-left">
                    Mulai
                </th>

                <th class="p-4 text-left">
                    Selesai
                </th>

                <th class="p-4 text-left">
                    Pembayaran
                </th>

                <th class="p-4 text-left">
                    Status Booking
                </th>

                <th class="p-4 text-left">
                    Aksi
                </th>

            </tr>

        </thead>


        <tbody>

        @forelse($bookings as $booking)

            @php

                /*
                |--------------------------------------------------------------------------
                | PAYMENT PER PERIODE
                |--------------------------------------------------------------------------
                */

                $payments = $booking->payments
                    ->sortBy('periode_ke')
                    ->values();

                $totalDibayar = $payments
                    ->whereIn('status', ['success'])
                    ->sum('jumlah');

                $periodeLunas = $payments
                    ->where('status', 'success')
                    ->count();

                $periodeBerikutnya = $periodeLunas + 1;

                $paymentBerikutnya = $payments
                    ->where('periode_ke', $periodeBerikutnya)
                    ->first();

            @endphp


            <tr class="border-b hover:bg-gray-50">


                <!-- BOOKING ID -->

                <td class="p-4">

                    <span class="font-mono font-semibold text-blue-600">
                        #{{ $booking->id }}
                    </span>

                </td>


                <!-- TANGGAL BOOKING -->

                <td class="p-4 text-gray-600">

                    {{ $booking->created_at
                        ? $booking->created_at
                            ->timezone('Asia/Jakarta')
                            ->format('d M Y H:i')
                        : '-'
                    }}

                </td>


                <!-- USER -->

                <td class="p-4">

                    <div class="font-semibold">
                        {{ $booking->user->nama_lengkap ?? '-' }}
                    </div>

                    <div class="text-xs text-gray-500">
                        {{ $booking->user->email ?? '-' }}
                    </div>

                </td>


                <!-- KAMAR -->

                <td class="p-4">

                    {{ $booking->kamar->nama_kamar ?? '-' }}

                </td>


                <!-- NO TELEPON -->

                <td class="p-4">

                    {{ $booking->whatsapp
                        ?? $booking->user->no_telp
                        ?? '-'
                    }}

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

                    {{ \Carbon\Carbon::parse(
                        $booking->tanggal_masuk
                    )->format('d M Y') }}

                </td>


                <!-- SELESAI -->

                <td class="p-4 text-gray-500">

                    {{ \Carbon\Carbon::parse(
                        $booking->tanggal_selesai
                    )->format('d M Y') }}

                </td>


                <!-- PEMBAYARAN PER PERIODE -->

                <td class="p-4">

                    <div class="space-y-2 min-w-[180px]">

                        @forelse($payments as $payment)

                            <div class="border rounded-lg p-2 bg-gray-50">

                                <div class="font-semibold text-gray-800">
                                    Periode {{ $payment->periode_ke }}
                                </div>

                                <div class="text-xs text-gray-600">

                                    Rp {{ number_format(
                                        $payment->jumlah,
                                        0,
                                        ',',
                                        '.'
                                    ) }}

                                </div>


                                @if($payment->status === 'success')

                                    <span class="inline-block mt-1
                                        bg-green-100
                                        text-green-700
                                        px-2 py-1
                                        rounded-full
                                        text-xs">

                                        Lunas

                                    </span>


                                @elseif($payment->status === 'pending')

                                    <span class="inline-block mt-1
                                        bg-yellow-100
                                        text-yellow-700
                                        px-2 py-1
                                        rounded-full
                                        text-xs">

                                        Pending

                                    </span>


                                @elseif($payment->status === 'failed')

                                    <span class="inline-block mt-1
                                        bg-red-100
                                        text-red-700
                                        px-2 py-1
                                        rounded-full
                                        text-xs">

                                        Gagal

                                    </span>


                                @else

                                    <span class="inline-block mt-1
                                        bg-gray-200
                                        text-gray-700
                                        px-2 py-1
                                        rounded-full
                                        text-xs">

                                        {{ ucfirst($payment->status ?? '-') }}

                                    </span>

                                @endif


                                @if($payment->paid_at)

                                    <div class="text-xs text-gray-400 mt-1">

                                        {{ \Carbon\Carbon::parse(
                                            $payment->paid_at
                                        )
                                        ->timezone('Asia/Jakarta')
                                        ->format('d M Y H:i') }}

                                    </div>

                                @endif

                            </div>

                        @empty

                            <span class="text-gray-500 text-xs">
                                Belum ada pembayaran
                            </span>

                        @endforelse


                        <!-- TOTAL YANG SUDAH DIBAYAR -->

                        @if($totalDibayar > 0)

                            <div class="pt-1 font-semibold text-green-700 text-xs">

                                Total dibayar:
                                Rp {{ number_format(
                                    $totalDibayar,
                                    0,
                                    ',',
                                    '.'
                                ) }}

                            </div>

                        @endif

                    </div>

                </td>


                <!-- STATUS BOOKING -->

                <td class="p-4">

                    @if($booking->status === 'cancel')

                        <span class="bg-red-100 text-red-700
                            px-3 py-1 rounded-full text-xs">

                            Dibatalkan

                        </span>


                    @elseif($booking->status === 'paid')

                        <span class="bg-green-100 text-green-700
                            px-3 py-1 rounded-full text-xs">

                            Aktif

                        </span>


                    @else

                        <span class="bg-yellow-100 text-yellow-700
                            px-3 py-1 rounded-full text-xs">

                            Menunggu Pembayaran

                        </span>

                    @endif

                </td>


                <!-- AKSI -->

                <td class="p-4">

                    <div class="flex gap-2 flex-wrap">


                        <!-- DETAIL -->

                        <a
                            href="{{ route(
                                'admin.booking.detail',
                                $booking->id
                            ) }}"

                            class="bg-blue-600 hover:bg-blue-700
                            text-white px-3 py-1
                            rounded-lg text-xs">

                            Detail

                        </a>


                        <!-- DELETE -->

                        <form
                            action="{{ route(
                                'admin.booking.delete',
                                $booking->id
                            ) }}"

                            method="POST">

                            @csrf

                            @method('DELETE')


                            <button
                                onclick="return confirm(
                                    'Hapus booking ini?'
                                )"

                                class="bg-gray-600 hover:bg-gray-700
                                text-white px-3 py-1
                                rounded-lg text-xs">

                                Delete

                            </button>

                        </form>

                    </div>

                </td>


            </tr>


        @empty

            <tr>

                <td
                    colspan="12"
                    class="p-8 text-center text-gray-500">

                    Belum ada data booking

                </td>

            </tr>

        @endforelse

        </tbody>

    </table>

</div>

</div>

@endsection
