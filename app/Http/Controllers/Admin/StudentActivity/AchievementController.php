<?php

namespace App\Http\Controllers\Admin\StudentActivity;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\StudentAchievement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'category'      => ['required', 'string', 'in:akademik,non-akademik,seni,olahraga'],
            'level'         => ['required', 'string', 'in:sekolah,kota,provinsi,nasional,internasional'],
            'year'          => ['nullable', 'integer', 'min:2000', 'max:2099'],
            'organizer'     => ['nullable', 'string', 'max:255'],
            'student_name'  => ['required', 'string', 'max:255'],
            'student_class' => ['nullable', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'is_featured'   => ['nullable', 'boolean'],
            'is_active'     => ['nullable', 'boolean'],
            'order'         => ['nullable', 'integer'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('kesiswaan/prestasi', 'public');
        }

        $achievement = StudentAchievement::create([
            'title'         => $validated['title'],
            'category'      => $validated['category'],
            'level'         => $validated['level'],
            'year'          => $validated['year'] ?? (int) date('Y'),
            'organizer'     => $validated['organizer'] ?? null,
            'student_name'  => $validated['student_name'],
            'student_class' => $validated['student_class'] ?? null,
            'description'   => $validated['description'] ?? null,
            'photo'         => $photoPath,
            'is_featured'   => $request->boolean('is_featured'),
            'is_active'     => $request->boolean('is_active', true),
            'order'         => $validated['order'] ?? 0,
        ]);

        AuditLog::log('create', "Menambahkan rekam prestasi siswa: {$achievement->title}", $achievement);

        return redirect()->route('admin.student-activities.index', ['tab' => 'prestasi'])
            ->with('success', 'Prestasi siswa berhasil ditambahkan.');
    }

    public function update(Request $request, StudentAchievement $achievement): RedirectResponse
    {
        $validated = $request->validate([
            'title'         => ['required', 'string', 'max:255'],
            'category'      => ['required', 'string', 'in:akademik,non-akademik,seni,olahraga'],
            'level'         => ['required', 'string', 'in:sekolah,kota,provinsi,nasional,internasional'],
            'year'          => ['nullable', 'integer', 'min:2000', 'max:2099'],
            'organizer'     => ['nullable', 'string', 'max:255'],
            'student_name'  => ['required', 'string', 'max:255'],
            'student_class' => ['nullable', 'string', 'max:100'],
            'description'   => ['nullable', 'string', 'max:2000'],
            'photo'         => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_photo'  => ['nullable', 'boolean'],
            'is_featured'   => ['nullable', 'boolean'],
            'is_active'     => ['nullable', 'boolean'],
            'order'         => ['nullable', 'integer'],
        ]);

        $photoPath = $achievement->photo;
        if ($request->boolean('remove_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        } elseif ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('kesiswaan/prestasi', 'public');
        }

        $achievement->update([
            'title'         => $validated['title'],
            'category'      => $validated['category'],
            'level'         => $validated['level'],
            'year'          => $validated['year'] ?? $achievement->year,
            'organizer'     => $validated['organizer'] ?? null,
            'student_name'  => $validated['student_name'],
            'student_class' => $validated['student_class'] ?? null,
            'description'   => $validated['description'] ?? null,
            'photo'         => $photoPath,
            'is_featured'   => $request->boolean('is_featured'),
            'is_active'     => $request->boolean('is_active', true),
            'order'         => $validated['order'] ?? 0,
        ]);

        AuditLog::log('update', "Memperbarui rekam prestasi: {$achievement->title}", $achievement);

        return redirect()->route('admin.student-activities.index', ['tab' => 'prestasi'])
            ->with('success', 'Data prestasi siswa berhasil diperbarui.');
    }

    public function destroy(StudentAchievement $achievement): RedirectResponse
    {
        $title = $achievement->title;

        if ($achievement->photo && Storage::disk('public')->exists($achievement->photo)) {
            Storage::disk('public')->delete($achievement->photo);
        }

        $achievement->delete();

        AuditLog::log('delete', "Menghapus rekam prestasi: {$title}");

        return redirect()->route('admin.student-activities.index', ['tab' => 'prestasi'])
            ->with('success', 'Data prestasi siswa berhasil dihapus.');
    }
}
