<?php
namespace App\Http\Controllers\Ortu;
use App\Http\Controllers\Controller;
use App\Models\ReportCard;
use App\Models\User;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ReportCardController extends Controller
{
    public function index(User $student): View
    {
        abort_unless(
            auth()->user()->children()->where('users.id', $student->id)->exists(),
            403
        );

        $reportCards = ReportCard::with('semester')
            ->where('student_id', $student->id)
            ->orderByDesc('created_at')
            ->get();

        return view('ortu.report-cards.index', compact('student', 'reportCards'));
    }

    public function download(ReportCard $reportCard): Response
    {
        // Pastikan laporan ini milik anak dari ortu yang login
        abort_unless(
            auth()->user()->children()->where('users.id', $reportCard->student_id)->exists(),
            403
        );

        abort_unless(Storage::disk('public')->exists($reportCard->file_path), 404);

        return Storage::disk('public')->download(
            $reportCard->file_path,
            $reportCard->file_original_name
        );
    }
}
