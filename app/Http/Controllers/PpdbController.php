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
use Illuminate\Validation\Rule;
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
            'nisn' => [
                'nullable', 'string', 'max:20',
                Rule::unique('ppdb_registrations', 'nisn')
                    ->where('ppdb_period_id', $activePeriod->id)
                    ->where('status', '!=', 'rejected'),
            ],
            'nik' => [
                'nullable', 'string', 'max:20',
                Rule::unique('ppdb_registrations', 'nik')
                    ->where('ppdb_period_id', $activePeriod->id)
                    ->where('status', '!=', 'rejected'),
            ],
            'gender' => ['required', 'in:L,P'],
            'birth_place' => ['required', 'string', 'max:255'],
            'birth_date' => ['required', 'date'],
            'address' => ['required', 'string'],
            'phone' => ['required', 'string', 'max:20'],
            'email' => [
                'required', 'email', 'max:255',
                Rule::unique('ppdb_registrations', 'email')
                    ->where('ppdb_period_id', $activePeriod->id)
                    ->where('status', '!=', 'rejected'),
            ],
            'parent_name' => ['required', 'string', 'max:255'],
            'parent_phone' => ['required', 'string', 'max:20'],
            'parent_email' => ['required', 'email', 'max:255'],
            'previous_school' => ['required', 'string', 'max:255'],
            'first_major' => ['required', 'string', 'max:100'],
            'second_major' => ['nullable', 'string', 'max:100'],
            'nilai_rapor' => ['required', 'numeric', 'min:0', 'max:100'],
            'nilai_ijazah' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'documents' => ['required', 'array'],
            'documents.foto' => ['required', 'file', 'max:2048', 'mimes:jpg,jpeg,png'],
            'documents.kartu_keluarga' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.akta_lahir' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.rapor_1' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.rapor_2' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.rapor_3' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.rapor_4' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.rapor_5' => ['required', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.sertifikat_1' => ['nullable', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.sertifikat_2' => ['nullable', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.surat_keterangan_lulus' => ['nullable', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
            'documents.*' => ['nullable', 'file', 'max:2048', 'mimes:pdf,jpg,jpeg,png'],
        ], [
            'nisn.unique' => 'NISN ini sudah terdaftar pada periode PPDB yang sedang berjalan. '
                .'Jika Anda sebelumnya sudah mendaftar, silakan gunakan menu "Cek Status" atau "Lupa Nomor Pendaftaran".',
            'nik.unique' => 'NIK ini sudah terdaftar pada periode PPDB yang sedang berjalan. '
                .'Jika Anda sebelumnya sudah mendaftar, silakan gunakan menu "Cek Status" atau "Lupa Nomor Pendaftaran".',
            'email.unique' => 'Email ini sudah terdaftar pada periode PPDB yang sedang berjalan. '
                .'Jika Anda sebelumnya sudah mendaftar, gunakan menu "Cek Status" atau "Lupa Nomor Pendaftaran". '
                .'Jika ini bukan pendaftaran Anda, gunakan email pribadi lain.',
            'first_major.required' => 'Pilihan Jurusan 1 wajib dipilih.',
            'documents.required' => 'Dokumen persyaratan wajib diunggah.',
            'documents.foto.required' => 'Pas foto calon siswa (3x4) wajib diunggah.',
            'documents.foto.mimes' => 'Format pas foto calon siswa harus berupa gambar (JPG, JPEG, atau PNG).',
            'documents.foto.max' => 'Ukuran pas foto calon siswa maksimal 2MB.',
            'documents.kartu_keluarga.required' => 'Dokumen Kartu Keluarga (KK) wajib diunggah.',
            'documents.kartu_keluarga.mimes' => 'Format dokumen Kartu Keluarga harus berupa PDF, JPG, atau PNG.',
            'documents.kartu_keluarga.max' => 'Ukuran file Kartu Keluarga maksimal 2MB.',
            'documents.akta_lahir.required' => 'Dokumen Akta Kelahiran wajib diunggah.',
            'documents.akta_lahir.mimes' => 'Format dokumen Akta Kelahiran harus berupa PDF, JPG, atau PNG.',
            'documents.akta_lahir.max' => 'Ukuran file Akta Kelahiran maksimal 2MB.',
            'documents.rapor_1.required' => 'Dokumen scan Buku Rapor Semester 1 wajib diunggah.',
            'documents.rapor_1.mimes' => 'Format dokumen Rapor Semester 1 harus berupa PDF, JPG, atau PNG.',
            'documents.rapor_1.max' => 'Ukuran file Rapor Semester 1 maksimal 2MB.',
            'documents.rapor_2.required' => 'Dokumen scan Buku Rapor Semester 2 wajib diunggah.',
            'documents.rapor_2.mimes' => 'Format dokumen Rapor Semester 2 harus berupa PDF, JPG, atau PNG.',
            'documents.rapor_2.max' => 'Ukuran file Rapor Semester 2 maksimal 2MB.',
            'documents.rapor_3.required' => 'Dokumen scan Buku Rapor Semester 3 wajib diunggah.',
            'documents.rapor_3.mimes' => 'Format dokumen Rapor Semester 3 harus berupa PDF, JPG, atau PNG.',
            'documents.rapor_3.max' => 'Ukuran file Rapor Semester 3 maksimal 2MB.',
            'documents.rapor_4.required' => 'Dokumen scan Buku Rapor Semester 4 wajib diunggah.',
            'documents.rapor_4.mimes' => 'Format dokumen Rapor Semester 4 harus berupa PDF, JPG, atau PNG.',
            'documents.rapor_4.max' => 'Ukuran file Rapor Semester 4 maksimal 2MB.',
            'documents.rapor_5.required' => 'Dokumen scan Buku Rapor Semester 5 wajib diunggah.',
            'documents.rapor_5.mimes' => 'Format dokumen Rapor Semester 5 harus berupa PDF, JPG, atau PNG.',
            'documents.rapor_5.max' => 'Ukuran file Rapor Semester 5 maksimal 2MB.',
        ]);

        $registration = PpdbRegistration::create([
            ...$validated,
            'ppdb_period_id' => $activePeriod->id,
            'status' => 'submitted',
        ]);

        foreach ($request->file('documents', []) as $docType => $file) {
            if (! $file || ! $file->isValid()) {
                continue;
            }

            if ($file->getSize() > 2 * 1024 * 1024 || ! in_array(strtolower($file->getClientOriginalExtension()), ['pdf', 'jpg', 'jpeg', 'png'])) {
                continue;
            }

            $path = $file->store('ppdb-documents', 'public');

            PpdbDocument::create([
                'ppdb_registration_id' => $registration->id,
                'document_type' => $docType,
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

    public function print(string $registrationNumber): View
    {
        $registration = PpdbRegistration::with(['period', 'documents'])
            ->where('registration_number', $registrationNumber)
            ->firstOrFail();

        return view('ppdb.cetak', compact('registration'));
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