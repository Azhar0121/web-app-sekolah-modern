<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GradeWeight extends Model
{
    use HasFactory;

    protected $fillable = [
        'teaching_assignment_id', 'semester_id',
        'tugas_weight', 'uh_weight', 'uts_weight', 'uas_weight',
    ];

    public function teachingAssignment(): BelongsTo
    {
        return $this->belongsTo(TeachingAssignment::class);
    }

    public function semester(): BelongsTo
    {
        return $this->belongsTo(Semester::class);
    }

    public function weightFor(string $category): int
    {
        return match ($category) {
            'tugas' => $this->tugas_weight,
            'uh' => $this->uh_weight,
            'uts' => $this->uts_weight,
            'uas' => $this->uas_weight,
            default => 0,
        };
    }
}
