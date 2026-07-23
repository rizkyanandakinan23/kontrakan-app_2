<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function create($id)
    {
        $booking = Booking::with('kamar')
            ->findOrFail($id);

        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $orderId = 'PAY-' . $booking->id . '-' . time();

        $snapToken = Snap::getSnapToken([
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $booking->total_harga
            ],

            'customer_details' => [
                'first_name' => auth()->user()->nama_lengkap,
                'phone' => $booking->whatsapp
            ]
        ]);

        $payment = Payment::create([
            'booking_id' => $booking->id,
            'order_id' => $orderId,
            'snap_token' => $snapToken,
            'metode_pembayaran' => 'Midtrans',
            'jumlah' => $booking->total_harga,
            'status' => 'pending',
            'transaction_status' => 'pending'
        ]);

        return view('booking.midtrans', [
            'booking' => $booking,
            'payment' => $payment,
            'kamar' => $booking->kamar
        ]);
    }

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

            $payment->booking->update([
                'status' => 'paid'
            ]);

            // NOTIF ADMIN
            $admins = User::where('is_admin', 1)->get();

            foreach ($admins as $admin) {
    $admin->notify(
    new SystemNotification(
        'Booking Baru',
        'Booking #' . $payment->booking_id .
        ' untuk kamar "' . $payment->booking->kamar->nama_kamar .
        '" berhasil dibayar. Silakan pantau pembayaran/booking ini.'
    )
);
}

            // NOTIF USER
if ($payment->booking->user) {

    $payment->booking->user->notify(
        new SystemNotification(
            'Pembayaran Berhasil',
            'Pembayaran booking untuk kamar "' .
            $payment->booking->kamar->nama_kamar .
            '" berhasil. Terima kasih telah melakukan pembayaran.'
        )
    );

}
        }

        elseif (in_array($status, ['expire', 'cancel', 'deny'])) {

            $payment->update([
                'status' => 'failed',
                'transaction_status' => $status
            ]);

            $payment->booking->update([
                'status' => 'cancel'
            ]);
        }

        return response()->json([
            'message' => 'OK'
        ]);
    }

   
}