<?php

namespace App\Services;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\PpdbRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StudentEnrollmentService
{
    /**
     * Buat akun User untuk siswa yang baru saja dikonfirmasi daftar ulang,
     * lalu otomatis tempatkan ke Kelas X yang jumlah siswanya paling sedikit
     * (kalau kapasitas kelas X masih tersedia).
     *
     * @return array{user: User, classroom: ?Classroom, password: string, placed: bool}
     */
    public function enroll(PpdbRegistration $registration): array
    {
        return DB::transaction(function () use ($registration) {
            $password = Str::password(10);
            $email = $this->resolveUniqueEmail($registration);
            $role = Role::where('slug', 'siswa')->firstOrFail();

            $user = User::create([
                'name' => $registration->full_name,
                'email' => $email,
                'password' => $password,
                'role_id' => $role->id,
                'is_active' => true,
            ]);

            $registration->update(['user_id' => $user->id]);

            $classroom = $this->pickLeastFilledClassroomX();
            $placed = false;

            if ($classroom) {
                $activeYear = AcademicYear::active();

                ClassroomStudent::create([
                    'academic_year_id' => $activeYear->id,
                    'classroom_id' => $classroom->id,
                    'student_id' => $user->id,
                ]);

                $placed = true;
            }

            return [
                'user' => $user,
                'classroom' => $classroom,
                'password' => $password,
                'placed' => $placed,
            ];
        });
    }

    private function resolveUniqueEmail(PpdbRegistration $registration): string
    {
        if (! User::where('email', $registration->email)->exists()) {
            return $registration->email;
        }

        $suffix = strtolower(str_replace('-', '', $registration->registration_number));
        [$localPart, $domain] = explode('@', $registration->email, 2);

        return "{$localPart}+{$suffix}@{$domain}";
    }

    private function pickLeastFilledClassroomX(): ?Classroom
    {
        $activeYear = AcademicYear::active();

        if (! $activeYear) {
            return null;
        }

        return Classroom::where('grade_level', 'X')
            ->where('is_active', true)
            ->withCount(['enrollments as current_student_count' => function ($q) use ($activeYear) {
                $q->where('academic_year_id', $activeYear->id);
            }])
            ->get()
            ->filter(fn (Classroom $c) => is_null($c->capacity) || $c->current_student_count < $c->capacity)
            ->sortBy('current_student_count')
            ->first();
    }
}