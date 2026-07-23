<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\User;
use App\Models\Review;
use App\Models\ReviewReport;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon; // ✅ FIX (huruf besar)
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use App\Notifications\SystemNotification;

class AdminController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD ADMIN
    |--------------------------------------------------------------------------
    */
    public function index()
{
    // ======================
    // BASIC
    // ======================
    $totalUser = User::count();

    $kamars = Kamar::with([
        'bookings.payment'
    ])->get();

    $tanggal = Carbon::parse(
    request('tanggal', now()->toDateString())
);

    $totalKamar = $kamars->count();

    $today = Carbon::today();

    foreach ($kamars as $kamar) {

    $status = 'tersedia';
    $bookingAktif = null;

    foreach ($kamar->bookings as $booking) {

        if ($booking->status == 'cancel') {
            continue;
        }

        if (
            !$booking->payment ||
            $booking->payment->transaction_status != 'settlement'
        ) {
            continue;
        }

        $mulai = Carbon::parse($booking->tanggal_masuk);

        $selesai = Carbon::parse($booking->tanggal_selesai)
            ->addDays(2);

        if ($tanggal->between($mulai, $selesai->copy()->subDay())) {

            $status = $tanggal->lt($mulai)
                ? 'booking'
                : 'terisi';

            // <<< INI YANG KURANG
            $bookingAktif = $booking;

            break;
        }
    }

    $kamar->status_booking = $status;

    // <<< SIMPAN KE OBJECT
    $kamar->booking_aktif = $bookingAktif;
}

    $kamarTersedia = $kamars->where('status_booking', 'tersedia')->count();
    $kamarBooking  = $kamars->where('status_booking', 'booking')->count();
    $kamarTerisi   = $kamars->where('status_booking', 'terisi')->count();

    $users = User::latest()->take(10)->get();

    // ======================
    // TRANSAKSI
    // ======================
    $transaksiQuery = Payment::where('transaction_status', 'settlement');

    $totalTransaksi = (clone $transaksiQuery)->count();

    $totalPendapatan = (clone $transaksiQuery)->sum('jumlah');

    // ======================
    // RATA-RATA PENDAPATAN
    // ======================
    $pendapatanPerBulan = (clone $transaksiQuery)
        ->whereNotNull('paid_at')
        ->selectRaw('YEAR(paid_at) as tahun, MONTH(paid_at) as bulan, SUM(jumlah) as total')
        ->groupBy('tahun', 'bulan')
        ->pluck('total');

    $rataPendapatan = $pendapatanPerBulan->isNotEmpty()
        ? round($pendapatanPerBulan->avg())
        : 0;

    // ======================
    // RATA-RATA TRANSAKSI
    // ======================
    $transaksiPerBulan = (clone $transaksiQuery)
        ->whereNotNull('paid_at')
        ->selectRaw('YEAR(paid_at) as tahun, MONTH(paid_at) as bulan, COUNT(*) as total')
        ->groupBy('tahun', 'bulan')
        ->pluck('total');

    $rataTransaksi = $transaksiPerBulan->isNotEmpty()
        ? round($transaksiPerBulan->avg(), 1)
        : 0;

    // ======================
    // GRAFIK PENDAPATAN
    // ======================
    $grafikPendapatan = Payment::select(
            DB::raw('YEAR(paid_at) as tahun'),
            DB::raw('MONTH(paid_at) as bulan'),
            DB::raw('SUM(jumlah) as total')
        )
        ->where('transaction_status', 'settlement')
        ->whereNotNull('paid_at')
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun')
        ->orderBy('bulan')
        ->get();

    $labelGrafik = [];
    $dataGrafik = [];

    foreach ($grafikPendapatan as $item) {

        $labelGrafik[] = Carbon::create()
            ->month($item->bulan)
            ->translatedFormat('F') . ' ' . $item->tahun;

        $dataGrafik[] = $item->total;
    }

    // ======================
    // BOOKING TERBARU
    // ======================
    $bookingTerbaru = Booking::with([
            'user',
            'kamar'
        ])
        ->latest()
        ->take(6)
        ->get();

    return view('admin.adminpanel', compact(
        'totalUser',
        'totalKamar',
        'kamarTersedia',
        'kamarBooking',
        'kamarTerisi',
        'users',
        'totalTransaksi',
        'totalPendapatan',
        'rataPendapatan',
        'rataTransaksi',
        'labelGrafik',
        'dataGrafik',
        'bookingTerbaru'
    ));
}

    /*
    |--------------------------------------------------------------------------
    | USER MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function userIndex()
    {
        $users = User::latest()->get();
        return view('admin.user.adminuser', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);

        if ($user->is_admin) {
            return back()->with('error', 'Akun admin tidak dapat dihapus.');
        }

        $user->delete();


        $user->notify(new SystemNotification(
    'Akun Dihapus',
    'Akun Anda telah dihapus oleh admin'
));

        return back()->with('success', 'Akun user berhasil dihapus');
    }

    /*
    |--------------------------------------------------------------------------
    | BOOKING MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function bookingIndex()
{
    $bookings = Booking::with([
        'user',
        'kamar',
        'payment'
    ])
    ->latest()
    ->get();

    return view('admin.booking.adminbooking', compact('bookings'));
}

    public function approveBooking($id)
    {
        $booking = Booking::with(['user', 'kamar'])->findOrFail($id);

        $booking->update([
    'status_pembayaran' => 'dibayar'
]);

$booking->payment()->update([
    'transaction_status' => 'settlement',
    'paid_at' => now()
]);
        // notif ke admin
$admins = User::where('is_admin', 1)->get();

foreach ($admins as $admin) {
    $admin->notify(new SystemNotification(
        'Booking Dibayar',
        'User ' . $booking->user->nama_lengkap .
        ' membayar kamar ' . $booking->kamar->nama_kamar
    ));
}


        // FORMAT NOMOR WA
        $nomor = preg_replace('/[^0-9]/', '', $booking->whatsapp);

        if (substr($nomor, 0, 1) == '0') {
            $nomor = '62' . substr($nomor, 1);
        }

        // PESAN WA
        $pesan = urlencode(
            "Halo {$booking->user->nama_lengkap}, "
            . "booking kamar '{$booking->kamar->nama_kamar}' telah DISETUJUI. "
            . "Silakan datang sesuai jadwal. Terima kasih."
        );

        $waLink = "https://wa.me/{$nomor}?text={$pesan}";

        return back()
            ->with('success', 'Booking berhasil diapprove')
            ->with('wa_link', $waLink);
    }

public function rejectBooking($id)
{
    $booking = Booking::with(['user', 'kamar'])->findOrFail($id);

   $booking->update([
    'status_pembayaran'=>'ditolak'
]);

if ($booking->payment) {

    $booking->payment->update([
        'transaction_status'=>'deny'
    ]);

}

    return back()->with('success', 'Pembayaran ditolak');
}

    public function deleteBooking($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->bukti_pembayaran) {
            Storage::disk('public')->delete($booking->bukti_pembayaran);
        }

        $booking->delete();

        return back()->with('success', 'Booking dihapus');
    }

 public function bookingDetail($id)
{
    $booking = Booking::with(['user', 'kamar'])->findOrFail($id);

    return view(
        'admin.booking.adminbookingdetail',
        compact('booking')
    );
}

    /*
    |--------------------------------------------------------------------------
    | KAMAR MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function kamarIndex(Request $request)
{
    $tanggal = Carbon::parse(
        $request->tanggal ?? now()->toDateString()
    );

    $kamars = Kamar::with([
        'bookings.payment',
        'bookings.user'
    ])
    ->orderBy('nama_kamar', 'asc')
    ->get();

   foreach ($kamars as $kamar) {

    $status = 'tersedia';
    $bookingAktif = null;

    foreach ($kamar->bookings as $booking) {

        if ($booking->status == 'cancel') {
            continue;
        }

        if (
            !$booking->payment ||
            $booking->payment->transaction_status != 'settlement'
        ) {
            continue;
        }

        $mulai = Carbon::parse($booking->tanggal_masuk);
        $selesai = Carbon::parse($booking->tanggal_selesai)->addDays(2);

        // =============================
        // Sedang ditempati
        // =============================
        if ($tanggal->between($mulai, $selesai->copy()->subDay())) {

            $status = 'terisi';
            $bookingAktif = $booking;
            break;
        }

        // =============================
        // Akan dibooking (< 1 bulan)
        // =============================
        $selisihHari = $tanggal->diffInDays($mulai, false);

        if ($selisihHari > 0 && $selisihHari <= 30) {

            $status = 'booking';
            $bookingAktif = $booking;
            break;
        }
    }

    $kamar->status_booking = $status;
    $kamar->booking_aktif = $bookingAktif;
}

    return view('admin.kamar.index', compact(
        'kamars',
        'tanggal'
    ));
}

    public function kamarCreate()
    {
        return view('admin.kamar.create');
    }

    public function kamarStore(Request $request)
    {
        $request->validate([
            'nama_kamar' => 'required',
            'harga' => 'required|numeric',
            'foto_kamar.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'foto_kamar' => $fotoPaths,
        ]);

        $admins = User::where('is_admin', 1)->get();

foreach ($admins as $admin) {
    $admin->notify(new SystemNotification(
        'Kamar Baru Ditambahkan',
        'Kamar ' . $request->nama_kamar . ' berhasil dibuat'
    ));
}

        return redirect()->route('admin.kamar.index')
            ->with('success', 'Kamar berhasil ditambahkan');
    }

    public function kamarEdit(Kamar $kamar)
    {
        return view('admin.kamar.edit', compact('kamar'));
    }

    public function kamarUpdate(Request $request, Kamar $kamar)
{
    $request->validate([
        'nama_kamar' => 'required',
        'harga' => 'required|numeric',
        'foto_kamar' => 'nullable|array|max:20',
        'foto_kamar.*' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $fotoLama = is_array($kamar->foto_kamar)
        ? $kamar->foto_kamar
        : [];

    /*
    |--------------------------------------------------------------------------
    | HAPUS FOTO YANG DICENTANG
    |--------------------------------------------------------------------------
    */
    if ($request->filled('hapus_foto')) {

        foreach ($request->hapus_foto as $fotoHapus) {

            Storage::disk('public')->delete($fotoHapus);

            $fotoLama = array_filter(
                $fotoLama,
                fn($item) => $item != $fotoHapus
            );
        }
    }
    /*
    |--------------------------------------------------------------------------
    | TAMBAH FOTO BARU
    |--------------------------------------------------------------------------
    */
    if ($request->hasFile('foto_kamar')) {

    foreach ($request->file('foto_kamar') as $foto) {

        $fotoLama[] = $foto->store('kamar','public');

    }

}

        if ($request->filled('urutan_foto')) {

    $urutan = json_decode($request->urutan_foto, true);

    $fotoUrut = [];

    foreach ($urutan as $path) {
        if (in_array($path, $fotoLama)) {
            $fotoUrut[] = $path;
        }
    }

    // tambahkan foto baru yang belum ada di urutan
    foreach ($fotoLama as $path) {
        if (!in_array($path, $fotoUrut)) {
            $fotoUrut[] = $path;
        }
    }

    $fotoLama = $fotoUrut;
}

    $kamar->update([
    'nama_kamar'=>$request->nama_kamar,
    'deskripsi'=>$request->deskripsi,
    'harga'=>$request->harga,
    'foto_kamar'=>array_values($fotoLama),
]);

    return redirect()
        ->route('admin.kamar.index')
        ->with('success', 'Kamar berhasil diupdate');
}

    public function kamarDestroy(Kamar $kamar)
    {
        if (is_array($kamar->foto_kamar)) {
            foreach ($kamar->foto_kamar as $foto) {
                Storage::disk('public')->delete($foto);
            }
        }

        $kamar->delete();

        return back()->with('success', 'Kamar berhasil dihapus');
    }
    /*
    |--------------------------------------------------------------------------
    | REVIEW MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function reviewIndex()
    {
        $reviews = Review::with(['user', 'kamar'])
            ->withCount('reports')
            ->latest()
            ->get();

        return view('admin.review.adminreview', compact('reviews'));
    }

    public function reviewDelete($id)
    {
        $review = Review::findOrFail($id);

        ReviewReport::where('review_id', $review->id)->delete();
        $review->delete();

        return back()->with('success', 'Review berhasil dihapus');
    }

    public function reviewIgnore($id)
{
    $review = Review::findOrFail($id);

    // Hapus seluruh laporan terhadap review
    ReviewReport::where('review_id', $review->id)->delete();

    return back()->with(
        'success',
        'Laporan review berhasil diabaikan.'
    );
}

    /*
    |--------------------------------------------------------------------------
    | CHAT MANAGEMENT
    |--------------------------------------------------------------------------
    */
    public function chatIndex()
    {
        $conversations = Conversation::with(['user', 'messages'])
            ->latest()
            ->get();

        return view('admin.chat.whatsapp', compact('conversations'));
    }

    public function chatOpen(Conversation $conversation)
    {
        $messages = $conversation->messages()
            ->with('sender')
            ->orderBy('created_at')
            ->get();

        return response()->json([
            'user' => [
                'nama' => $conversation->user->nama_lengkap
            ],
            'messages' => $messages->map(function ($msg) {
                return [
                    'sender_id' => $msg->sender_id,
                    'message' => $msg->message,
                    'image' => $msg->image,
                    'time' => $msg->created_at->format('H:i'),
                    'is_admin' => $msg->sender_id == auth()->id()
                ];
            })
        ]);
    }

    public function chatSend(Request $request, Conversation $conversation)
    {
        $request->validate([
            'message' => 'nullable|string',
            'image' => 'nullable|image|max:2048'
        ]);

        if (!$request->message && !$request->hasFile('image')) {
            return response()->json([
                'status' => false,
                'message' => 'Pesan kosong'
            ]);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('chat', 'public');
        }

        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => auth()->id(),
            'message' => $request->message ?? '',
            'image' => $imagePath,
        ]);

        $conversation->user->notify(
    new SystemNotification(
        'Balasan Chat',
        'Admin membalas pesan chat Anda.'
    )
);

        return response()->json([
            'status' => true,
            'data' => [
                'message' => $message->message,
                'image' => $message->image,
                'created_at' => $message->created_at->format('H:i')
            ]
        ]);
    }

    
public function pembayaranIndex()
{
    $totalPendapatan = Payment::where(
    'transaction_status',
    'settlement'
)->sum('jumlah');

    $totalTransaksi = Payment::where(
    'transaction_status',
    'settlement'
)->count();

    $laporanBulanan = Payment::select(
            DB::raw('YEAR(paid_at) as tahun'),
            DB::raw('MONTH(paid_at) as bulan'),
            DB::raw('COUNT(*) as jumlah_transaksi'),
            DB::raw('SUM(jumlah) as total_pendapatan')
        )
        ->where('transaction_status', 'settlement')
        ->whereNotNull('paid_at')
        ->groupBy('tahun', 'bulan')
        ->orderBy('tahun', 'desc')
        ->orderBy('bulan', 'desc')
        ->get();


    $detailPembayaran = Payment::with([
    'booking.user',
    'booking.kamar'
])
->where('transaction_status','settlement')
->orderBy('paid_at','desc')
->get();

$pendapatanPerBulan = Payment::whereIn('transaction_status', ['settlement', 'capture'])
    ->whereNotNull('paid_at')
    ->selectRaw('YEAR(paid_at) as tahun, MONTH(paid_at) as bulan, SUM(jumlah) as total')
    ->groupBy('tahun', 'bulan')
    ->pluck('total');

$rataPendapatan = $pendapatanPerBulan->isNotEmpty()
    ? round($pendapatanPerBulan->avg())
    : 0;

    return view('admin.kelolapembayaran', compact(
        'totalPendapatan',
        'totalTransaksi',
        'rataPendapatan',
        'laporanBulanan',
        'detailPembayaran'
    ));
}

public function pembayaranDetail($tahun, $bulan)
{
    $detail = Payment::with([
        'booking.user',
        'booking.kamar'
    ])
    ->where('status', 'success')
    ->whereYear('paid_at', $tahun)
    ->whereMonth('paid_at', $bulan)
    ->orderBy('paid_at', 'desc')
    ->get();

    // Nama bulan (Januari, Februari, dst.)
    $namaBulan = \Carbon\Carbon::createFromDate($tahun, (int) $bulan, 1)
        ->translatedFormat('F');

    return view('admin.pembayarandetail', [
        'detail' => $detail,
        'tahun' => $tahun,
        'bulan' => $bulan,
        'namaBulan' => $namaBulan,
    ]);
}

public function notificationIndex()
{
    $admin = auth()->user();

    $notifications = $admin->notifications()
        ->latest()
        ->get();

    // mark all as read (simple & clean)
    $admin->unreadNotifications()
        ->update(['read_at' => now()]);

    return view('admin.notifications.index', compact('notifications'));
}

}