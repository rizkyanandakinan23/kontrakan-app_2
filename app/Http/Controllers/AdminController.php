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

private function tentukanStatusKamar($kamar, Carbon $tanggal)
{
    $status = 'tersedia';
    $bookingAktif = null;
    $bookingTerdekat = null;

    foreach ($kamar->bookings as $booking) {

        // ==========================================
        // SKIP BOOKING YANG DIBATALKAN
        // ==========================================

        if ($booking->status === 'cancel') {
            continue;
        }

        // ==========================================
        // TANGGAL BOOKING
        // ==========================================

        $mulai = Carbon::parse($booking->tanggal_masuk)->startOfDay();
        $selesai = Carbon::parse($booking->tanggal_selesai)->startOfDay();

        // ==========================================
        // PRIORITAS 1
        // SEDANG DALAM MASA SEWA
        // ==========================================

        if (
            $tanggal->greaterThanOrEqualTo($mulai) &&
            $tanggal->lessThan($selesai)
        ) {

            $status = 'terisi';
            $bookingAktif = $booking;

            break;
        }

        // ==========================================
        // PRIORITAS 2
        // AKAN MASUK DALAM 30 HARI
        // ==========================================

        if ($tanggal->lt($mulai)) {

            $selisihHari = $tanggal->diffInDays(
                $mulai,
                false
            );

            if (
                $selisihHari > 0 &&
                $selisihHari <= 30
            ) {

                if (
                    $bookingTerdekat === null ||
                    $mulai->lessThan(
                        Carbon::parse(
                            $bookingTerdekat->tanggal_masuk
                        )
                    )
                ) {

                    $bookingTerdekat = $booking;
                }
            }
        }
    }

    // ==========================================
    // JIKA ADA BOOKING TERDEKAT
    // ==========================================

    if (
        $status !== 'terisi' &&
        $bookingTerdekat !== null
    ) {

        $status = 'booking';
        $bookingAktif = $bookingTerdekat;
    }

    // ==========================================
    // RETURN
    // ==========================================

    return [
        'status' => $status,
        'booking' => $bookingAktif
    ];
}

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

    $tanggal = Carbon::parse(
        request('tanggal', now()->toDateString())
    );

    $kamars = Kamar::with([
        'bookings.payments',
        'bookings.user'
    ])->get();

    $totalKamar = $kamars->count();

    // ======================
    // STATUS KAMAR
    // ======================

    foreach ($kamars as $kamar) {

        $hasil = $this->tentukanStatusKamar(
            $kamar,
            $tanggal
        );

        $kamar->status_booking = $hasil['status'];
        $kamar->booking_aktif = $hasil['booking'];
    }

    $kamarTersedia = $kamars
        ->where('status_booking', 'tersedia')
        ->count();

    $kamarBooking = $kamars
        ->where('status_booking', 'booking')
        ->count();

    $kamarTerisi = $kamars
        ->where('status_booking', 'terisi')
        ->count();

    // ======================
    // USER TERBARU
    // ======================

    $users = User::latest()
        ->take(10)
        ->get();

    // ======================
    // TRANSAKSI
    // ======================

    $transaksiQuery = Payment::where(
        'transaction_status',
        'settlement'
    );

    $totalTransaksi = (clone $transaksiQuery)->count();

    $totalPendapatan = (clone $transaksiQuery)->sum('jumlah');

    // ======================
    // RATA-RATA PENDAPATAN
    // ======================

    $pendapatanPerBulan = (clone $transaksiQuery)
        ->whereNotNull('paid_at')
        ->selectRaw(
            'YEAR(paid_at) as tahun,
             MONTH(paid_at) as bulan,
             SUM(jumlah) as total'
        )
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
        ->selectRaw(
            'YEAR(paid_at) as tahun,
             MONTH(paid_at) as bulan,
             COUNT(*) as total'
        )
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
            ->translatedFormat('F')
            . ' '
            . $item->tahun;

        $dataGrafik[] = $item->total;
    }

    // ======================
    // BOOKING TERBARU
    // ======================

    $bookingTerbaru = Booking::with([
        'user',
        'kamar',
        'payments'
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
        'payments'
    ])
        ->latest()
        ->get();

    return view(
        'admin.booking.adminbooking',
        compact('bookings')
    );
}

    public function deleteBooking($id)
{
    $booking = Booking::findOrFail($id);

    // Tandai booking sebagai dibatalkan / diakhiri
    $booking->update([
        'status' => 'cancel',
    ]);

    return back()->with(
        'success',
        'Booking berhasil diakhiri. Kontrakan tidak lagi terikat pada booking ini.'
    );
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
    )->startOfDay();

    $kamars = Kamar::with([
        'bookings.user',
        'bookings.payments'
    ])
    ->orderBy('nama_kamar', 'asc')
    ->get();

    foreach ($kamars as $kamar) {

        // ==========================================
        // DEFAULT
        // ==========================================

        $status = 'tersedia';
        $bookingAktif = null;
        $bookingTerdekat = null;


        // ==========================================
        // CEK SEMUA BOOKING
        // ==========================================

        foreach ($kamar->bookings as $booking) {

            // ==========================================
            // SKIP BOOKING YANG DIBATALKAN
            // ==========================================

            if ($booking->status === 'cancel') {
                continue;
            }


            // ==========================================
            // TANGGAL BOOKING
            // ==========================================

           $mulai = Carbon::parse(
                $booking->tanggal_masuk
            )->startOfDay();

            $selesai = Carbon::parse(
                $booking->tanggal_selesai
            )->startOfDay();

            $batasTerisi = $selesai->copy()->addDays(2);

            if (
                $tanggal->greaterThanOrEqualTo($mulai) &&
                $tanggal->lessThan($batasTerisi)
            ) {
                $status = 'terisi';
                $bookingAktif = $booking;
                break;
            }


            // ==========================================
            // PRIORITAS 2
            // AKAN DIGUNAKAN DALAM 30 HARI
            // ==========================================

            if ($tanggal->lt($mulai)) {

                $selisihHari = $tanggal->diffInDays(
                    $mulai,
                    false
                );

                if (
                    $selisihHari > 0 &&
                    $selisihHari <= 30
                ) {

                    if (
                        $bookingTerdekat === null ||
                        $mulai->lessThan(
                            Carbon::parse(
                                $bookingTerdekat->tanggal_masuk
                            )
                        )
                    ) {

                        $bookingTerdekat = $booking;
                    }
                }
            }
        }


        // ==========================================
        // BOOKING TERDEKAT
        // ==========================================

        if (
            $status !== 'terisi' &&
            $bookingTerdekat !== null
        ) {

            $status = 'booking';
            $bookingAktif = $bookingTerdekat;
        }


        // ==========================================
        // SIMPAN HASIL
        // ==========================================

        $kamar->status_booking = $status;
        $kamar->booking_aktif = $bookingAktif;
    }


    // ==========================================
    // RETURN VIEW
    // ==========================================

    return view(
        'admin.kamar.index',
        compact(
            'kamars',
            'tanggal'
        )
    );
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
    // Simpan nama kamar sebelum data dihapus
    $namaKamar = $kamar->nama_kamar;

    // Hapus semua foto kamar
    if (is_array($kamar->foto_kamar)) {
        foreach ($kamar->foto_kamar as $foto) {
            Storage::disk('public')->delete($foto);
        }
    }

    // Hapus data kamar
    $kamar->delete();

    return back()->with(
        'success',
        'Kamar berhasil dihapus'
    );
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
    // ==========================
    // TOTAL PENDAPATAN
    // ==========================

    $totalPendapatan = Payment::where(
        'transaction_status',
        'settlement'
    )->sum('jumlah');

    // ==========================
    // TOTAL TRANSAKSI
    // ==========================

    $totalTransaksi = Payment::where(
        'transaction_status',
        'settlement'
    )->count();

    // ==========================
    // LAPORAN BULANAN
    // ==========================

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

    // ==========================
    // DETAIL PEMBAYARAN
    // ==========================

    $detailPembayaran = Payment::with([
        'booking.user',
        'booking.kamar'
    ])
        ->where('transaction_status', 'settlement')
        ->orderBy('paid_at', 'desc')
        ->get();

    // ==========================
    // RATA-RATA PENDAPATAN
    // ==========================

    $pendapatanPerBulan = Payment::where(
        'transaction_status',
        'settlement'
    )
        ->whereNotNull('paid_at')
        ->selectRaw(
            'YEAR(paid_at) as tahun,
             MONTH(paid_at) as bulan,
             SUM(jumlah) as total'
        )
        ->groupBy('tahun', 'bulan')
        ->pluck('total');

    $rataPendapatan = $pendapatanPerBulan->isNotEmpty()
        ? round($pendapatanPerBulan->avg())
        : 0;

    return view(
        'admin.kelolapembayaran',
        compact(
            'totalPendapatan',
            'totalTransaksi',
            'rataPendapatan',
            'laporanBulanan',
            'detailPembayaran'
        )
    );
}

public function pembayaranDetail($tahun, $bulan)
{
    $detail = Payment::with([
        'booking.user',
        'booking.kamar'
    ])
        ->where('transaction_status', 'settlement')
        ->whereNotNull('paid_at')
        ->whereYear('paid_at', $tahun)
        ->whereMonth('paid_at', $bulan)
        ->orderBy('paid_at', 'desc')
        ->get();

    $namaBulan = Carbon::createFromDate(
        $tahun,
        (int) $bulan,
        1
    )->translatedFormat('F');

    return view(
        'admin.pembayarandetail',
        [
            'detail' => $detail,
            'tahun' => $tahun,
            'bulan' => $bulan,
            'namaBulan' => $namaBulan,
        ]
    );
}

}