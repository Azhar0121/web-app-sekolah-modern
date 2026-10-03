<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Material;
use App\Models\Setting;
use App\Models\Subject;
use Illuminate\View\View;

class AcademicController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();
        $departments = Department::where('is_active', true)->orderBy('name')->get();
        $subjects = Subject::with('department')
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
        $materials = Material::with('teachingAssignment.subject')
            ->where('is_published', true)
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('public.academic', compact('settings', 'departments', 'subjects', 'materials'));
    }
}
