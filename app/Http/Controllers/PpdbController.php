<?php

namespace App\Http\Controllers;

use App\Mail\PpdbRegistrationSubmitted;
use App\Models\PpdbDocument;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PpdbController extends Controller
{
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
            // Wajib diisi — dipakai untuk mengirim nomor pendaftaran & sebagai
            // salah satu jalur pemulihan kalau nomor pendaftaran hilang/lupa.
            'email' => ['required', 'email', 'max:255'],
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

        foreach ($request->file('documents', []) as $index => $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            if ($file->getSize() > 2 * 1024 * 1024 || ! in_array($file->getClientOriginalExtension(), ['pdf', 'jpg', 'jpeg', 'png'])) {
                continue;
            }

            $path = $file->store('ppdb-documents', 'public');

            PpdbDocument::create([
                'ppdb_registration_id' => $registration->id,
                'document_type' => $validated['document_types'][$index] ?? 'lainnya',
                'original_name' => $file->getClientOriginalName(),
                'file_path' => $path,
            ]);
        }

        $this->sendRegistrationEmail($registration);

        return redirect()
            ->route('ppdb.sukses', $registration->registration_number)
            ->with('success', 'Pendaftaran berhasil dikirim. Nomor pendaftaran juga sudah dikirim ke email Anda.');
    }

    public function success(string $registrationNumber): View
    {
        $registration = PpdbRegistration::where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('ppdb.sukses', compact('registration'));
    }

    public function checkStatusForm(Request $request): View
    {
        $registration = null;
        $searched = false;

        if ($request->filled('registration_number')) {
            $searched = true;
            $registration = PpdbRegistration::with('documents')
                ->where('registration_number', $request->query('registration_number'))
                ->first();
        }

        return view('ppdb.cek-status', compact('registration', 'searched'));
    }

    public function checkStatus(Request $request): View
    {
        $request->validate([
            'registration_number' => ['required', 'string'],
        ]);

        $registration = PpdbRegistration::with('documents')
            ->where('registration_number', $request->registration_number)
            ->first();

        $searched = true;

        return view('ppdb.cek-status', compact('registration', 'searched'));
    }

    public function forgotNumberForm(): View
    {
        return view('ppdb.lupa-nomor');
    }

    public function forgotNumber(Request $request): View
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
        ]);

        $registrations = PpdbRegistration::whereRaw('LOWER(full_name) = ?', [strtolower(trim($validated['full_name']))])
            ->whereDate('birth_date', $validated['birth_date'])
            ->orderByDesc('created_at')
            ->get();

        return view('ppdb.lupa-nomor', compact('registrations'));
    }

    private function sendRegistrationEmail(PpdbRegistration $registration): void
    {
        try {
            Mail::to($registration->email)->send(new PpdbRegistrationSubmitted($registration));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email nomor pendaftaran PPDB: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
            ]);
        }
    }
}