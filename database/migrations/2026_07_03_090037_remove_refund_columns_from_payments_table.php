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
            if (Schema::hasColumn('payments', 'refunded_at')) {
                $table->dropColumn([
                    'refunded_at',
                    'refund_status',
                    'refund_reason',
                    'refund_description',
                    'refund_account',
                ]);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (! Schema::hasColumn('payments', 'refunded_at')) {
                $table->timestamp('refunded_at')->nullable();
                $table->string('refund_status')->nullable();
                $table->string('refund_reason')->nullable();
                $table->text('refund_description')->nullable();
                $table->string('refund_account')->nullable();
            }
        });
    }
};