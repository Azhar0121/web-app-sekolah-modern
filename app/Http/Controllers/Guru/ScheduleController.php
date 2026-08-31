<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(): View
    {
        $activeYear = AcademicYear::active();

        $schedules = $activeYear
            ? Schedule::with(['teachingAssignment.classroom', 'teachingAssignment.subject'])
                ->whereHas('teachingAssignment', function ($q) use ($activeYear) {
                    $q->where('academic_year_id', $activeYear->id)
                        ->where('teacher_id', auth()->id());
                })
                ->get()
                ->sortBy([
                    fn ($a, $b) => $a->dayOrder() <=> $b->dayOrder(),
                    fn ($a, $b) => $a->start_time <=> $b->start_time,
                ])
                ->groupBy('day_of_week')
            : collect();

        return view('guru.schedule.index', compact('activeYear', 'schedules'));
    }
}
