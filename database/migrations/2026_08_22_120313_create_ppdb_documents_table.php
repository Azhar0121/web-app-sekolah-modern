<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_registration_id')->constrained()->cascadeOnDelete();
            $table->enum('document_type', ['kartu_keluarga', 'akta_lahir', 'rapor', 'foto', 'lainnya']);
            $table->string('original_name');
            $table->string('file_path'); // path relatif di storage/app/public
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_documents');
    }
};