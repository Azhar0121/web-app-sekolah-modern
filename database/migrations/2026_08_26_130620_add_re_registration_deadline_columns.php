<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->unsignedInteger('re_registration_days')->default(7)->after('quota');
        });

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->timestamp('accepted_at')->nullable()->after('status');
            $table->date('re_registration_deadline')->nullable()->after('accepted_at');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_periods', function (Blueprint $table) {
            $table->dropColumn('re_registration_days');
        });

        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn(['accepted_at', 're_registration_deadline']);
        });
    }
};