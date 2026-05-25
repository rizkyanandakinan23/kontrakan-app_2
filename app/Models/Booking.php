<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

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

        // FIX INI
        'status_pembayaran',
        'bukti_pembayaran',
    ];

    // ======================
    // RELASI KE USER
    // ======================
    public function user()
    {
        return $this->belongsTo(User::class);
    }

public function kamar()
{
    return $this->belongsTo(Kamar::class);
}
}