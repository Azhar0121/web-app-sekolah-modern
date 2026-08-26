<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PpdbAccepted;
use App\Models\PpdbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->string('status')->toString();

        $registrations = PpdbRegistration::with('period')
            ->when($statusFilter, fn ($query) => $query->where('status', $statusFilter))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.ppdb.index', compact('registrations', 'statusFilter'));
    }

    public function show(PpdbRegistration $ppdbRegistration): View
    {
        $ppdbRegistration->load('documents', 'period', 'verifiedBy', 'reRegistrationConfirmedBy');

        return view('admin.ppdb.show', ['registration' => $ppdbRegistration]);
    }

    public function updateStatus(Request $request, PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:verified,accepted,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $updateData = [
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $ppdbRegistration->notes,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ];

        // Begitu status jadi "accepted", otomatis hitung batas waktu daftar ulang
        // berdasarkan pengaturan hari di periode PPDB terkait (default 7 hari).
        if ($validated['status'] === 'accepted') {
            $reRegistrationDays = $ppdbRegistration->period->re_registration_days ?? 7;

            $updateData['accepted_at'] = now();
            $updateData['re_registration_deadline'] = now()->addDays($reRegistrationDays)->toDateString();
        }

        $ppdbRegistration->update($updateData);

        if ($validated['status'] === 'accepted') {
            $this->sendAcceptedEmail($ppdbRegistration);
        }

        return redirect()
            ->route('admin.ppdb.show', $ppdbRegistration)
            ->with('success', 'Status pendaftaran berhasil diperbarui menjadi "' . $ppdbRegistration->statusLabel() . '".');
    }

    public function confirmReRegistration(Request $request, PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        if ($ppdbRegistration->status !== 'accepted') {
            return back()->with('error', 'Daftar ulang hanya bisa dikonfirmasi untuk pendaftar yang sudah berstatus "Diterima".');
        }

        $validated = $request->validate([
            're_registration_reference' => ['required', 'string', 'max:255'],
            're_registration_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $ppdbRegistration->update([
            'status' => 'registered_ulang',
            're_registration_reference' => $validated['re_registration_reference'],
            're_registration_notes' => $validated['re_registration_notes'] ?? null,
            're_registration_confirmed_by' => auth()->id(),
            're_registration_confirmed_at' => now(),
        ]);

        return redirect()
            ->route('admin.ppdb.show', $ppdbRegistration)
            ->with('success', 'Daftar ulang & pembayaran berhasil dikonfirmasi.');
    }

    private function sendAcceptedEmail(PpdbRegistration $registration): void
    {
        try {
            Mail::to($registration->email)->send(new PpdbAccepted($registration));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email notifikasi diterima PPDB: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
            ]);
        }
    }
}