<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\Task;
use App\Models\TeachingAssignment;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $student = auth()->user();
        $classroom = $student->currentClassroom();
        $activeYear = AcademicYear::active();

        $today = now()->locale('id');
        $dayName = ucfirst($today->translatedFormat('l'));

        $todaySchedules = ($classroom && $activeYear)
            ? Schedule::with(['teachingAssignment.subject', 'teachingAssignment.teacher'])
                ->whereHas('teachingAssignment', function ($q) use ($classroom, $activeYear) {
                    $q->where('classroom_id', $classroom->id)
                      ->where('academic_year_id', $activeYear->id);
                })
                ->where('day_of_week', $dayName)
                ->orderBy('start_time')
                ->get()
            : collect();

        $studentTasks = collect();
        if ($classroom && $activeYear) {
            $assignmentIds = TeachingAssignment::where('classroom_id', $classroom->id)
                ->where('academic_year_id', $activeYear->id)
                ->pluck('id');

            $studentTasks = Task::with('teachingAssignment.subject')
                ->whereIn('teaching_assignment_id', $assignmentIds)
                ->where('is_published', true)
                ->orderBy('deadline')
                ->take(4)
                ->get();
        }

        $attendanceStats = [
            'hadir' => Attendance::where('student_id', $student->id)->where('status', 'hadir')->count(),
            'izin'  => Attendance::where('student_id', $student->id)->where('status', 'izin')->count(),
            'sakit' => Attendance::where('student_id', $student->id)->where('status', 'sakit')->count(),
            'alpha' => Attendance::where('student_id', $student->id)->where('status', 'alpha')->count(),
        ];

        return view('siswa.dashboard', compact(
            'student',
            'classroom',
            'activeYear',
            'today',
            'dayName',
            'todaySchedules',
            'studentTasks',
            'attendanceStats'
        ));
    }
}