<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('notifications')->get()->each(function ($notif) {
            $data = json_decode($notif->data, true);

            if (!isset($data['type'])) return;

            // rename value
            if ($data['type'] === 'internal') {
                $data['type'] = 'user';
            }

            if ($data['type'] === 'external') {
                $data['type'] = 'admin';
            }

            DB::table('notifications')
                ->where('id', $notif->id)
                ->update([
                    'data' => json_encode($data)
                ]);
        });
    }

    public function down(): void
    {
        DB::table('notifications')->get()->each(function ($notif) {
            $data = json_decode($notif->data, true);

            if (!isset($data['type'])) return;

            if ($data['type'] === 'user') {
                $data['type'] = 'internal';
            }

            if ($data['type'] === 'admin') {
                $data['type'] = 'external';
            }

            DB::table('notifications')
                ->where('id', $notif->id)
                ->update([
                    'data' => json_encode($data)
                ]);
        });
    }
};