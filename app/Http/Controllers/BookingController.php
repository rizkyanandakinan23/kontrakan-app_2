<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use App\Models\Booking;
use Illuminate\Http\Request;

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
    // PROSES BOOKING + HALAMAN PEMBAYARAN
    // ======================
    public function store(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);

        $request->validate([
            'whatsapp' => 'required',
            'tanggal_masuk' => 'required|date',
            'durasi' => 'required|integer|min:1',
            'metode_pembayaran' => 'required',
        ]);

        $totalHarga = $kamar->harga * $request->durasi;
        $metode = $request->metode_pembayaran;

        // rekening
        $rekening = null;
        $atasNama = null;

        if ($metode == 'BCA') {
            $rekening = '1234567890';
            $atasNama = 'Kontrakan RDP';
        } elseif ($metode == 'BSI') {
            $rekening = '9876543210';
            $atasNama = 'Kontrakan RDP';
        }

        // simpan booking dulu
        $booking = Booking::create([
            'user_id' => auth()->id(),
            'kamar_id' => $kamar->id,
            'whatsapp' => $request->whatsapp,
            'tanggal_masuk' => $request->tanggal_masuk,
            'durasi' => $request->durasi,
            'metode_pembayaran' => $metode,
            'total_harga' => $totalHarga,
            'status_pembayaran' => 'pending',
        ]);

        return view('booking.pembayaran', [
            'booking' => $booking,
            'kamar' => $kamar,
            'totalHarga' => $totalHarga,
            'metode' => $metode,
            'rekening' => $rekening,
            'atasNama' => $atasNama,
        ]);
    }

    // ======================
    // UPLOAD BUKTI PEMBAYARAN
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
            ->with('success', 'Bukti pembayaran berhasil diupload, menunggu verifikasi admin');
    }

    // ======================
    // RIWAYAT BOOKING
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