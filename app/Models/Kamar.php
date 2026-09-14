<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Kamar extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'nama_kamar',
        'deskripsi',
        'harga',
        'foto_kamar',
    ];

    protected $casts = [
        'foto_kamar' => 'array',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}