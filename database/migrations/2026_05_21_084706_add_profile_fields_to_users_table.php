<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('nama_lengkap')->after('id');
            $table->string('username')->unique()->after('nama_lengkap');
            $table->string('no_telp')->nullable()->after('email');
            $table->text('alamat')->nullable()->after('no_telp');
            $table->string('foto')->nullable()->after('alamat');

        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropColumn([
                'nama_lengkap',
                'username',
                'no_telp',
                'alamat',
                'foto'
            ]);

        });
    }
};