<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\BillingRecord;
use App\Models\Classroom;
use App\Models\ClassroomStudent;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class BillingController extends Controller
{
    public function index(Request $request): View
    {
        $search       = $request->string('search')->trim()->toString();
        $classroomId  = $request->query('classroom_id');
        $statusFilter = $request->query('has_unpaid'); // 'yes' | 'no' | ''

        $activeYear  = AcademicYear::active();
        $classrooms  = Classroom::orderBy('name')->get();

        $students = User::whereHas('role', fn ($q) => $q->where('slug', 'siswa'))
            ->with([
                'studentProfile',
                'classroomStudents' => fn ($q) => $activeYear
                    ? $q->where('academic_year_id', $activeYear->id)->with('classroom')
                    : $q->with('classroom'),
                'billingRecords' => fn ($q) => $q->where('status', '!=', 'paid'),
            ])
            // Filter nama siswa
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%"))
            // Filter kelas
            ->when($classroomId, fn ($q) => $q->whereHas(
                'classroomStudents',
                fn ($sub) => $sub->where('classroom_id', $classroomId)
                    ->when($activeYear, fn ($s) => $s->where('academic_year_id', $activeYear->id))
            ))
            // Filter status tagihan
            ->when($statusFilter === 'yes', fn ($q) => $q->whereHas(
                'billingRecords', fn ($sub) => $sub->where('status', '!=', 'paid')
            ))
            ->when($statusFilter === 'no', fn ($q) => $q->whereDoesntHave(
                'billingRecords', fn ($sub) => $sub->where('status', '!=', 'paid')
            ))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('admin.billing.index', compact(
            'students', 'search', 'classroomId', 'statusFilter', 'classrooms', 'activeYear'
        ));
    }

    public function show(User $student): View
    {
        abort_unless($student->hasRole('siswa'), 404);

        $billings    = BillingRecord::with(['confirmedBy', 'createdBy'])
            ->where('student_id', $student->id)
            ->orderByDesc('due_date')
            ->get();
        $academicYears = AcademicYear::orderByDesc('name')->get();

        return view('admin.billing.show', compact('student', 'billings', 'academicYears'));
    }

    public function store(Request $request, User $student): RedirectResponse
    {
        abort_unless($student->hasRole('siswa'), 404);

        $validated = $request->validate([
            'description'      => ['required', 'string', 'max:255'],
            'amount'           => ['required', 'numeric', 'min:0'],
            'due_date'         => ['nullable', 'date'],
            'academic_year_id' => ['nullable', 'exists:academic_years,id'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $billing = BillingRecord::create(array_merge($validated, [
            'student_id' => $student->id,
            'status'     => 'unpaid',
            'created_by' => auth()->id(),
        ]));

        // Notifikasi ke Orang Tua: tagihan baru diterbitkan
        foreach ($student->parents as $parent) {
            $parent->notify(new \App\Notifications\BillingStatusNotification($billing, 'created'));
        }

        return back()->with('success', 'Tagihan berhasil ditambahkan.');
    }

    public function confirm(Request $request, BillingRecord $billing): RedirectResponse
    {
        $request->validate([
            'payment_proof' => ['nullable', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:5120'],
        ]);

        $data = [
            'status'       => 'paid',
            'paid_at'      => now(),
            'confirmed_by' => auth()->id(),
            'confirmed_at' => now(),
        ];

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $data['payment_proof_path'] = $file->store('bukti-bayar', 'public');
            $data['payment_proof_original_name'] = $file->getClientOriginalName();
        }

        $billing->update($data);

        // Catat otomatis sebagai Kas Masuk di modul Keuangan Sekolah (idempoten)
        \App\Models\FinanceTransaction::recordFromBilling($billing->fresh('student'));

        // Notifikasi ke Orang Tua: pembayaran dikonfirmasi Lunas
        $student = $billing->student;
        if ($student) {
            foreach ($student->parents as $parent) {
                $parent->notify(new \App\Notifications\BillingStatusNotification($billing, 'confirmed'));
            }
        }

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function batchStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'classroom_id'     => ['required', 'string'], // ID kelas tertentu atau 'all'
            'academic_year_id' => ['required', 'exists:academic_years,id'],
            'description'      => ['required', 'string', 'max:255'],
            'amount'           => ['required', 'numeric', 'min:1'],
            'due_date'         => ['nullable', 'date'],
            'notes'            => ['nullable', 'string', 'max:1000'],
        ]);

        $academicYearId = $validated['academic_year_id'];
        $classroomId    = $validated['classroom_id'];

        $query = ClassroomStudent::where('academic_year_id', $academicYearId)
            ->with(['student.parents']);

        if ($classroomId !== 'all') {
            $query->where('classroom_id', $classroomId);
        }

        $enrollments = $query->get();

        if ($enrollments->isEmpty()) {
            return back()->with('error', 'Tidak ditemukan siswa pada kelas/tahun ajaran yang dipilih.');
        }

        $createdCount = 0;

        DB::transaction(function () use ($enrollments, $validated, $academicYearId, &$createdCount) {
            foreach ($enrollments as $enrollment) {
                $student = $enrollment->student;
                if (! $student || ! $student->hasRole('siswa')) {
                    continue;
                }

                $billing = BillingRecord::create([
                    'student_id'       => $student->id,
                    'academic_year_id' => $academicYearId,
                    'description'      => $validated['description'],
                    'amount'           => $validated['amount'],
                    'due_date'         => $validated['due_date'] ?? null,
                    'notes'            => $validated['notes'] ?? null,
                    'status'           => 'unpaid',
                    'created_by'       => auth()->id(),
                ]);

                // Notifikasi ke Orang Tua siswa
                foreach ($student->parents as $parent) {
                    $parent->notify(new \App\Notifications\BillingStatusNotification($billing, 'created'));
                }

                $createdCount++;
            }
        });

        return back()->with('success', "Berhasil menerbitkan tagihan massal untuk {$createdCount} siswa.");
    }

    public function receipt(BillingRecord $billing): View
    {
        abort_unless($billing->status === 'paid', 404, 'Kuitansi hanya dapat dicetak untuk tagihan yang sudah Lunas.');

        $billing->load(['student.studentProfile', 'academicYear', 'confirmedBy']);

        // Cari kelas siswa saat tahun ajaran tagihan
        $classroom = null;
        if ($billing->student) {
            $classroom = $billing->student->classroomStudents()
                ->when($billing->academic_year_id, fn ($q) => $q->where('academic_year_id', $billing->academic_year_id))
                ->latest()
                ->first()?->classroom;
        }

        return view('admin.billing.receipt', compact('billing', 'classroom'));
    }

    public function export(Request $request): \Symfony\Component\HttpFoundation\StreamedResponse
    {
        $classroomId  = $request->query('classroom_id');
        $statusFilter = $request->query('status'); // 'all', 'unpaid', 'paid'
        $activeYear   = AcademicYear::active();

        $query = BillingRecord::with(['student.studentProfile', 'academicYear'])
            ->when($statusFilter === 'unpaid', fn ($q) => $q->where('status', '!=', 'paid'))
            ->when($statusFilter === 'paid', fn ($q) => $q->where('status', 'paid'))
            ->when($classroomId, fn ($q) => $q->whereHas('student.classroomStudents', function ($sub) use ($classroomId, $activeYear) {
                $sub->where('classroom_id', $classroomId)
                    ->when($activeYear, fn ($s) => $s->where('academic_year_id', $activeYear->id));
            }))
            ->orderBy('student_id')
            ->orderByDesc('due_date');

        $filename = 'rekap-tagihan-siswa-' . date('Y-m-d-His') . '.csv';

        return response()->streamDownload(function () use ($query, $activeYear) {
            $handle = fopen('php://output', 'w');
            // Menambahkan UTF-8 BOM untuk kompatibilitas Excel
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header Kolom
            fputcsv($handle, [
                'No',
                'NISN',
                'Nama Siswa',
                'Kelas',
                'Tahun Ajaran',
                'Uraian Tagihan',
                'Nominal (Rp)',
                'Jatuh Tempo',
                'Status',
                'Tanggal Bayar',
                'Catatan',
            ]);

            $no = 1;
            $query->chunk(200, function ($records) use ($handle, &$no, $activeYear) {
                foreach ($records as $row) {
                    $student = $row->student;
                    $profile = $student?->studentProfile;
                    $classroom = $student?->classroomStudents()
                        ->when($row->academic_year_id ?: $activeYear?->id, fn ($q, $yId) => $q->where('academic_year_id', $yId))
                        ->first()?->classroom;

                    fputcsv($handle, [
                        $no++,
                        $profile?->nisn ?? '-',
                        $student?->name ?? '-',
                        $classroom?->name ?? 'Belum ada kelas',
                        $row->academicYear?->name ?? '-',
                        $row->description,
                        $row->amount,
                        $row->due_date ? $row->due_date->format('d/m/Y') : '-',
                        $row->statusLabel(),
                        $row->paid_at ? $row->paid_at->format('d/m/Y H:i') : '-',
                        $row->notes ?? '-',
                    ]);
                }
            });

            fclose($handle);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    public function destroy(BillingRecord $billing): RedirectResponse
    {
        $studentId = $billing->student_id;
        $billing->delete();
        return redirect()->route('admin.billing.show', $studentId)
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}