@extends('layouts.adminlayouts')

@section('title', 'Dashboard')

@section('content')

    <div class="space-y-10">

        <!-- STATISTICS -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-gray-500 text-sm">Total User</p>
                <h2 class="text-2xl font-bold text-blue-600 mt-1">
                    {{ $totalUser ?? 0 }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-gray-500 text-sm">Total Kontrakan</p>
                <h2 class="text-2xl font-bold text-green-600 mt-1">
                    {{ $totalKamar ?? 0 }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-gray-500 text-sm">Kontrakan Terisi</p>
                <h2 class="text-2xl font-bold text-red-600 mt-1">
                    {{ $kamarTerisi ?? 0 }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
    <p class="text-gray-500 text-sm">
        Kontrakan Tersedia
    </p>

    <h2 class="text-2xl font-bold text-cyan-600 mt-1">
        {{ $kamarTersedia ?? 0 }}
    </h2>
</div>

<div class="bg-white rounded-2xl shadow-sm p-5">
    <p class="text-gray-500 text-sm">
        Kontrakan Dibooking
    </p>

    <h2 class="text-2xl font-bold text-yellow-600 mt-1">
        {{ $kamarBooking ?? 0 }}
    </h2>
</div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
    <p class="text-gray-500 text-sm">
        Total Pembayaran Berhasil
    </p>

    <h2 class="text-2xl font-bold text-purple-600 mt-1">
        {{ $totalTransaksi ?? 0 }}
    </h2>
</div>

            <div class="bg-white rounded-2xl shadow-sm p-5">
                <p class="text-gray-500 text-sm">Rata-rata Pembayaran / Bulan</p>
                <h2 class="text-2xl font-bold text-orange-600 mt-1">
                    {{ $rataTransaksi ?? 0 }}
                </h2>
            </div>

        </div>

        <!-- BOTTOM CARD -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-gray-500 text-sm">Total Pendapatan (Akumulasi)</p>
                <h2 class="text-3xl font-bold text-emerald-600 mt-2 break-words">
                    Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}
                </h2>
            </div>

            <div class="bg-white rounded-2xl shadow-sm p-6">
                <p class="text-gray-500 text-sm">Rata-rata Pendapatan / Bulan</p>
                <h2 class="text-3xl font-bold text-indigo-600 mt-2 break-words">
                    Rp {{ number_format($rataPendapatan ?? 0, 0, ',', '.') }}
                </h2>
            </div>

        </div>


        <div class="bg-white rounded-3xl shadow p-6">

    <h2 class="text-xl font-bold mb-4">
        Grafik Pendapatan Bulanan
    </h2>

    @if(count($labelGrafik ?? []) > 0)

        <div style="height:350px;">
            <canvas id="pendapatanChart"></canvas>
        </div>

    @else

        <div class="text-center text-gray-500 py-10">
            Belum ada data pendapatan
        </div>

    @endif

</div>


<!-- ================= NOTIF BOOKING ================= -->
<div class="bg-white rounded-3xl shadow p-6">

    <h2 class="text-xl font-bold mb-4">
        Notifikasi Booking Terbaru
    </h2>

    <div class="space-y-3">

        @forelse($bookingTerbaru as $booking)

            <div class="flex items-start gap-3 p-4 rounded-xl border 
                {{ $booking->status_pembayaran == 'dibayar' ? 'bg-green-50' : 'bg-yellow-50' }}">

                <div class="text-xl">
                    🔔
                </div>

                <div>
                    <p class="text-sm text-gray-800">
                        <span class="font-semibold">
                            {{ $booking->user?->nama_lengkap ?? 'User tidak ditemukan' }}
                        </span>

                        melakukan booking kamar

                        <span class="font-semibold text-amber-700">
                            {{ $booking->kamar?->nama_kamar ?? 'Kamar tidak ditemukan' }}
                        </span>
                    </p>

                    <p class="text-xs text-gray-500 mt-1">
                        {{ $booking->created_at->diffForHumans() }}
                    </p>
                </div>

            </div>

        @empty

            <div class="text-gray-500 text-sm">
                Belum ada booking terbaru
            </div>

        @endforelse

    </div>

</div>

        <!-- TABLE USER -->
        <div class="bg-white rounded-3xl shadow overflow-hidden">

            <div class="p-5 border-b">
                <h2 class="text-xl font-bold">User Terbaru</h2>
            </div>

            <table class="w-full">

                <thead class="bg-amber-600 text-white">
                    <tr>
                        <th class="p-4 text-left">Nama</th>
                        <th class="p-4 text-left">Username</th>
                        <th class="p-4 text-left">Email</th>
                        <th class="p-4 text-left">Role</th>
                        <th class="p-4 text-left">Tanggal registrasi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse($users ?? [] as $user)
                        <tr class="border-b">
                            <td class="p-4">{{ $user->nama_lengkap }}</td>
                            <td class="p-4">{{ $user->username }}</td>
                            <td class="p-4">{{ $user->email }}</td>
                            <td class="p-4">
                                @if($user->is_admin)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm">Admin</span>
                                @else
                                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">User</span>
                                @endif
                            </td>
                            <td class="p-4">
    {{ $user->created_at->format('d M Y H:i') }}
</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">
                                Belum ada user
                            </td>
                        </tr>
                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

document.addEventListener('DOMContentLoaded', function () {

    const ctx = document.getElementById('pendapatanChart');

    if (!ctx) return;

    new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($labelGrafik ?? []),
        datasets: [{
            label: 'Pendapatan (Rp)',
            data: @json($dataGrafik ?? []),
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,

        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return 'Rp ' +
                            context.raw.toLocaleString('id-ID');
                    }
                }
            }
        },

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

});

</script>

@endsection