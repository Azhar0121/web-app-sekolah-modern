<?php

namespace App\Http\Controllers;

use App\Models\PpdbDocument;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PpdbController extends Controller
{
    /**
     * Halaman info PPDB (menampilkan periode aktif saat ini).
     */
    public function index(): View
    {
        $activePeriod = PpdbPeriod::where('is_active', true)
            ->orderByDesc('start_date')
            ->first();

        return view('ppdb.index', compact('activePeriod'));
    }

    public function create(): View|RedirectResponse
    {
        $activePeriod = PpdbPeriod::where('is_active', true)->orderByDesc('start_date')->first();

        if (! $activePeriod || ! $activePeriod->isOpenForRegistration()) {
            return redirect()
                ->route('ppdb.index')
                ->with('error', 'Saat ini tidak ada periode PPDB yang sedang dibuka.');
        }

        return view('ppdb.daftar', compact('activePeriod'));
    }

    public function store(Request $request): RedirectResponse
    {
        $activePeriod = PpdbPeriod::where('is_active', true)->orderByDesc('start_date')->first();

        if (! $activePeriod || ! $activePeriod->isOpenForRegistration()) {
            return back()->with('error', 'Pendaftaran sedang tidak dibuka.');
        }

        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'nisn' => ['nullable', 'string', 'max:20'],
            'nik' => ['nullable', 'string', 'max:20'],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'previous_school' => ['required', 'string', 'max:255'],
            'documents' => ['nullable', 'array'],
            'document_types' => ['nullable', 'array'],
        ]);

        $registration = PpdbRegistration::create([
            ...$validated,
            'ppdb_period_id' => $activePeriod->id,
            'status' => 'submitted',
        ]);

        // Upload dokumen (opsional di tahap ini, supaya form tetap bisa disubmit
        // meski calon siswa belum siap semua berkasnya — bisa dilengkapi menyusul).
        foreach ($request->file('documents', []) as $index => $file) {
            // Slot upload yang tidak diisi calon siswa akan tetap muncul di array
            // tapi bukan file valid — dilewati saja, bukan bagian yang wajib.
            if (! $file || ! $file->isValid()) {
                continue;
            }

            if ($file->getSize() > 2 * 1024 * 1024 || ! in_array($file->getClientOriginalExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                continue; // silently skip file yang tidak sesuai (bisa diperketat lagi nanti jadi pesan error eksplisit)
            }

            $path = $file->store('ppdb-documents', 'public');

            PpdbDocument::create([
                'ppdb_registration_id' => $registration->id,
                'document_type' => $validated['document_types'][$index] ?? 'lainnya',
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);
        }

        return redirect()
            ->route('ppdb.sukses', $registration->registration_number)
            ->with('success', 'Pendaftaran berhasil dikirim.');
    }

    public function success(string $registrationNumber): View
    {
        $registration = PpdbRegistration::where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('ppdb.sukses', compact('registration'));
    }

    public function checkStatusForm(): View
    {
        return view('ppdb.cek-status');
    }

    public function checkStatus(Request $request): View
    {
        $request->validate([
            'registration_number' => ['required', 'string'],
        ]);

        $registration = PpdbRegistration::with('documents')
            ->where('registration_number', $request->registration_number)
            ->first();

        return view('ppdb.cek-status', compact('registration'));
    }
}
