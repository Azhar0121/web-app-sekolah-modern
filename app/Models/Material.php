<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_assignment_id', 'title', 'description',
        'file_path', 'file_original_name', 'link', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
        ];
    }

    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    public function hasFile(): bool
    {
        return ! empty($this->file_path);
    }

    public function hasLink(): bool
    {
        return ! empty($this->link);
    }
}
