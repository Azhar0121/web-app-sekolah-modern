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
            $table->string('number')->unique(); // nomor agenda (masuk) atau nomor surat resmi (keluar), auto-generate
            $table->date('letter_date'); // tanggal surat
            $table->string('category', 50); // Undangan, Pemberitahuan, Permohonan, Keputusan, Edaran, Lainnya
            $table->string('subject'); // perihal
            $table->string('correspondent'); // pengirim (surat masuk) / tujuan (surat keluar)
            $table->text('description')->nullable();
            $table->text('disposition')->nullable(); // catatan disposisi/tindak lanjut, khusus surat masuk
            $table->string('status', 20); // masuk: baru/diproses/selesai | keluar: draft/terkirim
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