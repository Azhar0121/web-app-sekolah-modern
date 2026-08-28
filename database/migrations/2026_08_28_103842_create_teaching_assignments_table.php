<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('teacher_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // 1 kelas + 1 mapel + 1 tahun ajaran hanya diampu 1 guru
            $table->unique(['academic_year_id', 'classroom_id', 'subject_id'], 'teaching_assignments_unique_slot');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_assignments');
    }
};