<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Material;
use App\Models\TeachingAssignment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class MaterialController extends Controller
{
    public function index(TeachingAssignment $teachingAssignment): View
    {
        $this->authorizeAssignment($teachingAssignment);

        $materials = $teachingAssignment->materials()->latest()->get();

        return view('guru.materials.index', compact('teachingAssignment', 'materials'));
    }

    public function create(TeachingAssignment $teachingAssignment): View
    {
        $this->authorizeAssignment($teachingAssignment);

        return view('guru.materials.create', compact('teachingAssignment'));
    }

    public function store(Request $request, TeachingAssignment $teachingAssignment): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);

        $validated = $this->validateMaterial($request, isUpdate: false);

        $material = new Material($validated);
        $material->teaching_assignment_id = $teachingAssignment->id;
        $material->is_published = $request->boolean('is_published', true);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $material->file_path = $file->store('materi/'.$teachingAssignment->id, 'public');
            $material->file_original_name = $file->getClientOriginalName();
        }

        $material->save();

        return redirect()
            ->route('guru.teaching-assignments.materials.index', $teachingAssignment)
            ->with('success', 'Materi berhasil diunggah.');
    }

    public function edit(TeachingAssignment $teachingAssignment, Material $material): View
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeMaterial($teachingAssignment, $material);

        return view('guru.materials.edit', compact('teachingAssignment', 'material'));
    }

    public function update(Request $request, TeachingAssignment $teachingAssignment, Material $material): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeMaterial($teachingAssignment, $material);

        $validated = $this->validateMaterial($request, isUpdate: true, existingMaterial: $material);

        $material->fill($validated);
        $material->is_published = $request->boolean('is_published', true);

        if ($request->hasFile('file')) {
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $file = $request->file('file');
            $material->file_path = $file->store('materi/'.$teachingAssignment->id, 'public');
            $material->file_original_name = $file->getClientOriginalName();
        } elseif ($request->boolean('remove_file')) {
            if ($material->file_path) {
                Storage::disk('public')->delete($material->file_path);
            }
            $material->file_path = null;
            $material->file_original_name = null;
        }

        $material->save();

        return redirect()
            ->route('guru.teaching-assignments.materials.index', $teachingAssignment)
            ->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy(TeachingAssignment $teachingAssignment, Material $material): RedirectResponse
    {
        $this->authorizeAssignment($teachingAssignment);
        $this->authorizeMaterial($teachingAssignment, $material);

        if ($material->file_path) {
            Storage::disk('public')->delete($material->file_path);
        }
        $material->delete();

        return redirect()
            ->route('guru.teaching-assignments.materials.index', $teachingAssignment)
            ->with('success', 'Materi berhasil dihapus.');
    }

    private function authorizeAssignment(TeachingAssignment $teachingAssignment): void
    {
        abort_unless($teachingAssignment->teacher_id === auth()->id(), 403, 'Anda tidak mengampu kelas/mapel ini.');
    }

    private function authorizeMaterial(TeachingAssignment $teachingAssignment, Material $material): void
    {
        abort_unless($material->teaching_assignment_id === $teachingAssignment->id, 404);
    }

    private function validateMaterial(Request $request, bool $isUpdate, ?Material $existingMaterial = null): array
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,ppt,pptx,xls,xlsx,zip', 'max:10240'],
            'link' => ['nullable', 'url', 'max:2000'],
            'is_published' => ['nullable', 'boolean'],
        ]);

        $willKeepOldFile = $isUpdate
            && $existingMaterial?->file_path
            && ! $request->hasFile('file')
            && ! $request->boolean('remove_file');

        $hasAnyAttachment = $request->hasFile('file') || ! empty($validated['link']) || $willKeepOldFile;

        if (! $hasAnyAttachment) {
            throw ValidationException::withMessages([
                'file' => 'Materi harus punya file atau link (isi salah satu).',
            ]);
        }

        return $validated;
    }
}