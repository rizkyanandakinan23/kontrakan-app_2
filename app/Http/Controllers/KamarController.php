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

        // ==========================================
        // SEARCH
        // ==========================================

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where(
                    'nama_kamar',
                    'like',
                    '%' . $request->search . '%'
                )
                ->orWhere(
                    'deskripsi',
                    'like',
                    '%' . $request->search . '%'
                );
            });
        }


        // ==========================================
        // SORT
        // ==========================================

        if ($request->sort == 'murah') {

            $query->orderBy('harga', 'asc');

        } elseif ($request->sort == 'mahal') {

            $query->orderBy('harga', 'desc');

        } else {

            $query->orderBy('nama_kamar', 'asc');
        }


        // ==========================================
        // TANGGAL YANG DIPILIH
        // ==========================================

        $tanggal = Carbon::parse(
            $request->tanggal ?? Carbon::today()->toDateString()
        )->startOfDay();


        $kamars = $query->get();


        // ==========================================
        // TENTUKAN STATUS SETIAP KAMAR
        // ==========================================

        foreach ($kamars as $kamar) {

            $status = 'tersedia';
            $bookingTerdekat = null;


            // ==========================================
            // AMBIL SEMUA BOOKING
            // ==========================================

            $bookings = Booking::where(
                'kamar_id',
                $kamar->id
            )
            ->where(
                'status',
                '!=',
                'cancel'
            )
            ->orderBy(
                'tanggal_masuk'
            )
            ->get();


            foreach ($bookings as $booking) {

                $mulai = Carbon::parse(
                    $booking->tanggal_masuk
                )->startOfDay();

                $selesai = Carbon::parse(
                    $booking->tanggal_selesai
                )->startOfDay();

                $batasTerisi = $selesai->copy()->addDays(2);

                if (
                    $tanggal->greaterThanOrEqualTo($mulai) &&
                    $tanggal->lessThan($batasTerisi)
                ) {
                    $status = 'terisi';
                    break;
                }

                // ==========================================
                // PRIORITAS 2
                // AKAN DIBOOKING DALAM 30 HARI
                // ==========================================

                if ($tanggal->lt($mulai)) {

                    $selisihHari = $tanggal->diffInDays(
                        $mulai,
                        false
                    );

                    if (
                        $selisihHari > 0 &&
                        $selisihHari <= 30
                    ) {

                        if (
                            $bookingTerdekat === null ||
                            $mulai->lessThan(
                                Carbon::parse(
                                    $bookingTerdekat->tanggal_masuk
                                )
                            )
                        ) {

                            $bookingTerdekat = $booking;
                        }
                    }
                }
            }


            // ==========================================
            // BOOKING TERDEKAT
            // ==========================================

            if (
                $status !== 'terisi' &&
                $bookingTerdekat !== null
            ) {

                $status = 'booking';
            }


            // ==========================================
            // SIMPAN STATUS
            // ==========================================

            $kamar->status_booking = $status;

            $kamar->tersedia = (
                $status !== 'terisi'
            );
        }


        // ==========================================
        // FILTER STATUS
        // ==========================================

        if ($request->status == 'tersedia') {

            $kamars = $kamars
                ->filter(function ($kamar) {

                    return $kamar->status_booking === 'tersedia';

                })
                ->values();
        }


        // ==========================================
        // STATISTIK
        // ==========================================

        $totalKamar = $kamars->count();

        $kamarKosong = $kamars
            ->where(
                'status_booking',
                'tersedia'
            )
            ->count();

        $kamarBooking = $kamars
            ->where(
                'status_booking',
                'booking'
            )
            ->count();

        $kamarTerisi = $kamars
            ->where(
                'status_booking',
                'terisi'
            )
            ->count();


        return view(
            'kamar.index',
            compact(
                'kamars',
                'tanggal',
                'totalKamar',
                'kamarKosong',
                'kamarBooking',
                'kamarTerisi'
            )
        );
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
            'bookings'
        ])->findOrFail($id);


        // ==========================================
        // TANGGAL
        // ==========================================

        $tanggal = Carbon::parse(
            $request->query(
                'tanggal',
                Carbon::today()->toDateString()
            )
        )->startOfDay();


        $statusBooking = 'tersedia';
        $bookingTerdekat = null;


        // ==========================================
        // CEK BOOKING
        // ==========================================

        foreach ($kamar->bookings as $booking) {

            // ==========================================
            // SKIP BOOKING YANG DIBATALKAN
            // ==========================================

            if ($booking->status === 'cancel') {
                continue;
            }


            $mulai = Carbon::parse(
                $booking->tanggal_masuk
            )->startOfDay();

            $selesai = Carbon::parse(
                $booking->tanggal_selesai
            )->startOfDay();

            $batasTerisi = $selesai->copy()->addDays(2);

            if (
                $tanggal->greaterThanOrEqualTo($mulai) &&
                $tanggal->lessThan($batasTerisi)
            ) {
                $statusBooking = 'terisi';
                break;
            }

            // ==========================================
            // AKAN DIBOOKING DALAM 30 HARI
            // ==========================================

            if ($tanggal->lt($mulai)) {

                $selisihHari = $tanggal->diffInDays(
                    $mulai,
                    false
                );

                if (
                    $selisihHari > 0 &&
                    $selisihHari <= 30
                ) {

                    if (
                        $bookingTerdekat === null ||
                        $mulai->lessThan(
                            Carbon::parse(
                                $bookingTerdekat->tanggal_masuk
                            )
                        )
                    ) {

                        $bookingTerdekat = $booking;
                    }
                }
            }
        }


        // ==========================================
        // BOOKING TERDEKAT
        // ==========================================

        if (
            $statusBooking !== 'terisi' &&
            $bookingTerdekat !== null
        ) {

            $statusBooking = 'booking';
        }


        // ==========================================
        // FORMAT TANGGAL UNTUK VIEW
        // ==========================================

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

        return view(
            'kamar.edit',
            compact('kamar')
        );
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(
        Request $request,
        string $id
    ) {
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