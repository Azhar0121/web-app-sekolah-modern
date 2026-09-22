<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role_id', 'is_active'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'qr_token_expires_at' => 'datetime',
        ];
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function hasRole(string ...$slugs): bool
    {
        return $this->role && in_array($this->role->slug, $slugs, true);
    }

    public function hasPermission(string $permissionSlug): bool
    {
        return $this->role && $this->role->hasPermission($permissionSlug);
    }

    public function teachingAssignments(): HasMany
    {
        return $this->hasMany(TeachingAssignment::class, 'teacher_id');
    }

    public function currentClassroom(): ?Classroom
    {
        $activeYear = AcademicYear::active();

        if (! $activeYear) {
            return null;
        }

        $enrollment = ClassroomStudent::with('classroom')
            ->where('academic_year_id', $activeYear->id)
            ->where('student_id', $this->id)
            ->first();

        return $enrollment?->classroom;
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    public function children(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_student', 'parent_id', 'student_id');
    }

    public function studentProfile(): HasOne
    {
        return $this->hasOne(StudentProfile::class);
    }

    public function classroomStudents(): HasMany
    {
        return $this->hasMany(ClassroomStudent::class, 'student_id');
    }

    public function parents(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'parent_student', 'student_id', 'parent_id');
    }

    public function billingRecords(): HasMany
    {
        return $this->hasMany(BillingRecord::class, 'student_id');
    }

    public function reportCards(): HasMany
    {
        return $this->hasMany(ReportCard::class, 'student_id');
    }

    public function leaveRequestsAsStudent(): HasMany
    {
        return $this->hasMany(LeaveRequest::class, 'student_id');
    }

    public function ppdbRegistration(): HasOne
    {
        return $this->hasOne(PpdbRegistration::class);
    }

    public function ensureQrToken(): string
    {
        if (! $this->qr_token) {
            $this->qr_token = bin2hex(random_bytes(20));
            $this->save();
        }

        return $this->qr_token;
    }

    public function rotateQrToken(int $ttlSeconds = 25): string
    {
        $this->qr_token = bin2hex(random_bytes(20));
        $this->qr_token_expires_at = now()->addSeconds($ttlSeconds);
        $this->save();

        return $this->qr_token;
    }

    public function isQrTokenValid(): bool
    {
        return $this->qr_token
            && $this->qr_token_expires_at
            && now()->lessThan($this->qr_token_expires_at);
    }
}