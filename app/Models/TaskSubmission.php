<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id', 'student_id', 'file_path', 'file_original_name',
        'note', 'submitted_at', 'grade', 'feedback', 'graded_at',
    ];

    protected function casts(): array
    {
        return [
            'submitted_at' => 'datetime',
            'graded_at' => 'datetime',
            'grade' => 'integer',
        ];
    }

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function isGraded(): bool
    {
        return ! is_null($this->grade);
    }

    public function isLate(): bool
    {
        return $this->submitted_at && $this->task && $this->submitted_at->greaterThan($this->task->deadline);
    }
}
