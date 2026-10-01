<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Room;
use App\Models\Setting;
use App\Models\User;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index(): View
    {
        $settings = Setting::all()->pluck('value', 'key')->all();

        $teachers = User::whereHas('role', fn ($q) => $q->where('slug', 'guru'))
            ->with(['teachingAssignments.subject', 'teachingAssignments.classroom'])
            ->orderBy('name')
            ->get();

        $facilities = Room::where('is_active', true)->orderBy('type')->get();
        $departments = Department::where('is_active', true)->orderBy('code')->get();

        return view('public.profile', compact(
            'settings',
            'teachers',
            'facilities',
            'departments'
        ));
    }
}
