<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{

    protected $fillable = [
    'booking_id',
    'order_id',
    'snap_token',
    'metode_pembayaran',
    'jumlah',
    'status',
    'transaction_status',
    'payment_type',
    'paid_at',

    'refund_status',
    'refund_reason',
    'refund_description',
    'refund_account',
    'refunded_at',
];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

}