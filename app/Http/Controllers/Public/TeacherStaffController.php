<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use Illuminate\View\View;

class TeacherStaffController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();

        $teachers = User::whereHas('role', fn ($q) => $q->where('slug', 'guru'))
            ->where('is_active', true)
            ->with(['role', 'teachingAssignments.subject', 'teachingAssignments.classroom'])
            ->orderBy('name')
            ->get();

        $staff = User::whereHas('role', fn ($q) => $q->where('slug', 'tu'))
            ->where('is_active', true)
            ->with('role')
            ->orderBy('name')
            ->get();

        $principals = User::whereHas('role', fn ($q) => $q->where('slug', 'kepsek'))
            ->where('is_active', true)
            ->with('role')
            ->get();

        return view('public.teachers.index', compact(
            'settings',
            'teachers',
            'staff',
            'principals'
        ));
    }
}