<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // hapus kolom yang sudah tidak dipakai
            if (Schema::hasColumn('bookings', 'status_pembayaran')) {
                $table->dropColumn('status_pembayaran');
            }

            if (Schema::hasColumn('bookings', 'bukti_pembayaran')) {
                $table->dropColumn('bukti_pembayaran');
            }

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            // rollback (kalau migrate rollback)
            $table->string('status_pembayaran')->nullable();
            $table->string('bukti_pembayaran')->nullable();

        });
    }
};
