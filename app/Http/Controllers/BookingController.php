<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Booking;
use Illuminate\Http\Request;

use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;

class BookingController extends Controller
{
    // ======================
    // FORM BOOKING
    // ======================
    public function index($id)
    {
        $kamar = Kamar::findOrFail($id);
        return view('booking.booking', compact('kamar'));
    }

    // ======================
    // PROSES BOOKING + MIDTRANS
    // ======================
    public function store(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);

        if ($kamar->status == 'terisi') {

    return back()->with(
        'error',
        'Kamar sudah terisi'
    );
}

        $request->validate([
            'whatsapp' => 'required',
            'tanggal_masuk' => 'required|date',
            'durasi' => 'required|integer|min:1',
        ]);

        // ======================
        // TOTAL HARGA
        // ======================
        $totalHarga = $kamar->harga * $request->durasi;

        // ======================
        // SIMPAN BOOKING (AWAL)
        // ======================
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'kamar_id' => $kamar->id,
            'whatsapp' => $request->whatsapp,
            'tanggal_masuk' => $request->tanggal_masuk,
            'durasi' => $request->durasi,
            'total_harga' => $totalHarga,

            // penting: jangan pakai manual lagi
            'metode_pembayaran' => 'midtrans',

            'status_pembayaran' => 'pending',
        ]);

        // ======================
        // MIDTRANS CONFIG
        // ======================
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // ======================
        // ORDER ID UNIQUE
        // ======================
        $orderId = 'BOOK-' . $booking->id . '-' . time();

        // ======================
        // SNAP PARAMS
        // ======================
        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => (int) $totalHarga,
            ],
            'customer_details' => [
                'first_name' => auth()->user()->nama_lengkap,
                'email' => auth()->user()->email,
                'phone' => $request->whatsapp,
            ]
        ];

        // ======================
        // SNAP TOKEN
        // ======================
        $snapToken = Snap::getSnapToken($params);

        // ======================
        // UPDATE BOOKING
        // ======================
        $booking->update([
            'snap_token' => $snapToken,
            'order_id' => $orderId,
        ]);

        // ======================
        // VIEW MIDTRANS
        // ======================
        return view('booking.midtrans', [
            'booking' => $booking,
            'kamar' => $kamar,
            'snapToken' => $snapToken
        ]);
    }

    // ======================
    // CALLBACK MIDTRANS (WAJIB)
    // ======================
    public function callback(Request $request)
{
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');

    $notification = new Notification();

    $orderId = $notification->order_id;
    $status  = $notification->transaction_status;
    $paymentType = $notification->payment_type;

    $booking = Booking::where('order_id', $orderId)->first();

    if (!$booking) {
        return response()->json([
            'message' => 'booking not found'
        ], 404);
    }

    switch ($status) {

        case 'capture':
        case 'settlement':

            $booking->update([
                'status_pembayaran' => 'dibayar',
                'transaction_status' => $status,
                'payment_type' => $paymentType,
                'paid_at' => now()
            ]);

            $booking->kamar->update([
                'status' => 'terisi'
            ]);

            break;

        case 'pending':

            $booking->update([
                'status_pembayaran' => 'pending',
                'transaction_status' => $status,
                'payment_type' => $paymentType
            ]);

            break;

        case 'expire':

            $booking->update([
                'status_pembayaran' => 'expired',
                'transaction_status' => $status
            ]);

            break;

        case 'deny':
        case 'cancel':

            $booking->update([
                'status_pembayaran' => 'gagal',
                'transaction_status' => $status
            ]);

            break;
    }

    return response()->json([
        'message' => 'ok'
    ]);
}

public function fakeSuccess($id)
{
    $booking = Booking::findOrFail($id);

    $booking->update([
        'status_pembayaran' => 'dibayar',
        'transaction_status' => 'settlement',
        'payment_type' => 'qris',
        'paid_at' => now()
    ]);

    return "SUCCESS SIMULATED";
}

public function cancel($id)
{
    $booking = Booking::findOrFail($id);

    if ($booking->status_pembayaran !== 'pending') {
        return back()->with('error', 'Tidak bisa membatalkan');
    }

    $booking->update([
        'status_pembayaran' => 'cancel'
    ]);

    return back()->with('success', 'Booking dibatalkan');
}

    // ======================
    // UPLOAD BUKTI (OPTIONAL fallback)
    // ======================
    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pembayaran' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $booking = Booking::findOrFail($id);

        $path = $request->file('bukti_pembayaran')->store('bukti', 'public');

        $booking->update([
            'bukti_pembayaran' => $path,
            'status_pembayaran' => 'pending',
        ]);

        return redirect()
            ->route('booking.riwayat')
            ->with('success', 'Bukti pembayaran berhasil diupload');
    }

    // ======================
    // RIWAYAT
    // ======================
    public function riwayat()
    {
        $bookings = Booking::with('kamar')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('booking.riwayat', compact('bookings'));
    }
}