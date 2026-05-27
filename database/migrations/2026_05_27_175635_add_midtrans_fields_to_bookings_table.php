<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->string('snap_token')->nullable();

            $table->string('order_id')->nullable();

            $table->string('transaction_status')->nullable();

            $table->string('payment_type')->nullable();

            $table->timestamp('paid_at')->nullable();

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'snap_token',
                'order_id',
                'transaction_status',
                'payment_type',
                'paid_at'
            ]);

        });
    }
};