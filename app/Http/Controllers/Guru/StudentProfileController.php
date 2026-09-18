<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassroomStudent;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function show(User $student): View
    {
        $this->authorizeAccess($student);

        $profile = $student->studentProfile;
        $classroom = $student->currentClassroom();

        return view('guru.student-profile.show', compact('student', 'profile', 'classroom'));
    }

    private function authorizeAccess(User $student): void
    {
        $activeYear = AcademicYear::active();

        $classroomIds = $activeYear
            ? TeachingAssignment::where('teacher_id', auth()->id())
                ->where('academic_year_id', $activeYear->id)
                ->pluck('classroom_id')
            : collect();

        $isTaught = $activeYear && ClassroomStudent::where('student_id', $student->id)
            ->where('academic_year_id', $activeYear->id)
            ->whereIn('classroom_id', $classroomIds)
            ->exists();

        abort_unless($isTaught, 403, 'Siswa ini bukan bagian dari kelas yang Anda ampu.');
    }
}