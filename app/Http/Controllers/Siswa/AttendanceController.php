<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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
            'izin' => Attendance::where('student_id', auth()->id())->where('status', 'izin')->count(),
            'sakit' => Attendance::where('student_id', auth()->id())->where('status', 'sakit')->count(),
            'alpha' => Attendance::where('student_id', auth()->id())->where('status', 'alpha')->count(),
        ];

        return view('siswa.attendance.index', compact('attendances', 'recap'));
    }
}
