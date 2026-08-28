<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Material;
use App\Models\TeachingAssignment;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(): View
    {
        $classroom = auth()->user()->currentClassroom();
        $activeYear = AcademicYear::active();

        $assignments = ($classroom && $activeYear)
            ? TeachingAssignment::with(['subject', 'teacher'])
                ->where('classroom_id', $classroom->id)
                ->where('academic_year_id', $activeYear->id)
                ->get()
                ->sortBy(fn ($a) => $a->subject->name)
            : collect();

        $materialsBySubject = $assignments->mapWithKeys(function ($assignment) {
            return [
                $assignment->subject->name => $assignment->materials()
                    ->where('is_published', true)
                    ->latest()
                    ->get(),
            ];
        });

        return view('siswa.materials.index', compact('classroom', 'materialsBySubject'));
    }

    public function download(Material $material): Response
    {
        $classroom = auth()->user()->currentClassroom();

        abort_unless(
            $material->hasFile() && $classroom && $material->teachingAssignment->classroom_id === $classroom->id && $material->is_published,
            403
        );

        return Storage::disk('public')->download($material->file_path, $material->file_original_name);
    }
}
