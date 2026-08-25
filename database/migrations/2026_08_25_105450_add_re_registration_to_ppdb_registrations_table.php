<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            // Nomor bukti pembayaran/kwitansi daftar ulang — diinput manual oleh TU
            // saat siswa datang bayar & daftar ulang secara offline (bukan payment
            // gateway online, sesuai arahan pembimbing magang).
            $table->string('re_registration_reference')->nullable()->after('notes');
            $table->text('re_registration_notes')->nullable()->after('re_registration_reference');
            $table->foreignId('re_registration_confirmed_by')
                ->nullable()
                ->after('re_registration_notes')
                ->constrained('users')
                ->nullOnDelete();
            $table->timestamp('re_registration_confirmed_at')->nullable()->after('re_registration_confirmed_by');
        });

        // Tambah value baru ke enum status: 'registered_ulang' (setelah 'accepted').
        // Pakai raw SQL karena Laravel Schema Builder tidak bisa ubah enum tanpa
        // package doctrine/dbal tambahan.
        DB::statement("
            ALTER TABLE ppdb_registrations
            MODIFY COLUMN status ENUM('draft', 'submitted', 'verified', 'accepted', 'rejected', 'registered_ulang')
            DEFAULT 'submitted'
        ");
    }

    public function down(): void
    {
        Schema::table('ppdb_registrations', function (Blueprint $table) {
            $table->dropConstrainedForeignId('re_registration_confirmed_by');
            $table->dropColumn(['re_registration_reference', 're_registration_notes', 're_registration_confirmed_at']);
        });

        DB::statement("
            ALTER TABLE ppdb_registrations
            MODIFY COLUMN status ENUM('draft', 'submitted', 'verified', 'accepted', 'rejected')
            DEFAULT 'submitted'
        ");
    }
};