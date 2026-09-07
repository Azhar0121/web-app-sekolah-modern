<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_assignment_id', 'semester_id', 'student_id',
        'category', 'label', 'score', 'recorded_by',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
        ];
    }

    public const CATEGORIES = ['tugas', 'uh', 'uts', 'uas'];

    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function categoryLabel(): string
    {
        return match ($this->category) {
            'tugas' => 'Tugas',
            'uh' => 'Ulangan Harian',
            'uts' => 'UTS/PTS',
            'uas' => 'UAS/PAS',
            default => $this->category,
        };
    }
}
