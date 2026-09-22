<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportCard extends Model
{
    protected $fillable = [
        'student_id', 'semester_id', 'file_path',
        'file_original_name', 'label', 'uploaded_by',
    ];

    public function student(): BelongsTo { return $this->belongsTo(User::class, 'student_id'); }
    public function semester(): BelongsTo { return $this->belongsTo(Semester::class); }
    public function uploadedBy(): BelongsTo { return $this->belongsTo(User::class, 'uploaded_by'); }
}
