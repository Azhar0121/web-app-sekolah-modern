<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use App\Models\Semester;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SemesterController extends Controller
{
    public function store(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $validated = $request->validate([
            'name' => [
                'required', Rule::in(['Ganjil', 'Genap']),
                Rule::unique('semesters', 'name')->where('academic_year_id', $academicYear->id),
            ],
        ], [
            'name.unique' => 'Semester :input untuk tahun ajaran ini sudah ada.',
        ]);

        $academicYear->semesters()->create([
            'name' => $validated['name'],
            'is_active' => false,
        ]);

        return back()->with('success', "Semester {$validated['name']} berhasil ditambahkan.");
    }

    public function activate(AcademicYear $academicYear, Semester $semester): RedirectResponse
    {
        if ($semester->academic_year_id !== $academicYear->id) {
            abort(404);
        }

        Semester::where('id', '!=', $semester->id)->update(['is_active' => false]);
        $semester->update(['is_active' => true]);

        AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);

        return back()->with('success', "Semester {$semester->name} {$academicYear->name} kini menjadi semester aktif sistem.");
    }

    public function destroy(AcademicYear $academicYear, Semester $semester): RedirectResponse
    {
        if ($semester->academic_year_id !== $academicYear->id) {
            abort(404);
        }

        if ($semester->is_active) {
            return back()->with('error', 'Semester yang sedang aktif tidak bisa dihapus.');
        }

        $semester->delete();

        return back()->with('success', 'Semester berhasil dihapus.');
    }
}