<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ClassroomController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();

        $classrooms = Classroom::with('homeroomTeacher')
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->orderBy('grade_level')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.classrooms.index', compact('classrooms', 'search'));
    }

    public function create(): View
    {
        $teachers = $this->teacherOptions();

        return view('admin.classrooms.create', compact('teachers'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateClassroom($request);

        Classroom::create($validated);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Kelas baru berhasil ditambahkan.');
    }

    public function edit(Classroom $classroom): View
    {
        $teachers = $this->teacherOptions();

        return view('admin.classrooms.edit', compact('classroom', 'teachers'));
    }

    public function update(Request $request, Classroom $classroom): RedirectResponse
    {
        $validated = $this->validateClassroom($request, $classroom->id);

        $classroom->update($validated);

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Classroom $classroom): RedirectResponse
    {
        $classroom->delete();

        return redirect()
            ->route('admin.classrooms.index')
            ->with('success', 'Kelas berhasil dihapus.');
    }

    /** Guru yang bisa dipilih sebagai wali kelas (hanya user dengan role "guru"). */
    private function teacherOptions()
    {
        return User::whereHas('role', fn ($q) => $q->where('slug', 'guru'))
            ->orderBy('name')
            ->get();
    }

    private function validateClassroom(Request $request, ?int $ignoreId = null): array
    {
        $validated = $request->validate([
            'name' => [
                'required', 'string', 'max:100',
                Rule::unique('classrooms', 'name')->ignore($ignoreId),
            ],
            'grade_level' => ['required', Rule::in(['X', 'XI', 'XII'])],
            'major' => ['nullable', 'string', 'max:50'],
            'homeroom_teacher_id' => [
                'nullable',
                'exists:users,id',
                function (string $attribute, $value, \Closure $fail) {
                    if ($value && ! User::whereHas('role', fn ($q) => $q->where('slug', 'guru'))->whereKey($value)->exists()) {
                        $fail('User yang dipilih sebagai wali kelas harus memiliki role Guru.');
                    }
                },
            ],
            'capacity' => ['nullable', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active', true);

        return $validated;
    }
}
