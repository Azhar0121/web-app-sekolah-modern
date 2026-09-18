<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use App\Models\StudentProfile;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class StudentProfileController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $classroomId = $request->integer('classroom_id');

        $classrooms = Classroom::orderBy('grade_level')->orderBy('name')->get();

        $students = User::whereHas('role', fn ($q) => $q->where('slug', 'siswa'))
            ->with('studentProfile')
            ->when($search, fn ($q) => $q->where(function ($qq) use ($search) {
                $qq->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('studentProfile', fn ($p) => $p->where('nisn', 'like', "%{$search}%"));
            }))
            ->when($classroomId, fn ($q) => $q->whereHas(
                'classroomStudents',
                fn ($cs) => $cs->where('classroom_id', $classroomId)
            ))
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        $students->getCollection()->transform(function (User $student) {
            $student->classroomForDisplay = $student->currentClassroom();

            return $student;
        });

        return view('admin.student-profiles.index', compact('students', 'classrooms', 'search', 'classroomId'));
    }

    public function edit(User $student): View
    {
        abort_unless($student->hasRole('siswa'), 404);

        $profile = $student->studentProfile ?? new StudentProfile(['user_id' => $student->id]);
        $classroom = $student->currentClassroom();

        return view('admin.student-profiles.edit', compact('student', 'profile', 'classroom'));
    }

    public function update(Request $request, User $student): RedirectResponse
    {
        abort_unless($student->hasRole('siswa'), 404);

        $validated = $request->validate([
            'nisn' => ['nullable', 'string', 'max:20'],
            'nik' => ['nullable', 'string', 'max:20'],
            'gender' => ['nullable', 'in:L,P'],
            'birth_place' => ['nullable', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'previous_school' => ['nullable', 'string', 'max:255'],
            'parent_name' => ['nullable', 'string', 'max:255'],
            'parent_phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:1000'],
            'phone' => ['nullable', 'string', 'max:20'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('photo')) {
            $profile = $student->studentProfile;
            if ($profile?->photo_path) {
                Storage::disk('public')->delete($profile->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('siswa-foto', 'public');
        }
        unset($validated['photo']);

        StudentProfile::updateOrCreate(['user_id' => $student->id], $validated);

        return redirect()
            ->route('admin.student-profiles.index')
            ->with('success', "Biodata {$student->name} berhasil diperbarui.");
    }
}