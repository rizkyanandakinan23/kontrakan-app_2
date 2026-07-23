<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Booking;
use Carbon\Carbon;

class KamarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
{
    $query = Kamar::query();

    // SEARCH
    if ($request->filled('search')) {
        $query->where(function ($q) use ($request) {
            $q->where('nama_kamar', 'like', '%' . $request->search . '%')
              ->orWhere('deskripsi', 'like', '%' . $request->search . '%');
        });
    }

    // SORT
    if ($request->sort == 'murah') {
        $query->orderBy('harga', 'asc');
    } elseif ($request->sort == 'mahal') {
        $query->orderBy('harga', 'desc');
    } else {
        $query->orderBy('nama_kamar', 'asc');
    }

    $tanggal = $request->tanggal ?? Carbon::today()->toDateString();

    $kamars = $query->get();

    foreach ($kamars as $kamar) {

    $booking = Booking::where('kamar_id', $kamar->id)
        ->where('status', '!=', 'cancel')
        ->whereHas('payment', function ($q) {
            $q->where('transaction_status', 'settlement');
        })
        ->orderBy('tanggal_masuk')
        ->get();

    $status = 'tersedia';

foreach ($booking as $item) {

    $mulai = Carbon::parse($item->tanggal_masuk);

    $selesai = Carbon::parse($item->tanggal_selesai)
        ->addDays(2);

    // =============================
    // Sedang ditempati
    // =============================
    if (
        Carbon::parse($tanggal)
            ->between($mulai, $selesai->copy()->subDay())
    ) {

        $status = 'terisi';
        break;
    }

    // =============================
    // Akan dibooking (<30 hari)
    // =============================
    $selisihHari = Carbon::parse($tanggal)
        ->diffInDays($mulai, false);

    if ($selisihHari > 0 && $selisihHari <= 30) {

        $status = 'booking';
        break;
    }
}

    $kamar->status_booking = $status;
    $kamar->tersedia = $status != 'terisi';
}

// FILTER STATUS
if ($request->status == 'tersedia') {
    $kamars = $kamars->filter(function ($kamar) {
        return $kamar->status_booking == 'tersedia';
    })->values();
}


$totalKamar = $kamars->count();

$kamarKosong = $kamars
    ->where('status_booking', 'tersedia')
    ->count();

$kamarBooking = $kamars
    ->where('status_booking', 'booking')
    ->count();

$kamarTerisi = $kamars
    ->where('status_booking', 'terisi')
    ->count();

    return view('kamar.index', compact(
    'kamars',
    'tanggal',
    'totalKamar',
    'kamarKosong',
    'kamarBooking',
    'kamarTerisi'
));
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('kamar.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
{
    $kamar = Kamar::with([
        'reviews.user',
        'bookings.payment'
    ])->findOrFail($id);

    $tanggal = Carbon::parse(
        $request->query('tanggal', Carbon::today()->toDateString())
    );

    $statusBooking = 'tersedia';

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

        // Sedang ditempati
        if ($tanggal->between($mulai, $selesai->copy()->subDay())) {
            $statusBooking = 'terisi';
            break;
        }

        // Akan dibooking (< 30 hari)
        $selisihHari = $tanggal->diffInDays($mulai, false);

        if ($selisihHari > 0 && $selisihHari <= 30) {
            $statusBooking = 'booking';
            break;
        }
    }

    $tanggal = $tanggal->format('Y-m-d');

    return view(
        'kamar.detail',
        compact(
            'kamar',
            'tanggal',
            'statusBooking'
        )
    );
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kamar = Kamar::findOrFail($id);

        return view('kamar.edit', compact('kamar'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}