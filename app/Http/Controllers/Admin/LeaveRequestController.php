<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\TeachingAssignment;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeaveRequestController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status', 'pending');
        $user   = auth()->user();

        $query = LeaveRequest::with(['student.studentProfile', 'parent', 'processedBy'])
            ->when($status !== 'all', fn ($q) => $q->where('status', $status));

        // Guru hanya lihat izin siswa di kelas yang diampu / wali kelas
        if ($user->hasRole('guru')) {
            $activeYear = AcademicYear::active();
            
            $homeroomClassIds = \App\Models\Classroom::where('homeroom_teacher_id', $user->id)->pluck('id');
            $teachingClassIds = $activeYear
                ? TeachingAssignment::where('teacher_id', $user->id)
                    ->where('academic_year_id', $activeYear->id)
                    ->pluck('classroom_id')
                : collect();

            $classroomIds = $homeroomClassIds->merge($teachingClassIds)->unique();

            $query->whereHas('student.classroomStudents', function ($q) use ($classroomIds, $activeYear) {
                $q->whereIn('classroom_id', $classroomIds)
                  ->when($activeYear, fn ($qq) => $qq->where('academic_year_id', $activeYear->id));
            });
        }

        $requests = $query->orderByDesc('created_at')->paginate(20)->withQueryString();

        return view('admin.leave-requests.index', compact('requests', 'status'));
    }

    public function process(Request $request, LeaveRequest $leaveRequest): RedirectResponse
    {
        $validated = $request->validate([
            'decision'      => ['required', 'in:approved,rejected'],
            'process_notes' => ['nullable', 'string', 'max:1000'],
        ]);

        $leaveRequest->update([
            'status'        => $validated['decision'],
            'process_notes' => $validated['process_notes'],
            'processed_by'  => auth()->id(),
            'processed_at'  => now(),
        ]);

        $label = $validated['decision'] === 'approved' ? 'disetujui' : 'ditolak';

        return back()->with('success', "Pengajuan izin berhasil {$label}.");
    }
}
