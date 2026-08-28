<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_assignment_id', 'title', 'description',
        'deadline', 'file_path', 'file_original_name', 'is_published',
    ];

    protected function casts(): array
    {
        return [
            'deadline' => 'datetime',
            'is_published' => 'boolean',
        ];
    }

    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(TaskSubmission::class);
    }

    public function isPastDeadline(): bool
    {
        return now()->greaterThan($this->deadline);
    }

    public function hasFile(): bool
    {
        return ! empty($this->file_path);
    }
}
