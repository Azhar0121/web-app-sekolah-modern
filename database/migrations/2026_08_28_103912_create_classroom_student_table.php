<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('classroom_student', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('classroom_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            // 1 siswa hanya boleh terdaftar di 1 kelas per tahun ajaran
            $table->unique(['academic_year_id', 'student_id'], 'classroom_student_unique_enrollment');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('classroom_student');
    }
};