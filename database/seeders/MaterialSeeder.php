<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Material;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = AcademicYear::active();
        $kelasX1 = Classroom::where('name', 'X-1')->first();
        $matematika = Subject::where('code', 'MTK')->first();

        if (! $activeYear || ! $kelasX1 || ! $matematika) {
            return;
        }

        $assignment = TeachingAssignment::where('academic_year_id', $activeYear->id)
            ->where('classroom_id', $kelasX1->id)
            ->where('subject_id', $matematika->id)
            ->first();

        if (! $assignment) {
            return;
        }

        Material::updateOrCreate(
            ['teaching_assignment_id' => $assignment->id, 'title' => 'Pengantar Bilangan Bulat'],
            [
                'description' => 'Ringkasan materi bab 1: operasi hitung bilangan bulat dan sifat-sifatnya.',
                'link' => 'https://www.youtube.com/results?search_query=bilangan+bulat',
                'is_published' => true,
            ]
        );
    }
}
