<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $activeYear = AcademicYear::active();
        $classroom = auth()->user()->currentClassroom();

        $schedules = ($activeYear && $classroom)
            ? Schedule::with(['teachingAssignment.subject', 'teachingAssignment.teacher'])
                ->whereHas('teachingAssignment', function ($q) use ($activeYear, $classroom) {
                    $q->where('academic_year_id', $activeYear->id)
                        ->where('classroom_id', $classroom->id);
                })
                ->get()
                ->sortBy([
                    fn ($a, $b) => $a->dayOrder() <=> $b->dayOrder(),
                    fn ($a, $b) => $a->start_time <=> $b->start_time,
                ])
                ->groupBy('day_of_week')
            : collect();

        return view('siswa.schedule.index', compact('activeYear', 'classroom', 'schedules'));
    }
}
