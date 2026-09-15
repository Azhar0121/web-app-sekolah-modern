<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $children = auth()->user()->children()
            ->get()
            ->map(function ($child) {
                $child->classroomForDisplay = $child->currentClassroom();

                return $child;
            });

        return view('ortu.dashboard', compact('children'));
    }
}
