<?php

namespace App\Http\Controllers;

use App\Models\Kamar;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    // ======================
    // HALAMAN BOOKING
    // ======================

    public function index($id)
    {
        $kamar = Kamar::findOrFail($id);

        return view('booking.booking', compact('kamar'));
    }

    // ======================
    // PROSES BOOKING
    // ======================

    public function store(Request $request, $id)
    {
        $kamar = Kamar::findOrFail($id);

        // VALIDASI
        $request->validate([
            'whatsapp' => 'required',
            'tanggal_masuk' => 'required|date',
            'durasi' => 'required|integer|min:1',
            'metode_pembayaran' => 'required',
        ]);

        // TOTAL HARGA
        $totalHarga = $kamar->harga * $request->durasi;

        // METODE PEMBAYARAN
        $metode = $request->metode_pembayaran;

        // ======================
        // DATA PEMBAYARAN
        // ======================

        if ($metode == 'BCA') {

            $rekening = '1234567890';
            $atasNama = 'Kontrakan RDP';

        } elseif ($metode == 'BSI') {

            $rekening = '9876543210';
            $atasNama = 'Kontrakan RDP';

        } else {

            $rekening = null;
            $atasNama = null;
        }

        // KIRIM KE VIEW PEMBAYARAN
        return view('booking.pembayaran', compact(
            'kamar',
            'totalHarga',
            'metode',
            'rekening',
            'atasNama'
        ));
    }
}