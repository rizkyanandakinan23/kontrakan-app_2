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

       $maxTanggal = Carbon::today()->addMonths(6)->format('Y-m-d');

$request->validate([
    'tanggal_masuk' => [
        'required',
        'date',
        'after_or_equal:today',
        'before_or_equal:' . $maxTanggal,
    ],
    'durasi' => 'required|integer|min:1|max:24',
]);


        $durasi = (int)$request->durasi;


        $tanggalMasuk = Carbon::parse($request->tanggal_masuk);

$tanggalSelesai = $tanggalMasuk
    ->copy()
    ->addMonthsNoOverflow($durasi);

// ==========================================
// CEK APAKAH ADA BOOKING LAIN
// ==========================================

$bookingLain = Booking::where('kamar_id', $kamar->id)
    ->where('status', '!=', 'cancel')
    ->orderBy('tanggal_masuk')
    ->get();

foreach ($bookingLain as $booking) {

    $mulai = Carbon::parse($booking->tanggal_masuk);

    $selesai = Carbon::parse($booking->tanggal_selesai);

    // jeda 2 hari setelah checkout
    $bolehMasuk = $selesai->copy()->addDays(2);

    if (
        $tanggalMasuk < $bolehMasuk &&
        $tanggalSelesai > $mulai
    ) {
        return back()->with(
            'error',
            'Tanggal booking bertabrakan dengan jadwal penyewa lain.'
        );
    }
}

// ==========================================
// BARU SIMPAN
// ==========================================

$booking = Booking::create([

            'user_id'=>auth()->id(),

            'kamar_id'=>$kamar->id,

             'whatsapp' => auth()->user()->no_telp,

            'tanggal_masuk'=>$tanggalMasuk,

            'tanggal_selesai'=>$tanggalSelesai,

            'durasi'=>$durasi,

            'total_harga'=>$kamar->harga * $durasi,

            'status'=>'pending'

        ]);


        return redirect()
            ->route('payment.create',$booking->id);
    }



    public function cancel($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->user_id != auth()->id()) {
        return back()->with('error', 'Akses ditolak');
    }

    $booking->status = 'cancel';
    $booking->save();

    return back()->with('success', 'Status booking: ' . $booking->fresh()->status);
}



    public function riwayat()
    {

        $bookings = Booking::with([
            'kamar',
            'payment'
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