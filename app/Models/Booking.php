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
];

    protected $casts = [

        'tanggal_masuk' => 'date',
        'tanggal_selesai' => 'date', // tambah ini
        'paid_at' => 'datetime',

        'notif_h7' => 'boolean',
    'notif_h3' => 'boolean',
    'notif_h1' => 'boolean',
    'notif_h0' => 'boolean',

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
    return $this->hasOne(Payment::class);
}

public function getKeteranganAttribute()
{
    // =====================================
    // BOOKING DIBATALKAN
    // =====================================
    if ($this->status == 'cancel') {
        return [
            'text' => 'Booking telah dibatalkan.',
            'color' => 'red'
        ];
    }

    // =====================================
    // BELUM MELANJUTKAN PEMBAYARAN
    // =====================================
    if (!$this->payment) {
        return [
            'text' => 'Belum melanjutkan pembayaran',
            'color' => 'yellow'
        ];
    }

    // =====================================
    // MENUNGGU PEMBAYARAN
    // =====================================
    if ($this->payment->status == 'pending') {
        return [
            'text' => 'Menunggu pembayaran',
            'color' => 'yellow'
        ];
    }

    // =====================================
    // PEMBAYARAN BERHASIL
    // =====================================
    if ($this->payment->status == 'success') {

        $hariIni = Carbon::today();

        $tanggalMasuk = Carbon::parse(
            $this->tanggal_masuk
        )->startOfDay();

        $tanggalSelesai = Carbon::parse(
            $this->tanggal_selesai
        )->startOfDay();

        // =====================================
        // BOOKING BERHASIL, BELUM MASUK MASA SEWA
        // =====================================
        if ($hariIni->lt($tanggalMasuk)) {
            return [
                'text' => 'Booking berhasil. Kontrakan akan segera diisi.',
                'color' => 'blue'
            ];
        }

        // =====================================
        // MASA SEWA SUDAH SELESAI
        // =====================================
        if ($hariIni->gt($tanggalSelesai)) {
            return [
                'text' => 'Masa sewa selesai',
                'color' => 'gray'
            ];
        }

        // =====================================
        // MASA SEWA HAMPIR HABIS
        // =====================================
        $hariSisa = $hariIni->diffInDays(
            $tanggalSelesai,
            false
        );

        if ($hariSisa <= 7 && $hariSisa > 0) {
            return [
                'text' => "Masa sewa hampir habis ({$hariSisa} hari lagi)",
                'color' => 'orange'
            ];
        }

        // =====================================
        // KONTRAKAN SEDANG DIHUNI
        // =====================================
        return [
            'text' => 'Kontrakan terisi',
            'color' => 'green'
        ];
    }

    // =====================================
    // STATUS PEMBAYARAN LAIN
    // =====================================
    return [
        'text' => 'Booking dibatalkan',
        'color' => 'red'
    ];
}

}