<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\ClassroomStudent;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function index(Request $request): View
    {
        $activeYear = AcademicYear::active();

        $classrooms = $activeYear
            ? TeachingAssignment::with('classroom')
                ->where('teacher_id', auth()->id())
                ->where('academic_year_id', $activeYear->id)
                ->get()
                ->pluck('classroom')
                ->unique('id')
                ->sortBy('name')
                ->values()
            : collect();

        $selectedClassroomId = $request->integer('classroom_id') ?: $classrooms->first()?->id;
        $selectedClassroom   = $classrooms->firstWhere('id', $selectedClassroomId);

        $students = collect();
        if ($activeYear && $selectedClassroom) {
            $students = ClassroomStudent::with('student.studentProfile')
                ->where('academic_year_id', $activeYear->id)
                ->where('classroom_id', $selectedClassroom->id)
                ->get()
                ->pluck('student')
                ->sortBy('name')
                ->values();
        }

        return view('guru.student-profile.index', compact(
            'classrooms', 'selectedClassroom', 'students', 'activeYear'
        ));
    }

    public function show(User $student): View
    {
        $this->authorizeAccess($student);

        $profile  = $student->studentProfile;
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