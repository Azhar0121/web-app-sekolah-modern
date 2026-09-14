<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
use App\Models\Correspondence;
use App\Models\PpdbRegistration;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'submitted' => PpdbRegistration::where('status', 'submitted')->count(),
            'verified' => PpdbRegistration::where('status', 'verified')->count(),
            'accepted' => PpdbRegistration::where('status', 'accepted')->count(),
            'registered_ulang' => PpdbRegistration::where('status', 'registered_ulang')->count(),
        ];

        $correspondenceStats = [
            'masuk' => Correspondence::where('type', 'masuk')->count(),
            'masuk_baru' => Correspondence::where('type', 'masuk')->where('status', 'baru')->count(),
            'keluar' => Correspondence::where('type', 'keluar')->count(),
        ];

        $awaitingReRegistration = PpdbRegistration::where('status', 'accepted')
            ->orderBy('re_registration_deadline')
            ->limit(8)
            ->get();

        $recentEnrollments = PpdbRegistration::with('user')
            ->where('status', 'registered_ulang')
            ->whereNotNull('user_id')
            ->orderByDesc('re_registration_confirmed_at')
            ->limit(8)
            ->get()
            ->map(function (PpdbRegistration $registration) {
                $registration->placedClassroom = $registration->user?->currentClassroom();

                return $registration;
            });

        return view('tu.dashboard', compact('stats', 'awaitingReRegistration', 'recentEnrollments', 'correspondenceStats'));
    }
}