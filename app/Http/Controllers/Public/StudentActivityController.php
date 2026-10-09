<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\OsisActivity;
use App\Models\OsisMember;
use App\Models\Setting;
use App\Models\StudentAchievement;
use Illuminate\View\View;

class StudentActivityController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();

        $extracurriculars = Extracurricular::where('is_active', true)
            ->orderBy('order')
            ->orderBy('name')
            ->get();

        $achievements = StudentAchievement::where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('year')
            ->get();

        $osisMembers = OsisMember::where('is_active', true)
            ->orderBy('order')
            ->get();

        $osisActivities = OsisActivity::where('is_active', true)
            ->orderBy('order')
            ->orderByDesc('date')
            ->get();

        return view('public.student-activity', compact(
            'settings',
            'extracurriculars',
            'achievements',
            'osisMembers',
            'osisActivities'
        ));
    }
}
