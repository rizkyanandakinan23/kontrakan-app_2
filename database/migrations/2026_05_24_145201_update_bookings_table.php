<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // hapus kolom lama kalau masih ada
            if (Schema::hasColumn('bookings', 'status')) {
                $table->dropColumn('status');
            }

            // status pembayaran (REKOMENDASI STRING, bukan ENUM)
            $table->string('status_pembayaran')->default('pending');

            // bukti transfer
            $table->string('bukti_pembayaran')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'status_pembayaran',
                'bukti_pembayaran'
            ]);

            // rollback ke versi lama (kalau dibutuhkan)
            $table->string('status')->default('pending');
        });
    }
};