<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('osis_members', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position'); 
            $table->string('department')->nullable();
            $table->string('class_name')->nullable();
            $table->string('photo')->nullable();
            $table->string('period')->default('2026/2027');
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('osis_members');
    }
};