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
            'rapor' => 'Rapor',
            'foto' => 'Foto',
            'lainnya' => 'Lainnya',
            default => $this->document_type,
        };
    }
}
