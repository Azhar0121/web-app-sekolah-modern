<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\StudentProfile;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(): View
    {
        $student = auth()->user();
        $profile = $student->studentProfile ?? new StudentProfile(['user_id' => $student->id]);
        $classroom = $student->currentClassroom();

        return view('siswa.profile.edit', compact('student', 'profile', 'classroom'));
    }

    public function update(Request $request): RedirectResponse
    {
        $student = auth()->user();

        $validated = $request->validate([
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

        return back()->with('success', 'Biodata Anda berhasil diperbarui.');
    }
}