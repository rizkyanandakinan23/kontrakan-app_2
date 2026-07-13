<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {

            $table->id();

            // relasi ke booking
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->cascadeOnDelete();


            // MIDTRANS
            $table->string('order_id')
                ->nullable();

            $table->string('snap_token')
                ->nullable();


            // PEMBAYARAN
            $table->string('metode_pembayaran')
                ->default('midtrans');

            $table->bigInteger('jumlah');


            // STATUS PEMBAYARAN
            $table->string('status')
                ->default('pending');


            // DATA MIDTRANS
            $table->string('transaction_status')
                ->nullable();

            $table->string('payment_type')
                ->nullable();


            // WAKTU BAYAR
            $table->timestamp('paid_at')
                ->nullable();


            // REFUND
            $table->string('refund_status')
                ->nullable();


            $table->timestamps();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};