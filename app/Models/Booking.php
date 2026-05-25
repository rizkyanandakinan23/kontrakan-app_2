<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [

        'user_id',
        'kamar_id',
        'whatsapp',
        'tanggal_masuk',
        'durasi',
        'metode_pembayaran',
        'total_harga',
        'status',

    ];
}