<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PpdbDocument extends Model
{
    use HasFactory;

    protected $fillable = ['ppdb_registration_id', 'document_type', 'original_name', 'file_path'];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(PpdbRegistration::class, 'ppdb_registration_id');
    }

    public function documentTypeLabel(): string
    {
        return match ($this->document_type) {
            'kartu_keluarga' => 'Kartu Keluarga',
            'akta_lahir' => 'Akta Lahir',
            'rapor' => 'Rapor (Semester 1-5)',
            'rapor_1' => 'Rapor Semester 1',
            'rapor_2' => 'Rapor Semester 2',
            'rapor_3' => 'Rapor Semester 3',
            'rapor_4' => 'Rapor Semester 4',
            'rapor_5' => 'Rapor Semester 5',
            'ijazah' => 'Ijazah / SKL',
            'foto' => 'Foto',
            'sertifikat_1' => 'Sertifikat Prestasi 1',
            'sertifikat_2' => 'Sertifikat Prestasi 2',
            'surat_keterangan_lulus' => 'Surat Keterangan Lulus (SKL)',
            'lainnya' => 'Lainnya',
            default => $this->document_type,
        };
    }
}