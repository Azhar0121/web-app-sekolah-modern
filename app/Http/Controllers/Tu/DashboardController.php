<?php

namespace App\Http\Controllers\Tu;

use App\Http\Controllers\Controller;
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

        // Sudah "Diterima" tapi belum daftar ulang — ini yang jadi tugas
        // utama TU: menunggu siswa datang bayar & mengonfirmasi di sistem.
        $awaitingReRegistration = PpdbRegistration::where('status', 'accepted')
            ->orderBy('re_registration_deadline')
            ->limit(8)
            ->get();

        // Riwayat penempatan kelas otomatis paling baru, supaya TU bisa
        // langsung lihat hasil dari konfirmasi yang baru saja dilakukan
        // (termasuk yang gagal ditempatkan karena kelas X penuh).
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

        return view('tu.dashboard', compact('stats', 'awaitingReRegistration', 'recentEnrollments'));
    }
}
