<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserImportBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_code',
        'file_name',
        'total_accounts',
        'credentials',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'credentials' => 'array',
            'total_accounts' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
