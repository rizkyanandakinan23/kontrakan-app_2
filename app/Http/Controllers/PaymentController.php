<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Notifications\SystemNotification;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use Carbon\Carbon;

class PaymentController extends Controller
{
public function create($id)
{
    $booking = Booking::with('kamar')
        ->findOrFail($id);

    // ==========================================
    // CEK AKSES USER
    // ==========================================
    if ($booking->user_id !== auth()->id()) {
        abort(403, 'Akses ditolak.');
    }

    // ==========================================
    // CEK BOOKING
    // ==========================================
    if ($booking->status === 'cancel') {
        return redirect()
            ->route('booking.riwayat')
            ->with('error', 'Booking ini telah dibatalkan.');
    }

    // ==========================================
    // CARI PAYMENT PENDING TERBARU
    // ==========================================
    //
    // Jika masih ada pembayaran pending,
    // jangan membuat transaksi baru.
    // Gunakan payment yang masih pending.
    //
    $paymentPending = $booking->payments()
        ->where('status', 'pending')
        ->latest('periode_ke')
        ->first();

    if ($paymentPending) {

        return view('booking.midtrans', [
            'booking' => $booking,
            'payment' => $paymentPending,
            'kamar' => $booking->kamar
        ]);
    }

    // ==========================================
    // TENTUKAN PERIODE BERIKUTNYA
    // ==========================================
    $periodeTerakhir = $booking->payments()
        ->max('periode_ke');

    $periodeKe = ($periodeTerakhir ?? 0) + 1;

    // ==========================================
    // CEK APAKAH MASIH ADA PERIODE SEWA
    // ==========================================
    if ($periodeKe > $booking->durasi) {
        return redirect()
            ->route('booking.riwayat')
            ->with(
                'success',
                'Seluruh periode pembayaran booking ini sudah selesai.'
            );
    }

    // ==========================================
    // KONFIGURASI MIDTRANS
    // ==========================================
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    // ==========================================
    // TANGGAL PERIODE
    // ==========================================
    $tanggalMulai = Carbon::parse($booking->tanggal_masuk)
        ->addMonthsNoOverflow($periodeKe - 1);
    $tanggalSelesai = Carbon::parse($booking->tanggal_masuk)
        ->addMonthsNoOverflow($periodeKe);

    // ==========================================
    // JATUH TEMPO
    // ==========================================
    $tanggalJatuhTempo = $tanggalMulai->copy();

    // ==========================================
    // BATAS PEMBAYARAN
    // H+10 DARI JATUH TEMPO
    // ==========================================
    $batasPembayaran = $tanggalJatuhTempo
        ->copy()
        ->addDays(10);

    // ==========================================
    // NOMINAL
    // ==========================================
    //
    // Pembayaran dilakukan per bulan,
    // bukan seluruh total booking.
    //
    $jumlah = $booking->kamar->harga;

    // ==========================================
    // ORDER ID MIDTRANS
    // ==========================================
    $orderId = 'PAY-' .
        $booking->id .
        '-P' .
        $periodeKe .
        '-' .
        time();

    // ==========================================
    // SNAP TOKEN
    // ==========================================
    $snapToken = Snap::getSnapToken([

        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $jumlah
        ],

        'customer_details' => [
            'first_name' => auth()->user()->nama_lengkap,
            'phone' => $booking->whatsapp
        ]

    ]);

    // ==========================================
    // SIMPAN PAYMENT
    // ==========================================
    $payment = Payment::create([
        'booking_id' => $booking->id,
        'periode_ke' => $periodeKe,
        'tanggal_periode_mulai' => $tanggalMulai,
        'tanggal_periode_selesai' => $tanggalSelesai,
        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
        'batas_pembayaran' => $batasPembayaran,
        'order_id' => $orderId,
        'snap_token' => $snapToken,
        'metode_pembayaran' => 'Midtrans',
        'jumlah' => $jumlah,
        'status' => 'pending',
        'transaction_status' => 'pending'
    ]);

    // ==========================================
    // TAMPILKAN HALAMAN MIDTRANS
    // ==========================================
    return view('booking.midtrans', [
        'booking' => $booking,
        'payment' => $payment,
        'kamar' => $booking->kamar
    ]);
}

public function createNextPeriod($bookingId)
{
    // ==========================================
    // AMBIL BOOKING
    // ==========================================
    $booking = Booking::with('kamar')
        ->findOrFail($bookingId);

    // ==========================================
    // CEK AKSES USER
    // ==========================================
    if ($booking->user_id !== auth()->id()) {
        abort(403, 'Akses ditolak.');
    }

    // ==========================================
    // CEK PEMBAYARAN TERAKHIR
    // ==========================================
    $lastPayment = $booking->payments()
        ->where('status', 'success')
        ->orderByDesc('periode_ke')
        ->first();

    if (!$lastPayment) {
        return back()->with(
            'error',
            'Pembayaran periode pertama belum berhasil.'
        );
    }

    // ==========================================
    // PERIODE BERIKUTNYA
    // ==========================================
    $periodeKe = $lastPayment->periode_ke + 1;

    // ==========================================
    // CEK APAKAH MASIH ADA PERIODE
    // ==========================================
    if ($periodeKe > $booking->durasi) {
        return back()->with(
            'error',
            'Seluruh periode sewa sudah dibayar.'
        );
    }


    // ==========================================
    // CEK APAKAH PERIODE SUDAH ADA
    // ==========================================
    $existingPayment = $booking->payments()
        ->where('periode_ke', $periodeKe)
        ->latest()
        ->first();

    if ($existingPayment) {
        return view('booking.midtrans', [
            'booking' => $booking,
            'payment' => $existingPayment,
            'kamar' => $booking->kamar
        ]);
    }

    // ==========================================
    // TANGGAL PERIODE
    // ==========================================
    $tanggalMulai = Carbon::parse(
        $booking->tanggal_masuk
    )->addMonthsNoOverflow($periodeKe - 1);

    $tanggalSelesai = $tanggalMulai
        ->copy()
        ->addMonthNoOverflow();

    // ==========================================
    // JATUH TEMPO
    // ==========================================
    $tanggalJatuhTempo = $tanggalMulai->copy();

    // ==========================================
    // BATAS PEMBAYARAN H+10
    // ==========================================
    $batasPembayaran = $tanggalJatuhTempo
        ->copy()
        ->addDays(10);

    // ==========================================
    // NOMINAL
    // ==========================================
    $jumlah = $booking->kamar->harga;

    // ==========================================
    // KONFIGURASI MIDTRANS
    // ==========================================
    Config::$serverKey = config('midtrans.server_key');
    Config::$isProduction = config('midtrans.is_production');
    Config::$isSanitized = true;
    Config::$is3ds = true;

    // ==========================================
    // ORDER ID
    // ==========================================
    $orderId = 'PAY-' .
        $booking->id .
        '-P' .
        $periodeKe .
        '-' .
        time();

    // ==========================================
    // SNAP TOKEN
    // ==========================================
    $snapToken = Snap::getSnapToken([
        'transaction_details' => [
            'order_id' => $orderId,
            'gross_amount' => $jumlah
        ],
        'customer_details' => [
            'first_name' => auth()->user()->nama_lengkap,
            'phone' => $booking->whatsapp
        ]
    ]);

    // ==========================================
    // SIMPAN PAYMENT
    // ==========================================
    $payment = Payment::create([
        'booking_id' => $booking->id,
        'periode_ke' => $periodeKe,
        'tanggal_periode_mulai' => $tanggalMulai,
        'tanggal_periode_selesai' => $tanggalSelesai,
        'tanggal_jatuh_tempo' => $tanggalJatuhTempo,
        'batas_pembayaran' => $batasPembayaran,
        'order_id' => $orderId,
        'snap_token' => $snapToken,
        'metode_pembayaran' => 'Midtrans',
        'jumlah' => $jumlah,
        'status' => 'pending',
        'transaction_status' => 'pending'
    ]);

    // ==========================================
    // TAMPILKAN HALAMAN PEMBAYARAN
    // ==========================================
    return view('booking.midtrans', [
        'booking' => $booking,
        'payment' => $payment,
        'kamar' => $booking->kamar
    ]);
}

    public function callback()
{
    // ==========================================
    // KONFIGURASI MIDTRANS
    // ==========================================
    Config::$serverKey = config('midtrans.server_key');
    $notif = new \Midtrans\Notification();

    // ==========================================
    // CARI PAYMENT
    // ==========================================
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

    // ==========================================
    // PEMBAYARAN BERHASIL
    // ==========================================
    if (in_array($status, ['capture', 'settlement'])) {
        // Hindari callback Midtrans yang masuk berulang
        if ($payment->status === 'success') {
            return response()->json([
                'message' => 'Payment sudah diproses'
            ]);
        }

        $payment->update([
            'status' => 'success',
            'transaction_status' => $status,
            'payment_type' => $notif->payment_type,
            'paid_at' => now()
        ]);

        // ==========================================
        // AMBIL BOOKING
        // ==========================================
        $booking = $payment->booking;
        if ($booking) {
            // ==========================================
            // STATUS BOOKING
            // ==========================================

            // Booking dianggap sudah memiliki
            // pembayaran yang berhasil.
            //
            // Bukan berarti seluruh periode sudah lunas.

            if ($booking->status !== 'paid') {
                $booking->update([
                    'status' => 'paid'
                ]);
            }

            // ==========================================
            // NOTIFIKASI ADMIN
            // ==========================================

            $admins = User::where(
                'is_admin',
                1
            )->get();
            foreach ($admins as $admin) {
                $admin->notify(
                    new SystemNotification(
                        'Pembayaran Berhasil',
                        'Pembayaran periode ke-' .
                        $payment->periode_ke .
                        ' untuk booking #' .
                        $booking->id .
                        ' kamar "' .
                        $booking->kamar->nama_kamar .
                        '" berhasil dibayar.',
                        route('booking.riwayat')
                    )
                );
            }

            // ==========================================
            // NOTIFIKASI USER
            // ==========================================
            if ($booking->user) {
                $booking->user->notify(
                    new SystemNotification(
                        'Pembayaran Berhasil',
                        'Pembayaran periode ke-' .
                        $payment->periode_ke .
                        ' untuk kamar "' .
                        $booking->kamar->nama_kamar .
                        '" berhasil dibayar.',
                        route('booking.riwayat')
                    )
                );
            }
        }
    }

    // ==========================================
    // PEMBAYARAN GAGAL / EXPIRED
    // ==========================================
    elseif (
        in_array(
            $status,
            ['expire', 'cancel', 'deny']
        )
    ) {

        $payment->update([
            'status' => 'failed',
            'transaction_status' => $status
        ]);

        // Jangan membatalkan booking.
        //
        // Yang gagal hanya pembayaran
        // pada periode tersebut.
    }

    return response()->json([
        'message' => 'OK'
    ]);
}

    public function invoice($id)
{
    $booking = Booking::with([
        'payments',
        'kamar',
        'user'
    ])->findOrFail($id);
    $payment = $booking->payments
        ->where('status', 'success')
        ->sortByDesc('periode_ke')
        ->first();

    if (!$payment) {
        abort(404, 'Pembayaran tidak ditemukan.');
    }

    return view('booking.buktipembayaran', compact(
        'booking',
        'payment'
    ));
}
    
}