<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ParentAccountLinked;
use App\Mail\PpdbAccepted;
use App\Mail\StudentAccountCreated;
use App\Models\AcademicYear;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\PpdbPeriod;
use App\Models\PpdbRegistration;
use App\Models\Role;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\View\View;
use function Illuminate\Support\defer;

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

        $activePeriod = PpdbPeriod::where('is_active', true)->orderByDesc('start_date')->first()
            ?? PpdbPeriod::orderByDesc('start_date')->first();

        return view('admin.ppdb.index', compact('registrations', 'statusFilter', 'activePeriod'));
    }

    public function updatePeriod(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'period_id'            => ['nullable', 'integer', 'exists:ppdb_periods,id'],
            'name'                 => ['required', 'string', 'max:255'],
            'start_date'           => ['required', 'date'],
            'end_date'             => ['required', 'date', 'after_or_equal:start_date'],
            'quota'                => ['nullable', 'integer', 'min:1'],
            're_registration_days' => ['required', 'integer', 'min:1', 'max:60'],
            'is_active'            => ['required', 'boolean'],
        ], [
            'name.required'                 => 'Nama gelombang / periode PPDB wajib diisi.',
            'start_date.required'           => 'Tanggal mulai pendaftaran wajib diisi.',
            'end_date.required'             => 'Tanggal selesai pendaftaran wajib diisi.',
            'end_date.after_or_equal'       => 'Tanggal selesai pendaftaran harus sama atau setelah tanggal mulai.',
            're_registration_days.required' => 'Batas waktu daftar ulang wajib diisi.',
        ]);

        $periodId = $validated['period_id'] ?? null;
        unset($validated['period_id']);

        $period = $periodId ? PpdbPeriod::find($periodId) : null;

        if (! $period) {
            $period = PpdbPeriod::where('is_active', true)->orderByDesc('start_date')->first()
                ?? PpdbPeriod::orderByDesc('start_date')->first();
        }

        if (! $period) {
            $period = PpdbPeriod::create($validated);
        } else {
            $period->update($validated);
        }

        if ($period->is_active) {
            PpdbPeriod::where('id', '!=', $period->id)->update(['is_active' => false]);
        }

        $statusText = $period->isOpenForRegistration() ? 'DIBUKA' : 'DITUTUP';

        return redirect()
            ->route('admin.ppdb.index')
            ->with('success', "Periode PPDB berhasil diperbarui. Status pendaftaran saat ini: {$statusText}.");
    }

    public function show(PpdbRegistration $ppdbRegistration): View
    {
        $ppdbRegistration->load([
            'period',
            'documents',
            'verifiedBy',
            'reRegistrationConfirmedBy',
            'user',
            'parentUser',
        ]);

        return view('admin.ppdb.show', [
            'registration' => $ppdbRegistration,
        ]);
    }

    public function updateStatus(Request $request, PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        $validated = $request->validate([
            'status'         => ['required', 'in:verified,accepted,rejected'],
            'accepted_major' => ['required_if:status,accepted', 'nullable', 'string', 'max:100'],
            'notes'          => ['nullable', 'string', 'max:1000'],
        ], [
            'accepted_major.required_if' => 'Jurusan yang diterima wajib ditentukan saat mengubah status menjadi Diterima.',
        ]);

        $ppdbRegistration->status = $validated['status'];
        $ppdbRegistration->notes  = $validated['notes'] ?? $ppdbRegistration->notes;

        // Jika status diubah ke "verified", catat siapa yang memverifikasi
        if ($validated['status'] === 'verified') {
            $ppdbRegistration->verified_by = auth()->id();
            $ppdbRegistration->verified_at = now();
            $ppdbRegistration->accepted_major = null;
            $ppdbRegistration->accepted_at = null;
            $ppdbRegistration->re_registration_deadline = null;
        }

        // Jika status diubah ke "rejected"
        if ($validated['status'] === 'rejected') {
            $ppdbRegistration->accepted_major = null;
            $ppdbRegistration->accepted_at = null;
            $ppdbRegistration->re_registration_deadline = null;
        }

        // Jika status diubah ke "accepted", simpan jurusan yang diterima & hitung deadline daftar ulang
        if ($validated['status'] === 'accepted') {
            $ppdbRegistration->accepted_major = $validated['accepted_major'] ?: ($ppdbRegistration->first_major ?: null);
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

        [$studentUser, $studentPassword, $parentUser, $parentPassword, $isNewParent, $classroom] = DB::transaction(function () use ($ppdbRegistration, $validated) {
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

            $studentUser = User::create([
                'name'     => $ppdbRegistration->full_name,
                'email'    => $email,
                'password' => Hash::make($password),
                'role_id'  => $siswaRole?->id,
            ]);

            // Link registrasi ke user
            $ppdbRegistration->update(['user_id' => $studentUser->id]);

            StudentProfile::create([
                'user_id'         => $studentUser->id,
                'nisn'            => $ppdbRegistration->nisn,
                'nik'             => $ppdbRegistration->nik,
                'gender'          => $ppdbRegistration->gender,
                'birth_place'     => $ppdbRegistration->birth_place,
                'birth_date'      => $ppdbRegistration->birth_date,
                'previous_school' => $ppdbRegistration->previous_school,
                'parent_name'     => $ppdbRegistration->parent_name,
                'parent_phone'    => $ppdbRegistration->parent_phone,
                'address'         => $ppdbRegistration->address,
                'phone'           => $ppdbRegistration->phone,
            ]);

            // Coba tempatkan ke kelas X yang masih tersedia
            $activeYear = AcademicYear::active();
            $classroom  = $activeYear ? $this->findAvailableClassroom($activeYear, $ppdbRegistration) : null;

            if ($classroom && $activeYear) {
                ClassroomStudent::create([
                    'academic_year_id' => $activeYear->id,
                    'classroom_id'     => $classroom->id,
                    'student_id'       => $studentUser->id,
                ]);
            }

            // Buat atau tautkan akun orang tua jika ada data email orang tua
            [$parentUser, $parentPassword, $isNewParent] = $this->createOrLinkParentAccount($ppdbRegistration, $studentUser);

            return [$studentUser, $password, $parentUser, $parentPassword, $isNewParent, $classroom];
        });

        // Kirim email kredensial akun siswa dan orang tua di background phase (defer)
        $this->dispatchAccountCredentialsEmails(
            $ppdbRegistration,
            $studentUser,
            $studentPassword,
            $classroom,
            $parentUser,
            $parentPassword,
            $isNewParent
        );

        return redirect()
            ->route('admin.ppdb.show', $ppdbRegistration)
            ->with('success', 'Daftar ulang berhasil dikonfirmasi. Akun siswa dan orang tua telah dibuat/ditautkan dan email kredensial dikirim.');
    }

    public function resendCredentials(PpdbRegistration $ppdbRegistration): RedirectResponse
    {
        if ($ppdbRegistration->status !== 'registered_ulang' || ! $ppdbRegistration->user) {
            return back()->with('error', 'Kredensial hanya dapat dikirim ulang untuk pendaftar yang sudah menyelesaikan daftar ulang.');
        }

        $studentUser = $ppdbRegistration->user;
        $newStudentPassword = Str::random(10);
        $studentUser->update(['password' => Hash::make($newStudentPassword)]);

        $parentUser = $ppdbRegistration->parentUser;
        $newParentPassword = null;
        $isNewParent = false;

        if ($parentUser) {
            $newParentPassword = Str::random(10);
            $parentUser->update(['password' => Hash::make($newParentPassword)]);
        }

        $classroom = $studentUser->currentClassroom();

        $this->dispatchAccountCredentialsEmails(
            $ppdbRegistration,
            $studentUser,
            $newStudentPassword,
            $classroom,
            $parentUser,
            $newParentPassword,
            $isNewParent
        );

        return back()->with('success', 'Password baru berhasil digenerate dan email kredensial telah dikirim ulang ke siswa' . ($parentUser ? ' dan orang tua.' : '.'));
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

    private function findAvailableClassroom(AcademicYear $academicYear, PpdbRegistration $registration): ?Classroom
    {
        try {
            $candidates = Classroom::where('grade_level', 'X')
                ->where('is_active', true)
                ->withCount(['enrollments' => fn ($q) => $q->where('academic_year_id', $academicYear->id)])
                ->get()
                ->filter(fn (Classroom $c) => $c->capacity === null || $c->enrollments_count < $c->capacity);

            if ($candidates->isEmpty()) {
                return null;
            }

            $profiles = $candidates->map(function (Classroom $c) use ($academicYear) {
                $studentIds = ClassroomStudent::where('classroom_id', $c->id)
                    ->where('academic_year_id', $academicYear->id)
                    ->pluck('student_id');

                $regs = $studentIds->isNotEmpty()
                    ? PpdbRegistration::whereIn('user_id', $studentIds)->get()
                    : collect();

                $scores = $regs->map->academicScore()->filter();

                return [
                    'classroom' => $c,
                    'registrations' => $regs,
                    'averageScore' => $scores->isNotEmpty() ? $scores->avg() : 0,
                ];
            });

            // TIER 1: jumlah siswa gender yang sama, paling sedikit
            $targetGender = $registration->gender;
            $minGenderCount = $profiles->min(fn ($p) => $p['registrations']->where('gender', $targetGender)->count());
            $tiedByGender = $profiles->filter(
                fn ($p) => $p['registrations']->where('gender', $targetGender)->count() === $minGenderCount
            );

            // TIER 2: di antara yang seri gender, rata-rata skor akademik paling rendah
            $minAverage = $tiedByGender->min('averageScore');
            $tiedByScore = $tiedByGender->filter(fn ($p) => $p['averageScore'] == $minAverage);

            // TIER 3: kalau masih seri, jumlah siswa total paling sedikit
            $chosen = $tiedByScore->sortBy(fn ($p) => $p['registrations']->count())->first();

            return $chosen['classroom'] ?? null;
        } catch (\Throwable) {
            return null;
        }
    }

    private function sendAcceptedEmail(PpdbRegistration $registration): void
    {
        defer(function () use ($registration) {
            try {
                @set_time_limit(60);
                Mail::to($registration->email)->sendNow(new PpdbAccepted($registration));
            } catch (\Throwable $e) {
                Log::warning('Gagal mengirim email penerimaan PPDB: ' . $e->getMessage(), [
                    'registration_id' => $registration->id,
                ]);
            }
        });
    }

    private function dispatchAccountCredentialsEmails(
        PpdbRegistration $registration,
        User $studentUser,
        string $studentPassword,
        ?Classroom $classroom,
        ?User $parentUser = null,
        ?string $parentPassword = null,
        bool $isNewParent = false,
    ): void {
        defer(function () use ($registration, $studentUser, $studentPassword, $classroom, $parentUser, $parentPassword, $isNewParent) {
            try {
                @set_time_limit(120);

                // 1. Kirim email kredensial akun siswa
                Mail::to($studentUser->email)->sendNow(
                    new StudentAccountCreated($registration, $studentUser->email, $studentPassword, $classroom)
                );

                // 2. Beri jeda 2 detik untuk mematuhi rate limit Mailtrap Testing (1 email per detik)
                if ($parentUser) {
                    sleep(2);

                    Mail::to($parentUser->email)->sendNow(
                        new ParentAccountLinked(
                            registration: $registration,
                            parentUser: $parentUser,
                            password: $parentPassword,
                            isNewAccount: $isNewParent,
                        )
                    );
                }
            } catch (\Throwable $e) {
                Log::warning('Gagal mengirim email kredensial akun PPDB: ' . $e->getMessage(), [
                    'registration_id' => $registration->id,
                ]);
            }
        });
    }

    private function createOrLinkParentAccount(PpdbRegistration $registration, User $student): array
    {
        if (! $registration->parent_email) {
            return [null, null, false];
        }

        $ortuRole = Role::where('slug', 'ortu')->first();

        if (! $ortuRole) {
            Log::warning('Role "ortu" tidak ditemukan, akun orang tua tidak dibuat.', [
                'registration_id' => $registration->id,
            ]);

            return [null, null, false];
        }

        $parentUser = User::where('email', $registration->parent_email)->first();
        $isNewAccount = ! $parentUser;
        $password = null;

        if (! $parentUser) {
            $password = Str::random(10);

            $parentUser = User::create([
                'name'     => $registration->parent_name,
                'email'    => $registration->parent_email,
                'password' => Hash::make($password),
                'role_id'  => $ortuRole->id,
            ]);
        }

        $parentUser->children()->syncWithoutDetaching([$student->id]);

        $registration->update(['parent_user_id' => $parentUser->id]);

        return [$parentUser, $password, $isNewAccount];
    }
}