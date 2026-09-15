<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\View\View;

class AttendanceController extends Controller
{
    public function index(User $student): View
    {
        $this->authorizeChild($student);

        $attendances = Attendance::with(['session.schedule.teachingAssignment.subject'])
            ->where('student_id', $student->id)
            ->get()
            ->sortByDesc(fn ($a) => $a->session->date)
            ->groupBy(fn ($a) => $a->session->date->format('Y-m-d'));

        $recap = [
            'hadir' => Attendance::where('student_id', $student->id)->where('status', 'hadir')->count(),
            'izin' => Attendance::where('student_id', $student->id)->where('status', 'izin')->count(),
            'sakit' => Attendance::where('student_id', $student->id)->where('status', 'sakit')->count(),
            'alpha' => Attendance::where('student_id', $student->id)->where('status', 'alpha')->count(),
        ];

        return view('ortu.attendance.index', compact('student', 'attendances', 'recap'));
    }

    private function authorizeChild(User $student): void
    {
        abort_unless(
            auth()->user()->children()->where('users.id', $student->id)->exists(),
            403,
            'Siswa ini bukan anak yang tertaut ke akun Anda.'
        );
    }
}