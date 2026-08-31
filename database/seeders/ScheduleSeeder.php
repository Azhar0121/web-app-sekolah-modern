<?php

namespace Database\Seeders;

use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use Illuminate\Database\Seeder;

class ScheduleSeeder extends Seeder
{
    public function run(): void
    {
        $activeYear = AcademicYear::active();
        $kelasX1 = Classroom::where('name', 'X-1')->first();

        if (! $activeYear || ! $kelasX1) {
            return; // dependency belum ada, skip supaya seeder lain tidak ikut gagal
        }

        $matematika = Subject::where('code', 'MTK')->first();
        $informatika = Subject::where('code', 'INF')->first();

        $entries = [];

        if ($matematika) {
            $assignment = TeachingAssignment::where('academic_year_id', $activeYear->id)
                ->where('classroom_id', $kelasX1->id)
                ->where('subject_id', $matematika->id)
                ->first();

            if ($assignment) {
                $entries[] = [
                    'teaching_assignment_id' => $assignment->id,
                    'day_of_week' => 'Senin',
                    'start_time' => '07:00',
                    'end_time' => '08:30',
                    'room' => 'Ruang X-1',
                ];
            }
        }

        if ($informatika) {
            $assignment = TeachingAssignment::where('academic_year_id', $activeYear->id)
                ->where('classroom_id', $kelasX1->id)
                ->where('subject_id', $informatika->id)
                ->first();

            if ($assignment) {
                $entries[] = [
                    'teaching_assignment_id' => $assignment->id,
                    'day_of_week' => 'Selasa',
                    'start_time' => '09:00',
                    'end_time' => '10:30',
                    'room' => 'Lab Komputer',
                ];
            }
        }

        foreach ($entries as $entry) {
            Schedule::updateOrCreate(
                [
                    'teaching_assignment_id' => $entry['teaching_assignment_id'],
                    'day_of_week' => $entry['day_of_week'],
                    'start_time' => $entry['start_time'],
                ],
                $entry
            );
        }
    }
}
