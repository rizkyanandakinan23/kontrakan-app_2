@extends('layouts.adminlayouts')

@section('title', 'Detail Pendapatan Bulanan')

@section('content')

<div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-3xl shadow p-8 mb-6">

    <h1 class="text-3xl font-bold">
        📊 Detail Pendapatan Bulanan
    </h1>

    <p class="mt-2 text-blue-100">
    Rincian seluruh transaksi pembayaran pada bulan
    <strong>{{ $namaBulan }} {{ $tahun }}</strong>.
    </p>

</div>

<div class="mb-6">
    <a href="{{ route('admin.pembayaran.index') }}"
       class="inline-flex items-center bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded-xl transition">
        ← Kembali
    </a>
</div>

{{-- Ringkasan --}}
<div class="grid md:grid-cols-3 gap-5 mb-8">

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500 text-sm">
            Total Transaksi
        </p>

        <h2 class="text-3xl font-bold text-blue-600 mt-2">
            {{ $detail->count() }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500 text-sm">
            Total Pendapatan
        </p>

        <h2 class="text-3xl font-bold text-green-600 mt-2">
            Rp {{ number_format($detail->sum('jumlah'),0,',','.') }}
        </h2>
    </div>

    <div class="bg-white rounded-2xl shadow p-6">
        <p class="text-gray-500 text-sm">
            Rata-rata Transaksi
        </p>

        <h2 class="text-3xl font-bold text-indigo-600 mt-2">
            Rp {{ number_format($detail->avg('jumlah') ?? 0,0,',','.') }}
        </h2>
    </div>

</div>

{{-- Detail Pembayaran --}}
<div class="bg-white rounded-3xl shadow overflow-hidden">

    <div class="p-5 border-b">
        <h2 class="text-xl font-bold">
            Daftar Transaksi
        </h2>
    </div>

    <div class="overflow-x-auto">

        <table class="min-w-full">

            <thead class="bg-emerald-600 text-white">

                <tr>
                    <th class="p-4 text-center">No</th>
                    <th class="p-4 text-left">Tanggal Bayar</th>
                    <th class="p-4 text-left">Nama Penyewa</th>
                    <th class="p-4 text-left">Username Penyewa</th>
                    <th class="p-4 text-left">Email</th>
                    <th class="p-4 text-left">Kamar</th>
                    <th class="p-4 text-center">Tanggal Mulai</th>
                    <th class="p-4 text-center">Tanggal Selesai</th>
                    <th class="p-4 text-center">Durasi</th>
                    <th class="p-4 text-left">Metode</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-right">Total</th>
                </tr>

            </thead>

            <tbody>

            @forelse($detail as $index => $item)

                <tr class="border-b hover:bg-gray-50">

                    <td class="p-4 text-center">
                        {{ $index + 1 }}
                    </td>

                    <td class="p-4">
                        {{ \Carbon\Carbon::parse($item->paid_at)->translatedFormat('d F Y H:i') }}
                    </td>

                    <td class="p-4">
                        {{ $item->booking?->user?->nama_lengkap ?? '-' }}
                    </td>

                    <td class="p-4">
                    {{ $item->booking?->user?->username ?? '-' }}
                    </td>

                    <td class="p-4">
                        {{ $item->booking?->user?->email ?? '-' }}
                    </td>

                    <td class="p-4">
                        {{ $item->booking?->kamar?->nama_kamar ?? '-' }}
                    </td>

                    <td class="p-4 text-center">
                        {{ \Carbon\Carbon::parse($item->booking?->tanggal_masuk)->translatedFormat('d F Y') }}
                    </td>

                    <td class="p-4 text-center">
                        {{ \Carbon\Carbon::parse($item->booking?->tanggal_selesai)->translatedFormat('d F Y') }}
                    </td>

                    <td class="p-4 text-center">
                        {{ $item->booking?->durasi ?? '-' }} Bulan
                    </td>

                    <td class="p-4">
                        {{ strtoupper($item->payment_type ?? '-') }}
                    </td>

                    <td class="p-4 text-center">

                        @if($item->transaction_status == 'settlement')

                            <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Settlement
                            </span>

                        @elseif($item->transaction_status == 'pending')

                            <span class="bg-yellow-100 text-yellow-700 px-3 py-1 rounded-full text-sm font-semibold">
                                Pending
                            </span>

                        @else

                            <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ ucfirst($item->transaction_status) }}
                            </span>

                        @endif

                    </td>

                    <td class="p-4 text-right font-bold text-green-600">
                        Rp {{ number_format($item->jumlah,0,',','.') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="8" class="p-8 text-center text-gray-500">
                        Belum ada transaksi pada bulan ini.
                    </td>

                </tr>

            @endforelse

            </tbody>

        </table>

    </div>

</div>

@endsection