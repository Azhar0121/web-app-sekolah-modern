<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentPlacementController extends Controller
{
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $selectedYearId = (int) $request->integer('academic_year_id', AcademicYear::active()?->id ?? 0);

        $classrooms = Classroom::withCount([
                'enrollments as students_count' => fn ($q) => $q->where('academic_year_id', $selectedYearId),
            ])
            ->orderBy('grade_level')
            ->orderBy('name')
            ->get();

        return view('admin.student-placements.index', compact('classrooms', 'academicYears', 'selectedYearId'));
    }

    public function manage(Request $request, Classroom $classroom): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $selectedYearId = (int) $request->integer('academic_year_id', AcademicYear::active()?->id ?? 0);

        $enrollments = ClassroomStudent::with('student')
            ->where('classroom_id', $classroom->id)
            ->where('academic_year_id', $selectedYearId)
            ->get();

        $enrolledStudentIds = ClassroomStudent::where('academic_year_id', $selectedYearId)
            ->pluck('student_id');

        // Siswa yang belum terdaftar di kelas manapun pada tahun ajaran terpilih
        $availableStudents = User::whereHas('role', fn ($q) => $q->where('slug', 'siswa'))
            ->whereNotIn('id', $enrolledStudentIds)
            ->orderBy('name')
            ->get();

        return view('admin.student-placements.manage', compact(
            'classroom', 'academicYears', 'selectedYearId', 'enrollments', 'availableStudents'
        ));
    }

    public function store(Request $request, Classroom $classroom): RedirectResponse
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'student_id' => [
                'required',
                function (string $attribute, $value, \Closure $fail) {
                    if (! User::whereHas('role', fn ($q) => $q->where('slug', 'siswa'))->whereKey($value)->exists()) {
                        $fail('User yang dipilih harus memiliki role Siswa.');
                    }
                },
            ],
        ]);

        $alreadyEnrolled = ClassroomStudent::where('academic_year_id', $validated['academic_year_id'])
            ->where('student_id', $validated['student_id'])
            ->exists();

        if ($alreadyEnrolled) {
            return back()->with('error', 'Siswa ini sudah terdaftar di kelas lain pada tahun ajaran yang sama.');
        }

        ClassroomStudent::create([
            'academic_year_id' => $validated['academic_year_id'],
            'classroom_id' => $classroom->id,
            'student_id' => $validated['student_id'],
        ]);

        return redirect()
            ->route('admin.student-placements.manage', [$classroom, 'academic_year_id' => $validated['academic_year_id']])
            ->with('success', 'Siswa berhasil ditambahkan ke kelas.');
    }

    public function destroy(ClassroomStudent $classroomStudent): RedirectResponse
    {
        $classroom = $classroomStudent->classroom_id;
        $yearId = $classroomStudent->academic_year_id;
        $classroomStudent->delete();

        return redirect()
            ->route('admin.student-placements.manage', ['classroom' => $classroom, 'academic_year_id' => $yearId])
            ->with('success', 'Siswa berhasil dikeluarkan dari kelas.');
    }
}
