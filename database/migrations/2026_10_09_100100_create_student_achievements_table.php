<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_achievements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category')->default('akademik');
            $table->string('level')->default('kota');
            $table->year('year')->nullable();
            $table->string('organizer')->nullable();
            $table->string('student_name');
            $table->string('student_class')->nullable();
            $table->text('description')->nullable();
            $table->string('photo')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_achievements');
    }
};