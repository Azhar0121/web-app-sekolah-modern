<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->text('note')->nullable();
            $table->dateTime('submitted_at')->nullable();
            $table->unsignedTinyInteger('grade')->nullable(); // 0-100
            $table->text('feedback')->nullable();
            $table->dateTime('graded_at')->nullable();
            $table->timestamps();

            $table->unique(['task_id', 'student_id']); // 1 siswa hanya 1 baris pengumpulan per tugas
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_submissions');
    }
};