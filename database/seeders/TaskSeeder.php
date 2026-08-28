<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\Task;
use App\Models\TaskSubmission;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Database\Seeder;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = AcademicYear::active();
        $kelasX1 = Classroom::where('name', 'X-1')->first();
        $matematika = Subject::where('code', 'MTK')->first();
        $siswa = User::where('email', 'siswa@sekolah.test')->first();

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

        $task = Task::updateOrCreate(
            ['teaching_assignment_id' => $assignment->id, 'title' => 'Latihan Soal Bilangan Bulat'],
            [
                // 'description' => "Kerjakan soal nomor 1-10 halaman 15 buku paket.\nKumpulkan dalam bentuk foto/scan atau ketikan.",
                'deadline' => now()->addDays(7),
                'is_published' => true,
            ]
        );

        // Contoh 1 pengumpulan siswa dummy
        if ($siswa) {
            TaskSubmission::updateOrCreate(
                ['task_id' => $task->id, 'student_id' => $siswa->id],
                [
                    'note' => 'Jawaban terlampir, mohon dikoreksi.',
                    'submitted_at' => now(),
                ]
            );
        }
    }
}