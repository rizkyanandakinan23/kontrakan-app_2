<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // hapus kolom lama
            $table->dropColumn('status');

        });

        Schema::table('bookings', function (Blueprint $table) {

            // buat kolom baru
            $table->enum('status_pembayaran', [
                'pending',
                'pembayaran berhasil',
                'pembayaran gagal'
            ])->default('pending');

            // upload bukti
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

            $table->enum('status', [
                'pending',
                'pembayaran berhasil',
                'pembayaran gagal'
            ])->default('pending');

        });
    }
};