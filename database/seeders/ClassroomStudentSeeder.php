<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\User;
use Illuminate\Database\Seeder;

class ClassroomStudentSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = AcademicYear::active();
        $siswa = User::where('email', 'siswa@sekolah.test')->first();
        $kelasX1 = Classroom::where('name', 'X-1')->first();

        if (! $activeYear || ! $siswa || ! $kelasX1) {
            return;
        }

        ClassroomStudent::updateOrCreate(
            [
                'academic_year_id' => $activeYear->id,
                'student_id' => $siswa->id,
            ],
            ['classroom_id' => $kelasX1->id]
        );
    }
}
