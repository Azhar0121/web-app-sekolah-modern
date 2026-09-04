<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PpdbAccepted;
use App\Mail\StudentAccountCreated;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\PpdbRegistration;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PpdbController extends Controller
{
    public function index(Request $request): View
    {
        $statusFilter = $request->query('status');

        $registrations = PpdbRegistration::with('period')
            ->when($statusFilter, fn ($q) => $q->where('status', $statusFilter))
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('admin.ppdb.index', compact('registrations', 'statusFilter'));
    }

    public function show(PpdbRegistration $ppdbRegistration): View
    {
        $ppdbRegistration->load([
            'period',
            'documents',
            'verifiedBy',
            'reRegistrationConfirmedBy',
            'user',
        ]);

        return view('admin.ppdb.show', [
            'registration' => $ppdbRegistration,
        ]);
    }

    public function updateStatus(Request $request, PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:verified,accepted,rejected'],
            'notes'  => ['nullable', 'string', 'max:1000'],
        ]);

        $ppdbRegistration->status = $validated['status'];
        $ppdbRegistration->notes  = $validated['notes'] ?? $ppdbRegistration->notes;

        // Jika status diubah ke "verified", catat siapa yang memverifikasi
        if ($validated['status'] === 'verified') {
            $ppdbRegistration->verified_by = auth()->id();
            $ppdbRegistration->verified_at = now();
        }

        // Jika status diubah ke "accepted", hitung deadline daftar ulang & kirim email
        if ($validated['status'] === 'accepted') {
            $ppdbRegistration->accepted_at = now();

            $reRegistrationDays = $ppdbRegistration->period->re_registration_days ?? 7;
            $ppdbRegistration->re_registration_deadline = now()->addDays($reRegistrationDays);

            $ppdbRegistration->verified_by = $ppdbRegistration->verified_by ?? auth()->id();
            $ppdbRegistration->verified_at = $ppdbRegistration->verified_at ?? now();

            $this->sendAcceptedEmail($ppdbRegistration);
        }

        $ppdbRegistration->save();

        return redirect()
            ->route('admin.ppdb.show', $ppdbRegistration)
            ->with('success', 'Status pendaftar berhasil diperbarui.');
    }

    public function confirmReRegistration(Request $request, PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        if ($ppdbRegistration->status !== 'accepted') {
            return back()->with('error', 'Hanya pendaftar dengan status "Diterima" yang bisa dikonfirmasi daftar ulangnya.');
        }

        $validated = $request->validate([
            're_registration_reference' => ['required', 'string', 'max:255'],
            're_registration_notes'     => ['nullable', 'string', 'max:1000'],
        ]);

        DB::transaction(function () use ($ppdbRegistration, $validated) {
            $ppdbRegistration->update([
                'status'                        => 'registered_ulang',
                're_registration_reference'     => $validated['re_registration_reference'],
                're_registration_notes'         => $validated['re_registration_notes'] ?? null,
                're_registration_confirmed_by'  => auth()->id(),
                're_registration_confirmed_at'  => now(),
            ]);

            $password  = Str::random(10);
            $siswaRole = Role::where('slug', 'siswa')->first();
            $email     = $this->resolveUniqueEmail($ppdbRegistration);

            $user = User::create([
                'name'     => $ppdbRegistration->full_name,
                'email'    => $email,
                'password' => Hash::make($password),
                'role_id'  => $siswaRole?->id,
            ]);

            // Link registrasi ke user
            $ppdbRegistration->update(['user_id' => $user->id]);

            // Coba tempatkan ke kelas X yang masih tersedia
            $activeYear = AcademicYear::active();
            $classroom  = $activeYear ? $this->findAvailableClassroom($activeYear) : null;

            if ($classroom && $activeYear) {
                ClassroomStudent::create([
                    'academic_year_id' => $activeYear->id,
                    'classroom_id'     => $classroom->id,
                    'student_id'       => $user->id,
                ]);
            }

            // Kirim email kredensial ke siswa
            $this->sendAccountCreatedEmail($ppdbRegistration, $user->email, $password, $classroom);
        });

        return redirect()
            ->route('admin.ppdb.show', $ppdbRegistration)
            ->with('success', 'Daftar ulang berhasil dikonfirmasi. Akun siswa telah dibuat dan email dikirim.');
    }

    private function resolveUniqueEmail(PpdbRegistration $registration): string
    {
        if (! User::where('email', $registration->email)->exists()) {
            return $registration->email;
        }

        $suffix = strtolower(str_replace('-', '', $registration->registration_number));
        [$localPart, $domain] = explode('@', $registration->email, 2);

        return "{$localPart}+{$suffix}@{$domain}";
    }

    private function findAvailableClassroom(AcademicYear $academicYear): ?Classroom
    {
        try {
            return Classroom::where('grade_level', 'X')
                ->where('is_active', true)
                ->withCount(['enrollments' => fn ($q) => $q->where('academic_year_id', $academicYear->id)])
                ->get()
                ->filter(fn (Classroom $c) => $c->capacity === null || $c->enrollments_count < $c->capacity)
                ->sortBy('enrollments_count')
                ->first();
        } catch (\Throwable) {
            return null;
        }
    }

    private function sendAcceptedEmail(PpdbRegistration $registration): void
    {
        try {
            Mail::to($registration->email)->send(new PpdbAccepted($registration));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email penerimaan PPDB: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
            ]);
        }
    }

    private function sendAccountCreatedEmail(
        PpdbRegistration $registration,
        string $email,
        string $password,
        ?Classroom $classroom,
    ): void {
        try {
            Mail::to($email)->send(new StudentAccountCreated($registration, $email, $password, $classroom));
        } catch (\Throwable $e) {
            Log::warning('Gagal mengirim email akun siswa: ' . $e->getMessage(), [
                'registration_id' => $registration->id,
            ]);
        }
    }
}