<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\BillingRecord;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        BillingRecord::create(array_merge($validated, [
            'student_id' => $student->id,
            'status'     => 'unpaid',
            'created_by' => auth()->id(),
        ]));

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

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }

    public function destroy(BillingRecord $billing): RedirectResponse
    {
        $studentId = $billing->student_id;
        $billing->delete();
        return redirect()->route('admin.billing.show', $studentId)
            ->with('success', 'Tagihan berhasil dihapus.');
    }
}
