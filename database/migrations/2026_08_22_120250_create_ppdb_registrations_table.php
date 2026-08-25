<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ppdb_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ppdb_period_id')->constrained()->cascadeOnDelete();

            $table->string('registration_number')->unique();

            // Data calon siswa
            $table->string('full_name');
            $table->string('nisn', 20)->nullable();
            $table->string('nik', 20)->nullable();
            $table->enum('gender', ['L', 'P']);
            $table->string('birth_place');
            $table->date('birth_date');
            $table->text('address');
            $table->string('phone');

            // Data orang tua/wali
            $table->string('parent_name');
            $table->string('parent_phone');

            // Asal sekolah
            $table->string('previous_school');

            // Status approval workflow (berlapis: submitted -> verified -> accepted/rejected)
            $table->enum('status', ['draft', 'submitted', 'verified', 'accepted', 'rejected'])
                ->default('submitted');
            $table->text('notes')->nullable(); // catatan verifikasi / alasan penolakan

            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('verified_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ppdb_registrations');
    }
};