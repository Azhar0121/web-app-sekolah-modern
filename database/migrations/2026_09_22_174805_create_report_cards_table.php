<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('report_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('file_original_name');
            $table->string('label')->nullable(); // "Rapor Semester 1 - 2026/2027"
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'semester_id']); // 1 rapor per siswa per semester
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_cards');
    }
};
