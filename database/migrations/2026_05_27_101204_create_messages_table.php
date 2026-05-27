<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();

            // hubungan ke conversation
            $table->foreignId('conversation_id')
                ->constrained()
                ->onDelete('cascade');

            // siapa yang kirim (user / admin)
            $table->foreignId('sender_id')
                ->constrained('users')
                ->onDelete('cascade');

            $table->text('message');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
