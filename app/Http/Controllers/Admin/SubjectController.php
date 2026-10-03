<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Subject;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SubjectController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $departmentFilter = $request->query('department', 'all');

        $departments = Department::where('is_active', true)->orderBy('name')->get();

        $subjects = Subject::with('department')
            ->when($search, fn ($query) => $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%");
            }))
            ->when($departmentFilter === 'umum', fn ($query) => $query->whereNull('department_id'))
            ->when(is_numeric($departmentFilter), fn ($query) => $query->where('department_id', $departmentFilter))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.subjects.index', compact('subjects', 'departments', 'search', 'departmentFilter'));
    }

    public function create(): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.subjects.create', compact('departments'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateSubject($request);

        Subject::create($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran baru berhasil ditambahkan.');
    }

    public function edit(Subject $subject): View
    {
        $departments = Department::where('is_active', true)->orderBy('name')->get();

        return view('admin.subjects.edit', compact('subject', 'departments'));
    }

    public function update(Request $request, Subject $subject): RedirectResponse
    {
        $validated = $this->validateSubject($request, $subject->id);

        $subject->update($validated);

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Data mata pelajaran berhasil diperbarui.');
    }

    public function destroy(Subject $subject): RedirectResponse
    {
        $subject->delete();

        return redirect()
            ->route('admin.subjects.index')
            ->with('success', 'Mata pelajaran berhasil dihapus.');
    }

    private function validateSubject(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'code' => [
                'required', 'string', 'max:20',
                Rule::unique('subjects', 'code')->ignore($ignoreId),
            ],
            'department_id' => ['nullable', 'exists:departments,id'],
            'description' => ['nullable', 'string', 'max:1000'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['code'] = strtoupper($validated['code']);
        $validated['department_id'] = $request->filled('department_id') ? $request->integer('department_id') : null;
        $validated['is_active'] = $request->boolean('is_active', true);

        return $validated;
    }
}
