<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kamars', function (Blueprint $table) {
            $table->id();

            $table->string('nama_kamar');
            $table->text('deskripsi');
            $table->integer('harga');

            // fasilitas (pakai JSON biar bisa checkbox)
            $table->json('fasilitas')->nullable();

            // foto kamar
            $table->string('foto_kamar')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kamars');
    }
};