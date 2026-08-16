<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedInteger('periode_ke')
                ->default(1)
                ->after('booking_id');

            $table->date('tanggal_periode_mulai')
                ->nullable()
                ->after('periode_ke');

            $table->date('tanggal_periode_selesai')
                ->nullable()
                ->after('tanggal_periode_mulai');

            $table->date('tanggal_jatuh_tempo')
                ->nullable()
                ->after('tanggal_periode_selesai');

            $table->date('batas_pembayaran')
                ->nullable()
                ->after('tanggal_jatuh_tempo');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn([
                'periode_ke',
                'tanggal_periode_mulai',
                'tanggal_periode_selesai',
                'tanggal_jatuh_tempo',
                'batas_pembayaran',
            ]);
        });
    }
};