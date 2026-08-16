<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kamar;
use Carbon\Carbon;
use App\Models\Payment;
class Booking extends Model
{
    protected $fillable = [
    'user_id',
    'kamar_id',
    'whatsapp',
    'tanggal_masuk',
    'durasi',
    'tanggal_selesai',
    'foto_identitas',
    'metode_pembayaran',
    'total_harga',
    'status', // <-- TAMBAHKAN
    'snap_token',
    'order_id',
    'transaction_status',
    'payment_type',
    'paid_at',
    // tambahkan
    'notif_h7',
    'notif_h3',
    'notif_h1',
    'notif_h0',
    // Flag reminder pembayaran
    'notif_bayar_h0',
    'notif_bayar_h3',
    'notif_bayar_h7',
    'notif_bayar_h10',
];

    protected $casts = [
        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'date', // tambah ini
        'paid_at' => 'datetime',
        'notif_h7' => 'boolean',
        'notif_h3' => 'boolean',
        'notif_h1' => 'boolean',
        'notif_h0' => 'boolean',
    'notif_bayar_h0' => 'boolean',
    'notif_bayar_h3' => 'boolean',
    'notif_bayar_h7' => 'boolean',
    'notif_bayar_h10' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELASI USER
    |--------------------------------------------------------------------------
    */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    /*
    |--------------------------------------------------------------------------
    | RELASI KAMAR
    |--------------------------------------------------------------------------
    */
    public function kamar()
    {
        return $this->belongsTo(Kamar::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

public function getKeteranganAttribute()
{
    // =========================================================
    // BOOKING DIBATALKAN
    // =========================================================
    if ($this->status === 'cancel') {
        return [
            'text' => 'Booking telah dibatalkan.',
            'color' => 'red'
        ];
    }

    // =========================================================
    // BELUM MELANJUTKAN PEMBAYARAN
    // =========================================================
    if (!$this->payment) {
        return [
            'text' => 'Belum melanjutkan pembayaran.',
            'color' => 'yellow'
        ];
    }


    // =========================================================
    // PEMBAYARAN MASIH MENUNGGU
    // =========================================================
    if ($this->payment->status === 'pending') {

        // -----------------------------------------------------
        // CEK TANGGAL JATUH TEMPO
        // -----------------------------------------------------
        if ($this->payment->tanggal_jatuh_tempo) {
            $hariIni = Carbon::today();
            $tanggalJatuhTempo = Carbon::parse(
                $this->payment->tanggal_jatuh_tempo
            )->startOfDay();

            // -------------------------------------------------
            // PEMBAYARAN SUDAH JATUH TEMPO
            // -------------------------------------------------

            if ($hariIni->gte($tanggalJatuhTempo)) {
                $hariTerlambat = $tanggalJatuhTempo->diffInDays(
                    $hariIni,
                    false
                );

                return [
                    'text' => 'Pembayaran periode ke-' .
                        $this->payment->periode_ke .
                        ' telah jatuh tempo' .
                        ($hariTerlambat > 0
                            ? " dan terlambat {$hariTerlambat} hari."
                            : '.'),
                    'color' => 'red'
                ];
            }
        }

        // -----------------------------------------------------
        // BELUM JATUH TEMPO
        // -----------------------------------------------------
        return [
            'text' => 'Menunggu pembayaran.',
            'color' => 'yellow'
        ];
    }

    // =========================================================
    // PEMBAYARAN BERHASIL
    // =========================================================
    if ($this->payment->status === 'success') {
        $hariIni = Carbon::today();

        // -----------------------------------------------------
        // TANGGAL MASUK
        // -----------------------------------------------------
        $tanggalMasuk = Carbon::parse(
            $this->tanggal_masuk
        )->startOfDay();

        // -----------------------------------------------------
        // TANGGAL SELESAI
        // -----------------------------------------------------
        $tanggalSelesai = Carbon::parse(
            $this->tanggal_selesai
        )->startOfDay();

        // -----------------------------------------------------
        // BOOKING BERHASIL, BELUM MASUK MASA SEWA
        // -----------------------------------------------------
        if ($hariIni->lt($tanggalMasuk)) {
            return [
                'text' => 'Booking berhasil. Kontrakan akan segera diisi.',
                'color' => 'blue'
            ];
        }

        // -----------------------------------------------------
        // MASA SEWA SUDAH SELESAI
        // -----------------------------------------------------
        if ($hariIni->gt($tanggalSelesai)) {
            return [
                'text' => 'Masa sewa selesai.',
                'color' => 'gray'
            ];
        }

        // -----------------------------------------------------
        // MASA SEWA HAMPIR HABIS
        // -----------------------------------------------------
        $hariSisa = $hariIni->diffInDays(
            $tanggalSelesai,
            false
        );

        if ($hariSisa <= 7 && $hariSisa > 0) {
            return [
                'text' => "Masa sewa hampir habis ({$hariSisa} hari lagi).",
                'color' => 'orange'
            ];
        }

        // -----------------------------------------------------
        // KONTRAKAN SEDANG DIHUNI
        // -----------------------------------------------------
        return [
            'text' => 'Kontrakan terisi.',
            'color' => 'green'
        ];
    }

    // =========================================================
    // STATUS PEMBAYARAN GAGAL
    // =========================================================
    if ($this->payment->status === 'failed') {
        return [
            'text' => 'Pembayaran periode ke-' .
                $this->payment->periode_ke .
                ' gagal. Silakan lakukan pembayaran kembali.',
            'color' => 'red'
        ];
    }

    // =========================================================
    // STATUS PEMBAYARAN LAINNYA
    // =========================================================
    return [
        'text' => 'Status pembayaran belum dapat diproses.',
        'color' => 'gray'
    ];
}

}