<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Grade;
use App\Models\GradeWeight;
use App\Models\Semester;
use App\Models\TeachingAssignment;
use App\Services\GradeCalculator;
use Illuminate\Http\Request;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request): View
    {
        $student = auth()->user();
        $classroom = $student->currentClassroom();
        $academicYear = AcademicYear::active();

        $semesters = $academicYear
            ? Semester::where('academic_year_id', $academicYear->id)->get()
            : collect();

        $semester = $this->resolveSemester($request, $semesters);

        $assignments = ($classroom && $academicYear)
            ? TeachingAssignment::with('subject')
                ->where('classroom_id', $classroom->id)
                ->where('academic_year_id', $academicYear->id)
                ->get()
                ->sortBy(fn ($a) => $a->subject->name)
            : collect();

        $recaps = $assignments->map(function (TeachingAssignment $assignment) use ($semester, $student) {
            $weight = $semester
                ? GradeWeight::where('teaching_assignment_id', $assignment->id)
                    ->where('semester_id', $semester->id)
                    ->first()
                : null;

            $grades = $semester
                ? Grade::where('teaching_assignment_id', $assignment->id)
                    ->where('semester_id', $semester->id)
                    ->where('student_id', $student->id)
                    ->get()
                : collect();

            $taskAverage = $semester
                ? GradeCalculator::taskAverage($assignment->id, $semester->id, $student->id)
                : null;

            return [
                'subject' => $assignment->subject,
                'calc' => GradeCalculator::calculate($grades, $weight, $taskAverage),
            ];
        });

        return view('siswa.grades.index', compact('classroom', 'semesters', 'semester', 'recaps'));
    }

    private function resolveSemester(Request $request, $semesters): ?Semester
    {
        $selectedId = $request->integer('semester_id');

        if ($selectedId && $match = $semesters->firstWhere('id', $selectedId)) {
            return $match;
        }

        return $semesters->firstWhere('is_active', true) ?? $semesters->first();
    }
}