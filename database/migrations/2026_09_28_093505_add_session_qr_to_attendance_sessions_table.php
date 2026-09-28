<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->string('session_qr_token')->nullable()->unique()->after('closed_at');
            $table->timestamp('session_qr_expires_at')->nullable()->after('session_qr_token');
        });
    }

    public function down(): void
    {
        Schema::table('attendance_sessions', function (Blueprint $table) {
            $table->dropColumn(['session_qr_token', 'session_qr_expires_at']);
        });
    }
};