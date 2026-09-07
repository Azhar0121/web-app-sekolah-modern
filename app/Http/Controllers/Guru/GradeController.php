<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\ClassroomStudent;
use App\Models\Grade;
use App\Models\GradeWeight;
use App\Models\Semester;
use App\Models\TeachingAssignment;
use App\Services\GradeCalculator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GradeController extends Controller
{
    public function index(Request $request, TeachingAssignment $teachingAssignment): View
    {
        $this->authorizeAssignment($teachingAssignment);

        $semesters = Semester::where('academic_year_id', $teachingAssignment->academic_year_id)->get();
        $semester = $this->resolveSemester($request, $semesters);

        $weight = GradeWeight::where('teaching_assignment_id', $teachingAssignment->id)
            ->where('semester_id', $semester?->id)
            ->first();

        $students = ClassroomStudent::with('student')
            ->where('academic_year_id', $teachingAssignment->academic_year_id)
            ->where('classroom_id', $teachingAssignment->classroom_id)
            ->get()
            ->pluck('student')
            ->sortBy('name');

        $grades = $semester
            ? Grade::where('teaching_assignment_id', $teachingAssignment->id)
                ->where('semester_id', $semester->id)
                ->orderByDesc('created_at')
                ->get()
                ->groupBy('student_id')
            : collect();

        $recaps = $students->mapWithKeys(function ($student) use ($grades, $weight) {
            $studentGrades = $grades->get($student->id, collect());

            return [$student->id => GradeCalculator::calculate($studentGrades, $weight)];
        });

        return view('guru.grades.index', compact(
            'teachingAssignment', 'semesters', 'semester', 'weight', 'students', 'grades', 'recaps'
        ));
    }

    public function updateWeight(Request $request, TeachingAssignment $teachingAssignment): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);

        $validated = $request->validate([
            'semester_id' => ['required', 'exists:semesters,id'],
            'tugas_weight' => ['required', 'integer', 'min:0', 'max:100'],
            'uh_weight' => ['required', 'integer', 'min:0', 'max:100'],
            'uts_weight' => ['required', 'integer', 'min:0', 'max:100'],
            'uas_weight' => ['required', 'integer', 'min:0', 'max:100'],
        ]);

        $total = $validated['tugas_weight'] + $validated['uh_weight'] + $validated['uts_weight'] + $validated['uas_weight'];

        if ($total !== 100) {
            throw ValidationException::withMessages([
                'tugas_weight' => "Total bobot harus 100%, saat ini {$total}%.",
            ]);
        }

        GradeWeight::updateOrCreate(
            ['teaching_assignment_id' => $teachingAssignment->id, 'semester_id' => $validated['semester_id']],
            $validated
        );

        return back()->with('success', 'Bobot penilaian berhasil disimpan.')
            ->withInput(['semester_id' => $validated['semester_id']]);
    }

    public function storeBatch(Request $request, TeachingAssignment $teachingAssignment): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);

        $validated = $request->validate([
            'semester_id' => ['required', 'exists:semesters,id'],
            'category' => ['required', 'in:tugas,uh,uts,uas'],
            'label' => ['required', 'string', 'max:100'],
            'scores' => ['required', 'array'],
            'scores.*' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ]);

        $enrolledIds = ClassroomStudent::where('academic_year_id', $teachingAssignment->academic_year_id)
            ->where('classroom_id', $teachingAssignment->classroom_id)
            ->pluck('student_id');

        $count = 0;

        foreach ($validated['scores'] as $studentId => $score) {
            if ($score === null || $score === '') {
                continue; // guru bisa kosongkan siswa yang belum dinilai/absen
            }

            if (! $enrolledIds->contains((int) $studentId)) {
                continue; // jaga-jaga terhadap input yang tidak sah
            }

            Grade::create([
                'teaching_assignment_id' => $teachingAssignment->id,
                'semester_id' => $validated['semester_id'],
                'student_id' => $studentId,
                'category' => $validated['category'],
                'label' => $validated['label'],
                'score' => $score,
                'recorded_by' => auth()->id(),
            ]);

            $count++;
        }

        return redirect()
            ->route('guru.teaching-assignments.grades.index', [$teachingAssignment, 'semester_id' => $validated['semester_id']])
            ->with('success', "Nilai \"{$validated['label']}\" berhasil disimpan untuk {$count} siswa.");
    }

    public function destroy(TeachingAssignment $teachingAssignment, Grade $grade): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);
        abort_unless($grade->teaching_assignment_id === $teachingAssignment->id, 404);

        $semesterId = $grade->semester_id;
        $grade->delete();

        return redirect()
            ->route('guru.teaching-assignments.grades.index', [$teachingAssignment, 'semester_id' => $semesterId])
            ->with('success', 'Nilai berhasil dihapus.');
    }

    private function resolveSemester(Request $request, $semesters): ?Semester
    {
        $selectedId = $request->integer('semester_id');

        if ($selectedId && $match = $semesters->firstWhere('id', $selectedId)) {
            return $match;
        }

        return $semesters->firstWhere('is_active', true) ?? $semesters->first();
    }

    private function authorizeAssignment(TeachingAssignment $teachingAssignment): void
    {
        abort_unless($teachingAssignment->teacher_id === auth()->id(), 403, 'Anda tidak mengampu kelas/mapel ini.');
    }
}
