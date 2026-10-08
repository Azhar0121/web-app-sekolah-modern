<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceSession;
use App\Models\ClassroomStudent;
use App\Models\AcademicYear;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(): View
    {
        $attendances = Attendance::with(['session.schedule.teachingAssignment.subject'])
            ->where('student_id', auth()->id())
            ->get()
            ->sortByDesc(fn ($a) => $a->session->date)
            ->groupBy(fn ($a) => $a->session->date->format('Y-m-d'));

        $recap = [
            'hadir' => Attendance::where('student_id', auth()->id())->where('status', 'hadir')->count(),
            'izin'  => Attendance::where('student_id', auth()->id())->where('status', 'izin')->count(),
            'sakit' => Attendance::where('student_id', auth()->id())->where('status', 'sakit')->count(),
            'alpha' => Attendance::where('student_id', auth()->id())->where('status', 'alpha')->count(),
        ];

        return view('siswa.attendance.index', compact('attendances', 'recap'));
    }

    public function scan(Request $request): View
    {
        // Jika ada token dari URL (dari QR yang di-scan), kirim ke view
        $tokenFromUrl = $request->query('token');

        return view('siswa.attendance.scan', compact('tokenFromUrl'));
    }

    public function submitScan(Request $request): JsonResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
        ]);

        $student = auth()->user();

        $session = AttendanceSession::where('session_qr_token', $request->token)->first();

        if (! $session) {
            return response()->json([
                'success' => false,
                'message' => 'QR tidak dikenali. Pastikan Anda scan QR dari guru yang benar.',
            ], 404);
        }

        if (! $session->isOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Sesi presensi sudah ditutup oleh guru.',
            ], 422);
        }

        if (! $session->isSessionQrValid()) {
            return response()->json([
                'success' => false,
                'message' => 'QR sudah kadaluarsa. Minta guru untuk me-refresh QR dan coba lagi.',
            ], 422);
        }

        $activeYear = AcademicYear::active();
        $classroomId = $session->schedule->loadMissing('teachingAssignment')->teachingAssignment->classroom_id;

        $enrolled = ClassroomStudent::where('classroom_id', $classroomId)
            ->where('student_id', $student->id)
            ->when($activeYear, fn ($q) => $q->where('academic_year_id', $activeYear->id))
            ->exists();

        if (! $enrolled) {
            return response()->json([
                'success' => false,
                'message' => 'Anda bukan siswa di kelas ini. Hubungi guru jika ada kesalahan.',
            ], 403);
        }

        $existing = Attendance::where('attendance_session_id', $session->id)
            ->where('student_id', $student->id)
            ->first();

        if ($existing && $existing->status === 'hadir') {
            return response()->json([
                'success'  => true,
                'already'  => true,
                'message'  => 'Anda sudah tercatat hadir untuk sesi ini.',
                'subject'  => $session->schedule->teachingAssignment->subject->name ?? '-',
                'classroom' => $session->schedule->teachingAssignment->classroom->name ?? '-',
            ]);
        }

        $attendance = Attendance::updateOrCreate(
            ['attendance_session_id' => $session->id, 'student_id' => $student->id],
            ['status' => 'hadir', 'scanned_at' => now(), 'recorded_by' => null, 'note' => null]
        );

        // Notifikasi ke Orang Tua
        foreach ($student->parents as $parent) {
            $parent->notify(new \App\Notifications\AttendanceRecordedNotification($attendance));
        }

        return response()->json([
            'success'   => true,
            'already'   => false,
            'message'   => 'Presensi berhasil! Anda tercatat hadir.',
            'subject'   => $session->schedule->teachingAssignment->subject->name ?? '-',
            'classroom' => $session->schedule->teachingAssignment->classroom->name ?? '-',
        ]);
    }
}