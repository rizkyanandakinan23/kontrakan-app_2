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
        $query->latest();
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

        // checkout + jeda 2 hari
        $selesai = Carbon::parse($item->tanggal_selesai)
            ->addDays(2);

        if (
            Carbon::parse($tanggal)->between(
                $mulai,
                $selesai->copy()->subDay()
            )
        ) {

            if (Carbon::parse($tanggal)->lt($mulai)) {

    $status = 'booking';

} else {

    $status = 'terisi';

}

            break;
        }
    }

    $kamar->status_booking = $status;
    $kamar->tersedia = $status != 'terisi';
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

        if (
            $tanggal->between(
                $mulai,
                $selesai->copy()->subDay()
            )
        ) {

            if ($tanggal->lt($mulai)) {

    $statusBooking = 'booking';

} else {

    $statusBooking = 'terisi';

}

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