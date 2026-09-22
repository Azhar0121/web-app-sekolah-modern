<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('billing_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('academic_year_id')->nullable()->constrained('academic_years')->nullOnDelete();
            $table->string('description');   // "SPP Oktober 2026"
            $table->decimal('amount', 12, 2);
            $table->date('due_date')->nullable();
            $table->enum('status', ['unpaid', 'paid', 'overdue'])->default('unpaid');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_proof_path')->nullable();       // bukti bayar dari ortu (opsional)
            $table->string('payment_proof_original_name')->nullable();
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete(); // TU yg konfirmasi
            $table->timestamp('confirmed_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['student_id', 'status']);
            $table->index(['academic_year_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('billing_records');
    }
};
