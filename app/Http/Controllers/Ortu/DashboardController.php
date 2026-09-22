<?php

namespace App\Http\Controllers\Ortu;

use App\Http\Controllers\Controller;
use App\Models\BillingRecord;
use App\Models\LeaveRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $parent = auth()->user();

        $children = $parent->children()
            ->get()
            ->map(function ($child) use ($parent) {
                $child->classroomForDisplay = $child->currentClassroom();

                // Tagihan belum lunas
                $child->unpaidBillingCount = BillingRecord::where('student_id', $child->id)
                    ->where('status', '!=', 'paid')
                    ->count();

                $child->unpaidBillingTotal = BillingRecord::where('student_id', $child->id)
                    ->where('status', '!=', 'paid')
                    ->sum('amount');

                // Izin pending
                $child->pendingLeaveCount = LeaveRequest::where('student_id', $child->id)
                    ->where('status', 'pending')
                    ->count();

                return $child;
            });

        // Total izin pending seluruh anak
        $totalPendingLeave = LeaveRequest::where('parent_id', $parent->id)
            ->where('status', 'pending')
            ->count();

        return view('ortu.dashboard', compact('children', 'totalPendingLeave'));
    }
}
