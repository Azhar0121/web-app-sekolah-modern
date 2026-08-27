<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AcademicYear;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AcademicYearController extends Controller
{
    public function index(): View
    {
        $academicYears = AcademicYear::withCount('semesters')
            ->orderByDesc('start_date')
            ->get();

        return view('admin.academic-years.index', compact('academicYears'));
    }

    public function create(): View
    {
        return view('admin.academic-years.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAcademicYear($request);

        $academicYear = AcademicYear::create([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'is_active' => false,
        ]);

        if ($request->boolean('is_active')) {
            $this->activateYear($academicYear);
        }

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran baru berhasil ditambahkan.');
    }

    public function edit(AcademicYear $academicYear): View
    {
        $academicYear->load('semesters');

        return view('admin.academic-years.edit', compact('academicYear'));
    }

    public function update(Request $request, AcademicYear $academicYear): RedirectResponse
    {
        $validated = $this->validateAcademicYear($request, $academicYear->id);

        $academicYear->update([
            'name' => $validated['name'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
        ]);

        if ($request->boolean('is_active') && ! $academicYear->is_active) {
            $this->activateYear($academicYear);
        }

        return redirect()
            ->route('admin.academic-years.edit', $academicYear)
            ->with('success', 'Data tahun ajaran berhasil diperbarui.');
    }

    public function destroy(AcademicYear $academicYear): RedirectResponse
    {
        if ($academicYear->is_active) {
            return back()->with('error', 'Tahun ajaran yang sedang aktif tidak bisa dihapus. Aktifkan tahun ajaran lain terlebih dahulu.');
        }

        $academicYear->delete(); // semester terkait ikut terhapus (cascade)

        return redirect()
            ->route('admin.academic-years.index')
            ->with('success', 'Tahun ajaran berhasil dihapus.');
    }

    /**
     * Set 1 tahun ajaran ini aktif, dan pastikan semua tahun ajaran lain nonaktif.
     * Hanya boleh ada 1 tahun ajaran aktif dalam sistem pada satu waktu.
     */
    private function activateYear(AcademicYear $academicYear): void
    {
        AcademicYear::where('id', '!=', $academicYear->id)->update(['is_active' => false]);
        $academicYear->update(['is_active' => true]);
    }

    private function validateAcademicYear(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'name' => [
                'required', 'string', 'max:20',
                Rule::unique('academic_years', 'name')->ignore($ignoreId),
            ],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after:start_date'],
        ]);
    }
}
