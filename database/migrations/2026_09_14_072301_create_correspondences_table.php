<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('correspondences', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['masuk', 'keluar']);
            $table->string('number')->unique();
            $table->date('letter_date');
            $table->string('category', 50);
            $table->string('subject');
            $table->string('correspondent');
            $table->text('description')->nullable();
            $table->text('disposition')->nullable();
            $table->string('status', 20);
            $table->string('file_path')->nullable();
            $table->string('file_original_name')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'letter_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('correspondences');
    }
};