<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Booking;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{

    public function index(Request $request, $id)
{
    $kamar = Kamar::findOrFail($id);

    // ==========================================
    // TANGGAL MASUK
    // ==========================================

    $tanggalMasuk = $request->query('tanggal');

    // kalau tidak ada parameter ?tanggal=
    // gunakan hari ini
    if (empty($tanggalMasuk)) {
        $tanggalMasuk = Carbon::today()->format('Y-m-d');
    }

    // ==========================================
    // BOOKING AKTIF
    // ==========================================

    $bookingAktif = Booking::where('kamar_id', $kamar->id)
        ->where('status', '!=', 'cancel')
        ->orderBy('tanggal_masuk')
        ->get([
            'tanggal_masuk',
            'tanggal_selesai'
        ]);

    return view(
        'booking.booking',
        compact(
            'kamar',
            'tanggalMasuk',
            'bookingAktif'
        )
    );
}


    public function store(Request $request, $id)
{
    $kamar = Kamar::findOrFail($id);

    // ==========================================
    // BATAS TANGGAL BOOKING
    // ==========================================

    $maxTanggal = Carbon::today()
        ->addMonths(6)
        ->format('Y-m-d');

    // ==========================================
    // VALIDASI
    // ==========================================

    $request->validate([
        'tanggal_masuk' => [
            'required',
            'date',
            'after_or_equal:today',
            'before_or_equal:' . $maxTanggal,
        ],

        'durasi' => [
            'required',
            'integer',
            'min:1',
            'max:24',
        ],

        'foto_identitas' => [
            'required',
            'image',
            'mimes:jpg,jpeg,png',
            'max:2048',
        ],
    ]);

    // ==========================================
    // HITUNG TANGGAL SEWA
    // ==========================================

    $durasi = (int) $request->durasi;

    $tanggalMasuk = Carbon::parse(
        $request->tanggal_masuk
    );

    $tanggalSelesai = $tanggalMasuk
        ->copy()
        ->addMonthsNoOverflow($durasi);

    // ==========================================
    // CEK BOOKING LAIN
    // ==========================================

    $bookingLain = Booking::where('kamar_id', $kamar->id)
        ->where('status', '!=', 'cancel')
        ->orderBy('tanggal_masuk')
        ->get();

    foreach ($bookingLain as $booking) {

        $mulai = Carbon::parse(
            $booking->tanggal_masuk
        );

        $selesai = Carbon::parse(
            $booking->tanggal_selesai
        );

        // Jeda 2 hari setelah checkout
        $bolehMasuk = $selesai->copy()->addDays(2);

        if (
            $tanggalMasuk < $bolehMasuk &&
            $tanggalSelesai > $mulai
        ) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'Tanggal booking bertabrakan dengan jadwal penyewa lain.'
                );
        }
    }

    // ==========================================
    // UPLOAD IDENTITAS
    // ==========================================

    $fotoIdentitas = null;

    if ($request->hasFile('foto_identitas')) {

        $fotoIdentitas = $request
            ->file('foto_identitas')
            ->store('identitas', 'public');
    }

    // ==========================================
    // TOTAL NILAI SELURUH MASA SEWA
    // ==========================================

    $totalHarga = $kamar->harga * $durasi;

    // ==========================================
    // BUAT BOOKING
    // ==========================================

    $booking = Booking::create([
        'user_id' => auth()->id(),
        'kamar_id' => $kamar->id,
        'whatsapp' => auth()->user()->no_telp,
        'foto_identitas' => $fotoIdentitas,

        'tanggal_masuk' => $tanggalMasuk,
        'tanggal_selesai' => $tanggalSelesai,

        'durasi' => $durasi,

        // Total keseluruhan masa sewa
        'total_harga' => $totalHarga,

        'status' => 'pending',
    ]);

    // ==========================================
    // LANJUT KE PEMBAYARAN PERIODE PERTAMA
    // ==========================================

    return redirect()
        ->route('payment.create', $booking->id);
}


    public function cancel($id)
{
    $booking = Booking::findOrFail($id);

    // Pastikan booking milik user yang sedang login
    if ($booking->user_id !== auth()->id()) {
        abort(403, 'Akses ditolak.');
    }

    // Sudah dibatalkan
    if ($booking->status === 'cancel') {
        return back()->with(
            'error',
            'Booking sudah dibatalkan.'
        );
    }

    // ==========================================
    // CEK APAKAH SUDAH PERNAH BERHASIL BAYAR
    // ==========================================

    $sudahBayar = $booking->payments()
        ->where('status', 'success')
        ->exists();

    if ($sudahBayar) {
        return back()->with(
            'error',
            'Booking yang sudah dibayar tidak dapat dibatalkan.'
        );
    }

    // ==========================================
    // BATALKAN BOOKING
    // ==========================================

    $booking->update([
        'status' => 'cancel',
    ]);

    // ==========================================
    // BATALKAN PAYMENT YANG MASIH PENDING
    // ==========================================

    $booking->payments()
        ->where('status', 'pending')
        ->update([
            'status' => 'failed',
            'transaction_status' => 'cancel',
        ]);

    return back()->with(
        'success',
        'Booking berhasil dibatalkan.'
    );
}



    public function riwayat()
    {

        $bookings = Booking::with([
            'kamar',
            'payments'
        ])
        ->where('user_id',auth()->id())
        ->latest()
        ->get();


        return view(
            'booking.riwayat',
            compact('bookings')
        );

    }

}