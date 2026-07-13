<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->boolean('notif_h7')->default(false);
            $table->boolean('notif_h3')->default(false);
            $table->boolean('notif_h1')->default(false);
            $table->boolean('notif_h0')->default(false);

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropColumn([
                'notif_h7',
                'notif_h3',
                'notif_h1',
                'notif_h0'
            ]);

        });
    }
};