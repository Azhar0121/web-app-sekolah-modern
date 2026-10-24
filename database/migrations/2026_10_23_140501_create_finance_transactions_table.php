<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finance_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('number', 30)->unique();           // KM-2026-00001 / KK-2026-00001
            $table->enum('type', ['income', 'expense']);
            $table->foreignId('finance_category_id')->constrained('finance_categories')->restrictOnDelete();
            $table->date('transaction_date');
            $table->decimal('amount', 14, 2);
            $table->string('description');
            $table->string('party', 150)->nullable();          // sumber dana / dibayarkan kepada
            $table->enum('payment_method', ['tunai', 'transfer', 'lainnya'])->default('tunai');
            $table->string('reference_number', 100)->nullable(); // no. nota / kwitansi / bukti transfer

            // Khusus pengadaan / perbaikan barang (meja, kursi, dll.)
            $table->string('item_name', 150)->nullable();
            $table->unsignedInteger('quantity')->nullable();
            $table->decimal('unit_price', 14, 2)->nullable();
            $table->foreignId('room_id')->nullable()->constrained('rooms')->nullOnDelete();

            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();

            // Jika diisi, transaksi ini tercatat otomatis dari pembayaran tagihan siswa
            $table->foreignId('billing_record_id')->nullable()->unique()->constrained('billing_records')->nullOnDelete();

            $table->string('proof_path')->nullable();
            $table->string('proof_original_name')->nullable();
            $table->text('notes')->nullable();

            // Transaksi tidak pernah dihapus permanen, hanya dibatalkan (void) agar jejak audit utuh
            $table->enum('status', ['posted', 'void'])->default('posted');
            $table->text('void_reason')->nullable();
            $table->foreignId('voided_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('voided_at')->nullable();

            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['type', 'status', 'transaction_date']);
            $table->index('transaction_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finance_transactions');
    }
};