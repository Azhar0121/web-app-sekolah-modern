<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE ppdb_documents MODIFY document_type
                ENUM(
                    'kartu_keluarga','akta_lahir','rapor',
                    'rapor_1','rapor_2','rapor_3','rapor_4','rapor_5',
                    'ijazah','foto','sertifikat_1','sertifikat_2',
                    'surat_keterangan_lulus','lainnya'
                )
                NOT NULL");
        }
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE ppdb_documents MODIFY document_type
                ENUM('kartu_keluarga','akta_lahir','rapor','foto','sertifikat_1','sertifikat_2','surat_keterangan_lulus','lainnya')
                NOT NULL");
        }
    }
};