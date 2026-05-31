<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Kamar;

class Booking extends Model
{
    protected $fillable = [

        // RELASI
        'user_id',
        'kamar_id',

        // DATA BOOKING
        'whatsapp',
        'tanggal_masuk',
        'durasi',
        'metode_pembayaran',
        'total_harga',

        // STATUS
        'status_pembayaran',
        'bukti_pembayaran',

        // MIDTRANS
        'snap_token',
        'order_id',
        'transaction_status',
        'payment_type',
        'paid_at',
    ];

    protected $casts = [

        'tanggal_masuk' => 'date',
        'paid_at' => 'datetime',

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
}