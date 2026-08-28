<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $activeYear = AcademicYear::active();

        $assignments = $activeYear
            ? auth()->user()->teachingAssignments()
                ->with(['classroom', 'subject'])
                ->where('academic_year_id', $activeYear->id)
                ->get()
                ->sortBy(fn ($a) => $a->classroom->name)
            : collect();

        return view('guru.dashboard', compact('activeYear', 'assignments'));
    }
}
