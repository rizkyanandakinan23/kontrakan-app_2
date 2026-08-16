<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use App\Notifications\SystemNotification;

class ReminderBooking extends Command
{
    protected $signature = 'booking:reminder';

    protected $description = 'Kirim notifikasi pengingat masa sewa dan pembayaran menunggak';


    public function handle()
    {
        // ==========================================================
        // TANGGAL HARI INI
        // ==========================================================

        $today = Carbon::today();


        // ==========================================================
        // REMINDER MASA SEWA
        // H-7, H-3, H-1, DAN H0
        // ==========================================================

        $bookings = Booking::with([
            'user',
            'kamar',
            'payment'
        ])->get();


        foreach ($bookings as $booking) {

            // ------------------------------------------------------
            // CEK RELASI BOOKING
            // ------------------------------------------------------

            if (
                !$booking->user ||
                !$booking->kamar ||
                !$booking->payment
            ) {
                continue;
            }


            // ------------------------------------------------------
            // CEK STATUS PEMBAYARAN TERAKHIR
            // ------------------------------------------------------

            // Reminder masa sewa hanya dijalankan apabila
            // pembayaran booking sudah berhasil.

            if ($booking->payment->status != 'success') {
                continue;
            }


            // ------------------------------------------------------
            // HITUNG SISA HARI MASA SEWA
            // ------------------------------------------------------

            $sisaHari = $today->diffInDays(
                Carbon::parse($booking->tanggal_selesai),
                false
            );


            $this->info(
                "Booking {$booking->id} | Sisa Hari : {$sisaHari}"
            );


            // ------------------------------------------------------
            // INISIALISASI FLAG DAN PESAN
            // ------------------------------------------------------

            $flag = null;
            $pesan = null;


            // ------------------------------------------------------
            // TENTUKAN REMINDER BERDASARKAN SISA HARI
            // ------------------------------------------------------

            switch ($sisaHari) {

                // --------------------------------------------------
                // H-7
                // --------------------------------------------------

                case 7:

                    if ($booking->notif_h7) {
                        continue 2;
                    }

                    $flag = 'notif_h7';

                    $pesan =
                        'Masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '" akan berakhir dalam 7 hari.';

                    break;


                // --------------------------------------------------
                // H-3
                // --------------------------------------------------

                case 3:

                    if ($booking->notif_h3) {
                        continue 2;
                    }

                    $flag = 'notif_h3';

                    $pesan =
                        'Masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '" akan berakhir dalam 3 hari.';

                    break;


                // --------------------------------------------------
                // H-1
                // --------------------------------------------------

                case 1:

                    if ($booking->notif_h1) {
                        continue 2;
                    }

                    $flag = 'notif_h1';

                    $pesan =
                        'Besok adalah hari terakhir masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '".';

                    break;


                // --------------------------------------------------
                // H0
                // --------------------------------------------------

                case 0:

                    if ($booking->notif_h0) {
                        continue 2;
                    }

                    $flag = 'notif_h0';

                    $pesan =
                        'Hari ini adalah hari terakhir masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '".';

                    break;


                // --------------------------------------------------
                // TIDAK ADA REMINDER
                // --------------------------------------------------

                default:

                    continue 2;
            }


            // ------------------------------------------------------
            // KIRIM NOTIFIKASI REMINDER MASA SEWA
            // ------------------------------------------------------

            $booking->user->notify(
                new SystemNotification(
                    'Peringatan Masa Sewa',
                    $pesan,
                    route('booking.riwayat')
                )
            );


            // ------------------------------------------------------
            // SIMPAN FLAG REMINDER
            // Agar notifikasi tidak dikirim ulang
            // ------------------------------------------------------

            $booking->$flag = true;
            $booking->save();


            $this->info(
                "Notifikasi {$flag} berhasil dikirim ke User {$booking->user->id}"
            );
        }


        // ==========================================================
        // REMINDER PEMBAYARAN MENUNGGAK
        // ==========================================================

        // Ambil seluruh payment yang masih pending.
        //
        // Payment pending berarti pembayaran periode tersebut
        // belum berhasil dilakukan.

        $payments = Payment::with([
            'booking.user',
            'booking.kamar'
        ])
            ->where('status', 'pending')
            ->get();


        foreach ($payments as $payment) {

            // ------------------------------------------------------
            // AMBIL BOOKING DARI PAYMENT
            // ------------------------------------------------------

            $booking = $payment->booking;


            // ------------------------------------------------------
            // CEK RELASI PAYMENT
            // ------------------------------------------------------

            if (
                !$booking ||
                !$booking->user ||
                !$booking->kamar
            ) {
                continue;
            }


            // ------------------------------------------------------
            // HITUNG KETERLAMBATAN PEMBAYARAN
            // ------------------------------------------------------

            $tanggalJatuhTempo = Carbon::parse(
                $payment->tanggal_jatuh_tempo
            )->startOfDay();


            $hariTerlambat = $tanggalJatuhTempo->diffInDays(
                $today,
                false
            );


            // ------------------------------------------------------
            // TAMPILKAN INFORMASI UNTUK TESTING
            // ------------------------------------------------------

            $this->info(
                "Payment {$payment->id} | " .
                "Booking {$booking->id} | " .
                "Periode {$payment->periode_ke} | " .
                "Terlambat : {$hariTerlambat} hari"
            );


            // ------------------------------------------------------
            // BELUM JATUH TEMPO
            // ------------------------------------------------------

            if ($hariTerlambat < 0) {
                continue;
            }


            // ------------------------------------------------------
            // REMINDER PEMBAYARAN
            // ------------------------------------------------------

            // Untuk tahap pertama ini kita hanya mendeteksi
            // pembayaran yang sudah jatuh tempo.
            //
            // Belum ada proses H+3, H+7, atau H+10.
            // Belum ada perubahan status booking.

            $pesanPembayaran =
                'Pembayaran periode ke-' .
                $payment->periode_ke .
                ' untuk kamar "' .
                $booking->kamar->nama_kamar .
                '" telah melewati tanggal jatuh tempo. ' .
                'Segera lakukan pembayaran.';


            // ------------------------------------------------------
            // KIRIM NOTIFIKASI TUNGGAKAN
            // ------------------------------------------------------

            $booking->user->notify(
                new SystemNotification(
                    'Pembayaran Menunggak',
                    $pesanPembayaran,
                    route('booking.riwayat')
                )
            );

            // ------------------------------------------------------
            // INFORMASI TERMINAL
            // ------------------------------------------------------
            $this->info(
                "Reminder tunggakan dikirim ke User {$booking->user->id}"
            );
        }

        // ==========================================================
        // SELESAI
        // ==========================================================

        $this->info(
            'Reminder booking dan pembayaran selesai dijalankan.'
        );


        return Command::SUCCESS;
    }
}