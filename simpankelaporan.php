<?php

// ==================================================
// 1. LOGIN
// ==================================================

<form method="POST" action="{{ route('login') }}">
    @csrf
    <input type="email" name="email" value="{{ old('email') }}" required >
    <input type="password" name="password" required>
    <button type="submit"> Login </button>
</form>


$request->authenticate();
$request->session()->regenerate();

if (Auth::user()->is_admin == 1) {
    return redirect()->route('admin.panel');
}

return redirect()->route('dashboard');


// ==================================================
// 2. BOOKING
// ==================================================

<form id="bookingForm" action="{{ route('booking.store', $kamar->id) }}" method="POST" enctype="multipart/form-data">
    @csrf

    <input type="hidden" name="tanggal_masuk" value="{{ $tanggalMasuk }}">
    <input type="number" name="durasi" id="durasi" min="1" max="24" value="{{ old('durasi', 1) }}" required>
    <input type="file" name="foto_identitas" accept=".jpg,.jpeg,.png" required>
    <button type="submit">Booking Sekarang </button>
</form>

public function store(Request $request, $id)
{
    $kamar = Kamar::findOrFail($id);

    // Validasi data booking
    $request->validate([
        'tanggal_masuk' => 'required|date|after_or_equal:today',
        'durasi' => 'required|integer|min:1|max:24',
        'foto_identitas' => 'required|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    // Menentukan tanggal selesai berdasarkan durasi sewa
    $tanggalMasuk = Carbon::parse($request->tanggal_masuk);
    $tanggalSelesai = $tanggalMasuk
        ->copy()
        ->addMonthsNoOverflow($request->durasi);

    // Menyimpan data booking
    $booking = Booking::create([
        'user_id' => auth()->id(),
        'kamar_id' => $kamar->id,
        'tanggal_masuk' => $tanggalMasuk,
        'tanggal_selesai' => $tanggalSelesai,
        'durasi' => $request->durasi,
        'total_harga' => $kamar->harga * $request->durasi,
        'status' => 'pending',
    ]);

    return redirect()->route('payment.create', $booking->id);
}

// ==================================================
// 3. Proses pembayaran midtrans
// ==================================================

<button id="pay-button"
    class="w-full bg-amber-700 text-white py-4 rounded-2xl">
    Bayar Sekarang
</button>

<script
    src="https://app.sandbox.midtrans.com/snap/snap.js"
    data-client-key="{{ config('midtrans.client_key') }}">
</script>

<script>
const snapToken = "{{ $payment->snap_token }}";
document.getElementById('pay-button').addEventListener('click', function () {

    snap.pay(snapToken, {
        onSuccess: function () {
            window.location.href = "{{ route('booking.riwayat') }}";
        },
        onPending: function () {
            window.location.href =
                "{{ route('booking.riwayat') }}?pending=1";
        },
        onError: function () {
            window.location.href =
                "{{ route('booking.riwayat') }}?error=1";
        }
    });
});
</script>


public function create($id)
{
    $booking = Booking::with('kamar')->findOrFail($id);

    // Konfigurasi Midtrans
    Config::$serverKey = config('midtrans.server_key');

    // Membuat order dan Snap Token
    $orderId = 'PAY-' . $booking->id . '-' . time();
    $snapToken = Snap::getSnapToken([
        'transaction_details' => ['order_id' => $orderId, 'gross_amount' => $booking->total_harga,],
        'customer_details' => ['first_name' => auth()->user()->nama_lengkap, 'phone' => $booking->whatsapp,],
    ]);

    // Menyimpan data pembayaran
    $payment = Payment::create([
        'booking_id' => $booking->id,
        'order_id' => $orderId,
        'snap_token' => $snapToken,
        'jumlah' => $booking->total_harga,
        'status' => 'pending',
    ]);

    return view('booking.midtrans', compact('booking', 'payment'));
}



$periodeTerakhir = $booking->payments()
    ->max('periode_ke');
$periodeKe = ($periodeTerakhir ?? 0) + 1;
$tanggalMulai = Carbon::parse($booking->tanggal_masuk)
    ->addMonthsNoOverflow($periodeKe - 1);

$tanggalSelesai = Carbon::parse($booking->tanggal_masuk)
    ->addMonthsNoOverflow($periodeKe);

$jumlah = $booking->kamar->harga;

$orderId = 'PAY-' .$booking->id .'-P' .$periodeKe .'-' .time();

$snapToken = Snap::getSnapToken([
    'transaction_details' => [
        'order_id' => $orderId,
        'gross_amount' => $jumlah
    ]
]);

$payment = Payment::create([
    'booking_id' => $booking->id,
    'periode_ke' => $periodeKe,
    'tanggal_periode_mulai' => $tanggalMulai,
    'tanggal_periode_selesai' => $tanggalSelesai,
    'order_id' => $orderId,
    'snap_token' => $snapToken,
    'jumlah' => $jumlah,
    'status' => 'pending',
]);


// ==================================================
// 4. CALLBACK PEMBAYARAN
// ==================================================

public function callback()
{
    Config::$serverKey = config('midtrans.server_key');
    $notif = new \Midtrans\Notification();
    $payment = Payment::where(
        'order_id',
        $notif->order_id
    )->first();

    if (!$payment) {
        return response()->json([
            'message' => 'Payment tidak ditemukan'
        ], 404);
    }

    $status = $notif->transaction_status;
    if (in_array($status, ['capture', 'settlement'])) {

        $payment->update([
            'status' => 'success',
            'transaction_status' => $status,
            'payment_type' => $notif->payment_type,
            'paid_at' => now()
        ]);

        $payment->booking->update(['status' => 'paid' ]);
        $payment->booking->kamar->update(['status' => 'terisi']);

    } elseif (in_array($status, ['expire', 'cancel', 'deny'])) {
        $payment->update(['status' => 'failed', 'transaction_status' => $status]);
        $payment->booking->update(['status' => 'cancel']);
    }

    return response()->json(['message' => 'OK']);
}




// ==================================================
// 5. DASHBOARD ADMIN
// ==================================================

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p>Total User</p>
        <h2>{{ $totalUser }}</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p>Total Kontrakan</p>
        <h2>{{ $totalKamar }}</h2>
    </div>

    <div class="bg-white rounded-2xl shadow-sm p-5">
        <p>Total Pembayaran Berhasil</p>
        <h2>{{ $totalTransaksi }}</h2>
    </div>

</div>

<!-- GRAFIK PENDAPATAN -->
<canvas id="pendapatanChart"></canvas>

<script>
new Chart(document.getElementById('pendapatanChart'), {
    type: 'bar',
    data: {
        labels: @json($labelGrafik),
        datasets: [{
            label: 'Pendapatan',
            data: @json($dataGrafik)
        }]
    }
});
</script>


public function index()
{
    $totalUser = User::count();
    $totalKamar = Kamar::count();

    $totalTransaksi = Payment::where(
        'transaction_status', 'settlement'
    )->count();

    $totalPendapatan = Payment::where(
        'transaction_status', 'settlement'
    )->sum('jumlah');

    $grafikPendapatan = Payment::select(
        DB::raw('YEAR(paid_at) as tahun'),
        DB::raw('MONTH(paid_at) as bulan'),
        DB::raw('SUM(jumlah) as total')
    )
    ->where('transaction_status', 'settlement')
    ->groupBy('tahun', 'bulan')
    ->get();

    foreach ($grafikPendapatan as $item) {
        $labelGrafik[] = Carbon::create()
            ->month($item->bulan)
            ->translatedFormat('F');

        $dataGrafik[] = $item->total;
    }

    return view('admin.adminpanel', compact('totalUser','totalKamar','totalTransaksi','totalPendapatan','labelGrafik','dataGrafik'
    ));
}


// ==================================================
// menampilkan data kontrakan
// ==================================================

@forelse($kamars as $kamar)

<tr>
    <td> {{ $kamar->nama_kamar }} </td>
    <td> Rp {{ number_format($kamar->harga, 0, ',', '.') }} </td>
    <td>
        @if($kamar->status == 'terisi')
            <span>Terisi</span>
        @else
            <span>Tersedia</span>
        @endif
    </td>

    <td>
        <a href="{{ route('admin.kamar.edit', $kamar->id) }}"> Edit </a>
    </td>
</tr>

@empty

<tr>
    <td colspan="4"> Belum ada data kontrakan. </td>
</tr>

@endforelse


// ==================================================
// menambah data kontrakan
// ==================================================
<form action="{{ route('admin.kamar.store') }}" method="POST" enctype="multipart/form-data">

    @csrf

    <input type="text" name="nama_kamar" placeholder="Nama / Nomor Kontrakan" required>
    <textarea name="deskripsi" placeholder="Deskripsi"></textarea>
    <input type="number" name="harga" placeholder="Harga" required>
    <input type="file" name="foto_kamar[]" multiple>
    <button type="submit"> Simpan Kontrakan </button>

</form>

// Menyimpan data kontrakan
$request->validate([
    'nama_kamar' => 'required',
    'harga' => 'required|numeric',
    'foto_kamar.*' => 'nullable|image'
]);

Kamar::create([
    'nama_kamar' => $request->nama_kamar,
    'deskripsi' => $request->deskripsi,
    'harga' => $request->harga,
    'foto_kamar' => $fotoPaths,
]);


//mengubah data kontrakan
<form action="{{ route('admin.kamar.update', $kamar->id) }}" method="POST"enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <input type="text"
           name="nama_kamar" value="{{ old('nama_kamar', $kamar->nama_kamar) }}" required>
    <textarea name="deskripsi">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
    <input type="number" name="harga" value="{{ old('harga', $kamar->harga) }}" required>
    <input type="file" name="foto_kamar[]"multiple>

    @foreach($kamar->foto_kamar as $img)

        <img src="{{ asset('storage/' . $img) }}">

        <label>
            <input type="checkbox" name="hapus_foto[]" value="{{ $img }}">Hapus Foto
        </label>

    @endforeach

    <button type="submit"> Update Kontrakan </button>

</form>

// Memperbarui data kontrakan
$request->validate([
    'nama_kamar' => 'required',
    'harga' => 'required|numeric',
]);

$kamar->update([
    'nama_kamar' => $request->nama_kamar,
    'deskripsi' => $request->deskripsi,
    'harga' => $request->harga,
    'foto_kamar' => array_values($fotoLama),
]);

// ==================================================
// 7. KELOLA BOOKING
// ==================================================

$bookings = Booking::with(['user', 'kamar'])
    ->latest()
    ->get();

return view(
    'admin.booking.adminbooking',
    compact('bookings')
);


<form action="{{ route('admin.kamar.store') }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf

    <input type="text" name="nama_kamar" required>
    <textarea name="deskripsi"></textarea>
    <input type="number" name="harga" min="0" required>
    <input type="file" name="foto_kamar[]" multiple
        accept="image/jpeg,image/png,image/jpg,image/webp"
    >

    <button type="submit"> Simpan Kontrakan </button>

</form>



<form action="{{ route('admin.kamar.update', $kamar->id) }}"
      method="POST"
      enctype="multipart/form-data">

    @csrf
    @method('PUT')

    <input type="text" name="nama_kamar" value="{{ old('nama_kamar', $kamar->nama_kamar) }}" required>
    <textarea name="deskripsi">{{ old('deskripsi', $kamar->deskripsi) }}</textarea>
    <input type="number" name="harga" value="{{ old('harga', $kamar->harga) }}" min="0" required>
    <input type="file" name="foto_kamar[]" multiple accept="image/jpeg,image/png,image/jpg,image/webp">

    @foreach($foto as $img)
        <img src="{{ asset('storage/' . $img) }}">
        <input type="checkbox" name="hapus_foto[]" value="{{ $img }}">
    @endforeach

    <input type="hidden" name="urutan_foto" id="urutan_foto">

    <button type="submit">Update Kontrakan</button>

</form>




public function store(Request $request)
{
    $request->validate([
        'nama_kamar' => 'required',
        'harga' => 'required|numeric',
        'foto_kamar.*' => 'nullable|image'
    ]);

    $fotoPaths = [];

    if ($request->hasFile('foto_kamar')) {
        foreach ($request->file('foto_kamar') as $foto) {
            $fotoPaths[] = $foto->store('kamar', 'public');
        }
    }

    Kamar::create([
        'nama_kamar' => $request->nama_kamar,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'status' => 'kosong',
        'foto_kamar' => $fotoPaths,
    ]);
}

public function update(Request $request, Kamar $kamar)
{
    $request->validate([
        'nama_kamar' => 'required',
        'harga' => 'required|numeric',
        'foto_kamar.*' => 'nullable|image'
    ]);

    $fotoLama = $kamar->foto_kamar ?? [];

    // Hapus foto yang dipilih
    if ($request->hapus_foto) {
        $fotoLama = array_diff(
            $fotoLama,
            $request->hapus_foto
        );
    }

    // Tambahkan foto baru
    if ($request->hasFile('foto_kamar')) {
        foreach ($request->file('foto_kamar') as $foto) {
            $fotoLama[] = $foto->store('kamar', 'public');
        }
    }

    // Atur kembali urutan foto
    if ($request->urutan_foto) {
        $urutan = json_decode(
            $request->urutan_foto,
            true
        );

        if (is_array($urutan)) {
            $fotoLama = array_values(
                array_intersect($urutan, $fotoLama)
            );
        }
    }

    $kamar->update([
        'nama_kamar' => $request->nama_kamar,
        'deskripsi' => $request->deskripsi,
        'harga' => $request->harga,
        'foto_kamar' => array_values($fotoLama),
    ]);
}




@forelse($bookings as $booking)

<tr>
    <td>{{ $booking->id }}</td>
    <td> {{ $booking->user->nama_lengkap ?? '-' }}</td>
    <td> {{ $booking->kamar->nama_kamar ?? '-' }} </td>
    <td> {{ $booking->durasi }} bulan </td>
    <td> Rp {{ number_format($booking->payments
                ->where('status', 'success')
                ->sum('jumlah'), 0, ',', '.'
        ) }}
    </td>

    <td>
        @if($booking->status === 'paid')
            Aktif
        @elseif($booking->status === 'cancel')
            Dibatalkan
        @else
            Menunggu Pembayaran
        @endif
    </td>

    <td>
        <a href="{{ route('admin.booking.detail',$booking->id) }}">Detail</a>
    </td>
</tr>

@empty

<tr>
    <td colspan="7">Belum ada data booking</td>
</tr>

@endforelse


public function bookingIndex()
{
    $bookings = Booking::with([
        'user',
        'kamar',
        'payments'
    ])
    ->latest()
    ->get();

    return view(
        'admin.booking.adminbooking',
        compact('bookings')
    );
}