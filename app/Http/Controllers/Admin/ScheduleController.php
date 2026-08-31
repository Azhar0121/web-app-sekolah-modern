<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\Schedule;
use App\Models\TeachingAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $selectedYearId = (int) $request->integer('academic_year_id', AcademicYear::active()?->id ?? 0);

        $classrooms = Classroom::where('is_active', true)
            ->orderBy('grade_level')->orderBy('name')
            ->get();

        $selectedClassroomId = (int) $request->integer('classroom_id', $classrooms->first()?->id ?? 0);

        $schedules = Schedule::with(['teachingAssignment.subject', 'teachingAssignment.teacher'])
            ->whereHas('teachingAssignment', function ($q) use ($selectedYearId, $selectedClassroomId) {
                $q->where('academic_year_id', $selectedYearId)
                    ->where('classroom_id', $selectedClassroomId);
            })
            ->get()
            ->sortBy([
                fn ($a, $b) => $a->dayOrder() <=> $b->dayOrder(),
                fn ($a, $b) => $a->start_time <=> $b->start_time,
            ]);

        return view('admin.schedules.index', compact(
            'academicYears', 'selectedYearId', 'classrooms', 'selectedClassroomId', 'schedules'
        ));
    }

    public function create(Request $request): View
    {
        return view('admin.schedules.create', $this->formOptions($request));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSchedule($request);

        Schedule::create($validated);

        $assignment = TeachingAssignment::findOrFail($validated['teaching_assignment_id']);

        return redirect()
            ->route('admin.schedules.index', [
                'academic_year_id' => $assignment->academic_year_id,
                'classroom_id' => $assignment->classroom_id,
            ])
            ->with('success', 'Jadwal pelajaran berhasil ditambahkan.');
    }

    public function edit(Schedule $schedule, Request $request): View
    {
        $schedule->load('teachingAssignment');

        return view('admin.schedules.edit', array_merge(
            ['schedule' => $schedule],
            $this->formOptions($request, $schedule->teachingAssignment)
        ));
    }

    public function update(Request $request, Schedule $schedule): RedirectResponse
    {
        $validated = $this->validateSchedule($request, $schedule->id);

        $schedule->update($validated);

        $assignment = TeachingAssignment::findOrFail($validated['teaching_assignment_id']);

        return redirect()
            ->route('admin.schedules.index', [
                'academic_year_id' => $assignment->academic_year_id,
                'classroom_id' => $assignment->classroom_id,
            ])
            ->with('success', 'Jadwal pelajaran berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule): RedirectResponse
    {
        $assignment = $schedule->teachingAssignment;
        $schedule->delete();

        return redirect()
            ->route('admin.schedules.index', [
                'academic_year_id' => $assignment->academic_year_id,
                'classroom_id' => $assignment->classroom_id,
            ])
            ->with('success', 'Jadwal pelajaran berhasil dihapus.');
    }

    private function formOptions(Request $request, ?TeachingAssignment $current = null): array
    {
        $academicYears = AcademicYear::orderByDesc('start_date')->get();
        $selectedYearId = (int) $request->integer(
            'academic_year_id',
            $current?->academic_year_id ?? AcademicYear::active()?->id ?? 0
        );

        $classrooms = Classroom::where('is_active', true)
            ->orderBy('grade_level')->orderBy('name')
            ->get();

        $selectedClassroomId = (int) $request->integer('classroom_id', $current?->classroom_id ?? 0);

        $teachingAssignments = TeachingAssignment::with(['subject', 'teacher'])
            ->where('academic_year_id', $selectedYearId)
            ->when($selectedClassroomId, fn ($q) => $q->where('classroom_id', $selectedClassroomId))
            ->get()
            ->sortBy(fn ($a) => $a->subject->name);

        return compact('academicYears', 'selectedYearId', 'classrooms', 'selectedClassroomId', 'teachingAssignments');
    }

    private function validateSchedule(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'teaching_assignment_id' => ['required', 'exists:teaching_assignments,id'],
            'day_of_week' => ['required', Rule::in(Schedule::DAY_ORDER)],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
            'room' => ['nullable', 'string', 'max:100'],
        ]);

        $assignment = TeachingAssignment::findOrFail($validated['teaching_assignment_id']);

        $this->assertNoConflict($validated, $assignment, $ignoreId);

        return $validated;
    }

    /**
     * Cegah bentrok jadwal: guru yang sama tidak boleh mengajar 2 kelas
     * berbeda di jam yang sama, dan kelas yang sama tidak boleh punya
     * 2 mapel berbeda di jam yang sama (bentrok ruang/waktu belajar).
     */
    private function assertNoConflict(array $validated, TeachingAssignment $assignment, ?int $ignoreId): void
    {
        $overlapping = Schedule::where('day_of_week', $validated['day_of_week'])
            ->where('start_time', '<', $validated['end_time'])
            ->where('end_time', '>', $validated['start_time'])
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->with('teachingAssignment')
            ->get();

        $teacherClash = $overlapping->first(
            fn ($s) => $s->teachingAssignment->teacher_id === $assignment->teacher_id
        );

        if ($teacherClash) {
            throw ValidationException::withMessages([
                'teaching_assignment_id' => 'Guru ini sudah punya jadwal mengajar lain yang bentrok pada hari & jam tersebut.',
            ]);
        }

        $classroomClash = $overlapping->first(
            fn ($s) => $s->teachingAssignment->classroom_id === $assignment->classroom_id
        );

        if ($classroomClash) {
            throw ValidationException::withMessages([
                'teaching_assignment_id' => 'Kelas ini sudah punya jadwal mata pelajaran lain yang bentrok pada hari & jam tersebut.',
            ]);
        }
    }
}
