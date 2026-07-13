<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('notifications')
            ->where('type', 'App\\Notifications\\SystemNotification')
            ->update([
                'data' => DB::raw("JSON_SET(data, '$.type', 'external')")
            ]);
    }

    public function down(): void
    {
        DB::table('notifications')
            ->update([
                'data' => DB::raw("JSON_REMOVE(data, '$.type')")
            ]);
    }
};
