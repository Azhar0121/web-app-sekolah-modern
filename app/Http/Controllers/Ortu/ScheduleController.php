<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\View\View;

class ScheduleController extends Controller
{
    public function index(User $student): View
    {
        $this->authorizeChild($student);

        $activeYear = AcademicYear::active();
        $classroom  = $student->currentClassroom();

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

        return view('ortu.schedule.index', compact('student', 'activeYear', 'classroom', 'schedules'));
    }

    private function authorizeChild(User $student): void
    {
        abort_unless(
            auth()->user()->children()->where('users.id', $student->id)->exists(),
            403,
            'Siswa ini bukan anak yang tertaut dengan akun Anda.'
        );
    }
}
