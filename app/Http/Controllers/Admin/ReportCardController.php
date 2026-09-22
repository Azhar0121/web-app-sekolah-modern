<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\ReportCard;
use App\Models\Semester;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    public function index(User $student): View
    {
        abort_unless($student->hasRole('siswa'), 404);

        $reportCards = ReportCard::with(['semester', 'uploadedBy'])
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        $semesters = Semester::orderByDesc('id')->get();

        return view('admin.report-cards.index', compact('student', 'reportCards', 'semesters'));
    }

    public function store(Request $request, User $student): RedirectResponse
    {
        abort_unless($student->hasRole('siswa'), 404);

        $validated = $request->validate([
            'semester_id' => ['required', 'exists:semesters,id'],
            'label'       => ['nullable', 'string', 'max:255'],
            'file'        => ['required', 'file', 'mimes:pdf', 'max:10240'],
        ]);

        $file = $request->file('file');
        $path = $file->store('rapor/' . $student->id, 'public');

        ReportCard::updateOrCreate(
            ['student_id' => $student->id, 'semester_id' => $validated['semester_id']],
            [
                'file_path'           => $path,
                'file_original_name'  => $file->getClientOriginalName(),
                'label'               => $validated['label'],
                'uploaded_by'         => auth()->id(),
            ]
        );

        return back()->with('success', 'Rapor berhasil diupload.');
    }

    public function destroy(ReportCard $reportCard): RedirectResponse
    {
        $studentId = $reportCard->student_id;
        Storage::disk('public')->delete($reportCard->file_path);
        $reportCard->delete();

        return redirect()->route('admin.report-cards.index', $studentId)
            ->with('success', 'Rapor berhasil dihapus.');
    }
}
