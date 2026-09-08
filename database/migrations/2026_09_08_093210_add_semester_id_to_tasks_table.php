<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('semester_id')->nullable()->after('teaching_assignment_id')
                ->constrained()->nullOnDelete();
        });

        $activeSemesterId = DB::table('semesters')->where('is_active', true)->value('id');

        if ($activeSemesterId) {
            DB::table('tasks')->whereNull('semester_id')->update(['semester_id' => $activeSemesterId]);
        }
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropConstrainedForeignId('semester_id');
        });
    }
};