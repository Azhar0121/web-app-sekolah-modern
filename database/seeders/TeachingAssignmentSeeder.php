<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class TeachingAssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = AcademicYear::active();
        $guru = User::where('email', 'guru@sekolah.test')->first();
        $kelasX1 = Classroom::where('name', 'X-1')->first();
        $matematika = Subject::where('code', 'MTK')->first();

        if (! $activeYear || ! $guru || ! $kelasX1 || ! $matematika) {
            return; // dependency belum ada, skip supaya seeder lain tidak ikut gagal
        }

        TeachingAssignment::updateOrCreate(
            [
                'academic_year_id' => $activeYear->id,
                'classroom_id' => $kelasX1->id,
                'subject_id' => $matematika->id,
            ],
            ['teacher_id' => $guru->id]
        );

        $informatika = Subject::where('code', 'INF')->first();
        if ($informatika) {
            TeachingAssignment::updateOrCreate(
                [
                    'academic_year_id' => $activeYear->id,
                    'classroom_id' => $kelasX1->id,
                    'subject_id' => $informatika->id,
                ],
                ['teacher_id' => $guru->id]
            );
        }
    }
}