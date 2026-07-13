<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->longText('foto_kamar')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('kamars', function (Blueprint $table) {
            $table->string('foto_kamar')->nullable()->change();
        });
    }
};
