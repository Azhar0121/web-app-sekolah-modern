<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grade_weights', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teaching_assignment_id')->constrained()->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained()->cascadeOnDelete();

            $table->unsignedTinyInteger('tugas_weight')->default(20);
            $table->unsignedTinyInteger('uh_weight')->default(30);
            $table->unsignedTinyInteger('uts_weight')->default(20);
            $table->unsignedTinyInteger('uas_weight')->default(30);

            $table->timestamps();

            $table->unique(['teaching_assignment_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_weights');
    }
};