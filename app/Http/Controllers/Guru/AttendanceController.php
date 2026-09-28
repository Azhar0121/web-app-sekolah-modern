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
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class AttendanceController extends Controller
{
    private const QR_TTL_MINUTES = 5;

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

    public function showQr(AttendanceSession $attendanceSession): View
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        abort_if(! $attendanceSession->isOpen(), 403, 'Sesi presensi sudah ditutup.');

        // Generate QR token baru setiap kali halaman ini dibuka
        $token = $attendanceSession->generateSessionQr(self::QR_TTL_MINUTES);

        // Buat URL yang akan di-encode ke QR (siswa akan diarahkan ke URL ini)
        $scanUrl = route('siswa.attendance.scan') . '?token=' . $token;

        // Generate QR sebagai SVG
        $qrSvg = QrCode::size(350)->style('round')->eye('circle')->generate($scanUrl);

        $attendanceSession->load('schedule.teachingAssignment.classroom', 'schedule.teachingAssignment.subject');

        return view('guru.attendance.qr-display', [
            'attendanceSession' => $attendanceSession,
            'qrSvg'             => $qrSvg,
            'ttlMinutes'        => self::QR_TTL_MINUTES,
            'expiresAt'         => $attendanceSession->session_qr_expires_at,
            'scanUrl'           => $scanUrl,
        ]);
    }

    public function refreshQr(AttendanceSession $attendanceSession): JsonResponse
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        if (! $attendanceSession->isOpen()) {
            return response()->json(['success' => false, 'message' => 'Sesi sudah ditutup.'], 422);
        }

        $token   = $attendanceSession->generateSessionQr(self::QR_TTL_MINUTES);
        $scanUrl = route('siswa.attendance.scan') . '?token=' . $token;

        $qrSvg = QrCode::size(350)->style('round')->eye('circle')->generate($scanUrl);

        return response()->json([
            'success'    => true,
            'qrSvg'      => (string) $qrSvg,
            'expiresAt'  => $attendanceSession->fresh()->session_qr_expires_at->toIso8601String(),
            'ttlSeconds' => self::QR_TTL_MINUTES * 60,
        ]);
    }

    public function updateStatus(AttendanceSession $attendanceSession, User $student, Request $request): RedirectResponse
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        $validated = $request->validate([
            'status' => ['required', 'in:hadir,izin,sakit,alpha'],
            'note'   => ['nullable', 'string', 'max:255'],
        ]);

        Attendance::updateOrCreate(
            ['attendance_session_id' => $attendanceSession->id, 'student_id' => $student->id],
            [
                'status'      => $validated['status'],
                'note'        => $validated['note'] ?? null,
                'scanned_at'  => null,
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
                'student_id'            => $studentId,
                'status'                => 'alpha',
            ]);
        }

        $attendanceSession->update(['closed_at' => now()]);

        return redirect()->route('guru.attendance.index')
            ->with('success', 'Sesi presensi berhasil ditutup. Siswa yang belum tercatat otomatis ditandai Alpha.');
    }

    public function hadirCount(AttendanceSession $attendanceSession): JsonResponse
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        $count = Attendance::where('attendance_session_id', $attendanceSession->id)
            ->where('status', 'hadir')
            ->count();

        return response()->json(['count' => $count]);
    }

    public function reopen(AttendanceSession $attendanceSession): RedirectResponse
    {
        $this->authorizeTeacher($attendanceSession->schedule);

        $attendanceSession->update(['closed_at' => null]);

        return back()->with('success', 'Sesi presensi berhasil dibuka kembali.');
    }

    private function authorizeTeacher(Schedule $schedule): void
    {
        $schedule->loadMissing('teachingAssignment');

        Gate::allowIf(fn () => $schedule->teachingAssignment->teacher_id === auth()->id());
    }
}