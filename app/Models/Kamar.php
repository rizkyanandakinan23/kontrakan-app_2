<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kamar extends Model
{
    protected $fillable = [
        'nama_kamar',
        'deskripsi',
        'harga',
        'fasilitas',
        'foto_kamar',
        'status',
    ];

    protected $casts = [
        'foto_kamar' => 'array',
        'fasilitas' => 'array',
    ];

    public function reviews()
{
    return $this->hasMany(Review::class);
}
    
}

