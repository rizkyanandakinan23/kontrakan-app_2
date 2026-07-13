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
    Schema::table('payments', function (Blueprint $table) {

        $table->string('refund_reason')
            ->nullable()
            ->after('refund_status');

        $table->text('refund_description')
            ->nullable()
            ->after('refund_reason');

        $table->string('refund_account')
            ->nullable()
            ->after('refund_description');

        $table->timestamp('refunded_at')
            ->nullable()
            ->after('paid_at');

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::table('payments', function (Blueprint $table) {

        $table->dropColumn([
            'refund_reason',
            'refund_description',
            'refund_account',
            'refunded_at'
        ]);

    });
}
};
