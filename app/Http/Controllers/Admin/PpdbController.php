<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PpdbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    /**
     * Approval workflow berlapis: submitted -> verified -> accepted/rejected.
     * TU melakukan verifikasi data, keputusan akhir diterima/ditolak juga
     * lewat aksi ini.
     */
    public function updateStatus(Request $request, PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:verified,accepted,rejected'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $ppdbRegistration->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $ppdbRegistration->notes,
            'verified_by' => auth()->id(),
            'verified_at' => now(),
        ]);

        return redirect()
            ->route('admin.ppdb.show', $ppdbRegistration)
            ->with('success', 'Status pendaftaran berhasil diperbarui menjadi "' . $ppdbRegistration->statusLabel() . '".');
    }

    /**
     * Konfirmasi daftar ulang & pembayaran OFFLINE. Siswa datang langsung ke
     * sekolah untuk bayar & daftar ulang; TU cuma mencatat nomor bukti bayar
     * di sini sebagai validasi — tidak ada payment gateway online.
     * Hanya bisa dipakai kalau status pendaftar sudah "accepted".
     */
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
}