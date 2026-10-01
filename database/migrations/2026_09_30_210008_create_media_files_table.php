<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_files', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->string('original_name');
            $table->string('mime_type');
            $table->string('file_path');
            $table->unsignedBigInteger('file_size'); // bytes
            $table->integer('width')->nullable();
            $table->integer('height')->nullable();
            $table->string('folder')->default('general');
            $table->string('alt_text')->nullable();
            $table->string('caption')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_files');
    }
};
