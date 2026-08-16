<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'booking_id',

        'periode_ke',
        'tanggal_periode_mulai',
        'tanggal_periode_selesai',
        'tanggal_jatuh_tempo',
        'batas_pembayaran',

        'order_id',
        'snap_token',
        'metode_pembayaran',
        'jumlah',
        'status',
        'transaction_status',
        'payment_type',
        'paid_at',
    ];

    protected $casts = [
        'tanggal_periode_mulai' => 'date',
        'tanggal_periode_selesai' => 'date',
        'tanggal_jatuh_tempo' => 'date',
        'batas_pembayaran' => 'date',
        'paid_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}