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
    Schema::create('bookings', function (Blueprint $table) {

        $table->id();

        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->foreignId('kamar_id')->constrained()->onDelete('cascade');

        $table->string('whatsapp');

        $table->date('tanggal_masuk');

        $table->integer('durasi');

        $table->string('metode_pembayaran');

        $table->bigInteger('total_harga');

        $table->enum('status', [
            'pending',
            'dibayar',
            'ditolak'
        ])->default('pending');

        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
