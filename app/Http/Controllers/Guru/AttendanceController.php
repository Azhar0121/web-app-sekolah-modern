<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\ClassroomStudent;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $activeYear = AcademicYear::active();
        $todayName = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][now()->dayOfWeek];

        $schedules = $activeYear
            ? Schedule::with(['teachingAssignment.classroom', 'teachingAssignment.subject'])
                ->whereHas('teachingAssignment', function ($q) use ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id)
                        ->where('teacher_id', auth()->id());
                })
                ->where('day_of_week', $todayName)
                ->get()
                ->sortBy('start_time')
                ->map(function (Schedule $schedule) {
                    $schedule->todaySession = AttendanceSession::where('schedule_id', $schedule->id)
                        ->whereDate('date', now()->toDateString())
                        ->first();

                    return $schedule;
                })
            : collect();

        return view('guru.attendance.index', compact('activeYear', 'todayName', 'schedules'));
    }

    public function session(Schedule $schedule): View|RedirectResponse
    {
        $this->authorizeTeacher($schedule);

        $attendanceSession = AttendanceSession::firstOrCreate(
            ['schedule_id' => $schedule->id, 'date' => now()->toDateString()],
            ['opened_by' => auth()->id(), 'opened_at' => now()]
        );

        $schedule->load('teachingAssignment.classroom', 'teachingAssignment.subject');

        $activeYear = AcademicYear::active();

        $students = ClassroomStudent::with('student')
            ->where('academic_year_id', $activeYear?->id)
            ->where('classroom_id', $schedule->teachingAssignment->classroom_id)
            ->get()
            ->pluck('student')
            ->sortBy('name');

        $attendances = Attendance::where('attendance_session_id', $attendanceSession->id)
            ->get()
            ->keyBy('student_id');

        return view('guru.attendance.session', compact('schedule', 'attendanceSession', 'students', 'attendances'));
    }

    public function scan(AttendanceSession $attendanceSession, Request $request): JsonResponse
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        if (! $attendanceSession->isOpen()) {
            return response()->json(['success' => false, 'message' => 'Sesi presensi ini sudah ditutup.'], 422);
        }

        $request->validate(['token' => ['required', 'string']]);

        $student = User::where('qr_token', $request->token)
            ->whereHas('role', fn ($q) => $q->where('slug', 'siswa'))
            ->first();

        if (! $student) {
            return response()->json(['success' => false, 'message' => 'QR tidak dikenali / bukan kartu pelajar yang valid.'], 404);
        }

        $enrolled = ClassroomStudent::where('classroom_id', $attendanceSession->schedule->teachingAssignment->classroom_id)
            ->where('student_id', $student->id)
            ->exists();

        if (! $enrolled) {
            return response()->json([
                'success' => false,
                'message' => "{$student->name} bukan siswa di kelas ini.",
            ], 422);
        }

        $existing = Attendance::where('attendance_session_id', $attendanceSession->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing && $existing->status === 'hadir') {
            return response()->json([
                'success' => true,
                'already' => true,
                'message' => "{$student->name} sudah tercatat hadir sebelumnya.",
                'student_name' => $student->name,
            ]);
        }

        Attendance::updateOrCreate(
            ['attendance_session_id' => $attendanceSession->id, 'student_id' => $student->id],
            ['status' => 'hadir', 'scanned_at' => now(), 'recorded_by' => null, 'note' => null]
        );

        return response()->json([
            'success' => true,
            'already' => false,
            'message' => "{$student->name} berhasil tercatat hadir.",
            'student_name' => $student->name,
        ]);
    }

    public function updateStatus(AttendanceSession $attendanceSession, User $student, Request $request): RedirectResponse
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        $validated = $request->validate([
            'status' => ['required', 'in:hadir,izin,sakit,alpha'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        Attendance::updateOrCreate(
            ['attendance_session_id' => $attendanceSession->id, 'student_id' => $student->id],
            [
                'status' => $validated['status'],
                'note' => $validated['note'] ?? null,
                'scanned_at' => null,
                'recorded_by' => auth()->id(),
            ]
        );

        return back()->with('success', "Status kehadiran {$student->name} berhasil diperbarui.");
    }

    public function close(AttendanceSession $attendanceSession): RedirectResponse
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        $activeYear = AcademicYear::active();

        $studentIds = ClassroomStudent::where('academic_year_id', $activeYear?->id)
            ->where('classroom_id', $attendanceSession->schedule->teachingAssignment->classroom_id)
            ->pluck('student_id');

        $alreadyRecorded = Attendance::where('attendance_session_id', $attendanceSession->id)
            ->pluck('student_id');

        foreach ($studentIds->diff($alreadyRecorded) as $studentId) {
            Attendance::create([
                'attendance_session_id' => $attendanceSession->id,
                'student_id' => $studentId,
                'status' => 'alpha',
            ]);
        }

        $attendanceSession->update(['closed_at' => now()]);

        return redirect()->route('guru.attendance.index')
            ->with('success', 'Sesi presensi berhasil ditutup. Siswa yang belum tercatat otomatis ditandai Alpha.');
    }

    private function authorizeTeacher(Schedule $schedule): void
    {
        $schedule->loadMissing('teachingAssignment');

        Gate::allowIf(fn () => $schedule->teachingAssignment->teacher_id === auth()->id());
    }
}