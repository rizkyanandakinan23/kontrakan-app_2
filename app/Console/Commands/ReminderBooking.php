<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;
use App\Notifications\SystemNotification;

class ReminderBooking extends Command
{
    protected $signature = 'booking:reminder';
    protected $description = 'Kirim notifikasi pengingat masa sewa kontrakan';

    public function handle()
    {
        $today = Carbon::today();

        $bookings = Booking::with(['user', 'kamar', 'payment'])->get();

        foreach ($bookings as $booking) {

            // Skip jika relasi tidak lengkap
            if (!$booking->user || !$booking->kamar || !$booking->payment) {
                continue;
            }

            /*
            |--------------------------------------------------------------------------
            | CEK STATUS PEMBAYARAN
            |--------------------------------------------------------------------------
            | Sesuaikan dengan project kamu.
            | Jika status sukses memakai "success", gunakan status.
            | Jika memakai Midtrans settlement, gunakan transaction_status.
            */

            if ($booking->payment->status != 'success') {
                continue;
            }

            // Hitung sisa hari menuju tanggal selesai
            $sisaHari = $today->diffInDays(
                Carbon::parse($booking->tanggal_selesai),
                false
            );

            $this->info("Booking {$booking->id} | Sisa Hari : {$sisaHari}");

            $flag = null;
            $pesan = null;

            switch ($sisaHari) {

                case 7:

                    if ($booking->notif_h7) {
                        continue 2;
                    }

                    $flag = 'notif_h7';

                    $pesan = 'Masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '" akan berakhir dalam 7 hari.';

                    break;

                case 3:

                    if ($booking->notif_h3) {
                        continue 2;
                    }

                    $flag = 'notif_h3';

                    $pesan = 'Masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '" akan berakhir dalam 3 hari.';

                    break;

                case 1:

                    if ($booking->notif_h1) {
                        continue 2;
                    }

                    $flag = 'notif_h1';

                    $pesan = 'Besok adalah hari terakhir masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '".';

                    break;

                case 0:

                    if ($booking->notif_h0) {
                        continue 2;
                    }

                    $flag = 'notif_h0';

                    $pesan = 'Hari ini adalah hari terakhir masa sewa kamar "' .
                        $booking->kamar->nama_kamar .
                        '".';

                    break;

                default:
                    continue 2;
            }

            // Kirim notifikasi
            $booking->user->notify(
                new SystemNotification(
                    'Peringatan Masa Sewa',
                    $pesan,
                    route('booking.riwayat') // sesuaikan jika nama route berbeda
                )
            );

            // Update flag agar tidak terkirim lagi
            $booking->$flag = true;
            $booking->save();

            $this->info("Notifikasi {$flag} berhasil dikirim ke User {$booking->user->id}");
        }

        $this->info('Reminder booking selesai dijalankan.');

        return Command::SUCCESS;
    }
}