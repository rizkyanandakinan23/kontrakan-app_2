<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kamar;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $tanggal = Carbon::parse(
            $request->tanggal ?? now()->toDateString()
        );

        $kamars = Kamar::with([
            'bookings.payment',
            'bookings.user'
        ])->latest()->get();

        foreach ($kamars as $kamar) {

            $status = 'tersedia';
            $bookingAktif = null;

            foreach ($kamar->bookings as $booking) {

                if (
                    !$booking->payment ||
                    $booking->payment->transaction_status != 'settlement'
                ) {
                    continue;
                }

                $mulai = Carbon::parse($booking->tanggal_masuk);

                $selesai = Carbon::parse($booking->tanggal_selesai)
                    ->addDays(2);

                if ($tanggal->between($mulai, $selesai->copy()->subDay())) {

                    $status = $tanggal->lt($mulai)
                        ? 'booking'
                        : 'terisi';

                    $bookingAktif = $booking;

                    break;
                }
            }

            $kamar->status_booking = $status;
            $kamar->booking_aktif = $bookingAktif;
        }

        $kamars = $kamars
    ->filter(function ($kamar) {
        return $kamar->status_booking != 'terisi';
    })
    ->take(3)
    ->values();

        return view('dashboard', compact(
            'kamars',
            'tanggal'
        ));
    }
}