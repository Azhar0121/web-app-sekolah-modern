<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Subject;
use App\Models\TeachingAssignment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TeachingAssignmentController extends Controller
{
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $selectedYearId = (int) $request->integer('academic_year_id', AcademicYear::active()?->id ?? 0);

        $assignments = TeachingAssignment::with(['classroom', 'subject', 'teacher'])
            ->where('academic_year_id', $selectedYearId)
            ->get()
            ->sortBy([
                fn ($a, $b) => $a->classroom->name <=> $b->classroom->name,
                fn ($a, $b) => $a->subject->name <=> $b->subject->name,
            ]);

        return view('admin.teaching-assignments.index', compact('assignments', 'academicYears', 'selectedYearId'));
    }

    public function create(Request $request): View
    {
        return view('admin.teaching-assignments.create', $this->formOptions($request));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAssignment($request);

        TeachingAssignment::create($validated);

        return redirect()
            ->route('admin.teaching-assignments.index', ['academic_year_id' => $validated['academic_year_id']])
            ->with('success', 'Penugasan mengajar berhasil ditambahkan.');
    }

    public function edit(TeachingAssignment $teachingAssignment): View
    {
        return view('admin.teaching-assignments.edit', array_merge(
            ['assignment' => $teachingAssignment],
            $this->formOptions(null, $teachingAssignment)
        ));
    }

    public function update(Request $request, TeachingAssignment $teachingAssignment): RedirectResponse
    {
        $validated = $this->validateAssignment($request, $teachingAssignment->id);

        $teachingAssignment->update($validated);

        return redirect()
            ->route('admin.teaching-assignments.index', ['academic_year_id' => $validated['academic_year_id']])
            ->with('success', 'Penugasan mengajar berhasil diperbarui.');
    }

    public function destroy(TeachingAssignment $teachingAssignment): RedirectResponse
    {
        $yearId = $teachingAssignment->academic_year_id;
        $teachingAssignment->delete();

        return redirect()
            ->route('admin.teaching-assignments.index', ['academic_year_id' => $yearId])
            ->with('success', 'Penugasan mengajar berhasil dihapus.');
    }

    private function formOptions(?Request $request = null, ?TeachingAssignment $current = null): array
    {
        $selectedYearId = (int) ($request?->integer('academic_year_id') ?: ($current?->academic_year_id ?? AcademicYear::active()?->id ?? 0));
        $selectedClassroomId = (int) ($request?->integer('classroom_id') ?: ($current?->classroom_id ?? 0));

        return [
            'academicYears' => AcademicYear::orderByDesc('start_date')->get(),
            'selectedYearId' => $selectedYearId,
            'classrooms' => Classroom::orderBy('grade_level')->orderBy('name')->get(),
            'selectedClassroomId' => $selectedClassroomId,
            'subjects' => Subject::where('is_active', true)->orderBy('name')->get(),
            'teachers' => User::whereHas('role', fn ($q) => $q->where('slug', 'guru'))->orderBy('name')->get(),
        ];
    }

    private function validateAssignment(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'classroom_id' => ['required', 'exists:classrooms,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
            'teacher_id' => [
                'required',
                function (string $attribute, $value, \Closure $fail) {
                    if (! User::whereHas('role', fn ($q) => $q->where('slug', 'guru'))->whereKey($value)->exists()) {
                        $fail('User yang dipilih harus memiliki role Guru.');
                    }
                },
            ],
        ]);

        $duplicate = TeachingAssignment::where('academic_year_id', $validated['academic_year_id'])
            ->where('classroom_id', $validated['classroom_id'])
            ->where('subject_id', $validated['subject_id'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists();

        if ($duplicate) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'subject_id' => 'Kelas ini untuk mata pelajaran tersebut pada tahun ajaran yang sama sudah punya guru pengampu.',
            ]);
        }

        return $validated;
    }
}