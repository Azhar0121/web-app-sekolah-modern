<?php

namespace App\Http\Controllers\Admin\StudentActivity;

use App\Http\Controllers\Controller;
use App\Models\Extracurricular;
use App\Models\OsisActivity;
use App\Models\OsisMember;
use App\Models\StudentAchievement;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StudentActivityHubController extends Controller
{
    public function index(Request $request): View
    {
        $currentTab = $request->query('tab', 'osis');

        $osisActivities = OsisActivity::orderBy('order')
            ->orderByDesc('date')
            ->paginate(10, ['*'], 'activities_page')
            ->withQueryString();

        $osisMembers = OsisMember::orderBy('order')
            ->orderBy('id')
            ->paginate(12, ['*'], 'members_page')
            ->withQueryString();

        $extracurriculars = Extracurricular::orderBy('order')
            ->orderBy('name')
            ->paginate(12, ['*'], 'ekskul_page')
            ->withQueryString();

        $achievements = StudentAchievement::orderBy('order')
            ->orderByDesc('year')
            ->paginate(10, ['*'], 'prestasi_page')
            ->withQueryString();

        return view('admin.student-activity.index', compact(
            'currentTab',
            'osisActivities',
            'osisMembers',
            'extracurriculars',
            'achievements'
        ));
    }
}
