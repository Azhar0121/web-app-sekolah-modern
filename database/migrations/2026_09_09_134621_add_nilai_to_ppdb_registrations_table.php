<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->decimal('nilai_rapor', 5, 2)->nullable()->after('previous_school');
            $table->decimal('nilai_ijazah', 5, 2)->nullable()->after('nilai_rapor');
        });
    }

    public function down(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropColumn(['nilai_rapor', 'nilai_ijazah']);
        });
    }
};