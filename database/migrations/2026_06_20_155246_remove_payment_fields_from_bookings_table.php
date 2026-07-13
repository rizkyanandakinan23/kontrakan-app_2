<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::table('bookings', function (Blueprint $table) {

        $table->dropColumn([
            'metode_pembayaran',
            'snap_token',
            'order_id',
            'transaction_status',
            'payment_type',
            'paid_at',
            'refund_status'
        ]);

    });
}


public function down(): void
{
    Schema::table('bookings', function (Blueprint $table) {

        $table->string('metode_pembayaran')
            ->nullable();

        $table->string('snap_token')
            ->nullable();

        $table->string('order_id')
            ->nullable();

        $table->string('transaction_status')
            ->nullable();

        $table->string('payment_type')
            ->nullable();

        $table->timestamp('paid_at')
            ->nullable();

        $table->string('refund_status')
            ->nullable();

    });
}
};
