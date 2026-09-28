<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AcademicYear;
use Illuminate\View\View;

class QrCodeController extends Controller
{

    public function show(): View
    {
        $student   = auth()->user()->load('studentProfile', 'role');
        $classroom = $student->currentClassroom();
        $activeYear = AcademicYear::active();

        $attendanceSummary = [
            'hadir' => Attendance::where('student_id', $student->id)->where('status', 'hadir')->count(),
            'alpha' => Attendance::where('student_id', $student->id)->where('status', 'alpha')->count(),
            'izin'  => Attendance::where('student_id', $student->id)->where('status', 'izin')->count(),
            'sakit' => Attendance::where('student_id', $student->id)->where('status', 'sakit')->count(),
        ];

        return view('siswa.qr-code.show', [
            'student'           => $student,
            'classroom'         => $classroom,
            'activeYear'        => $activeYear,
            'attendanceSummary' => $attendanceSummary,
        ]);
    }

    public function refresh(): \Illuminate\Http\JsonResponse
    {
        return response()->json(['status' => 'ok']);
    }
}