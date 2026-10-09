<?php

namespace App\Http\Controllers\Admin\StudentActivity;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Extracurricular;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ExtracurricularController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'in:olahraga,seni,teknologi,kepemimpinan,keagamaan,lainnya'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'coach_name'   => ['nullable', 'string', 'max:255'],
            'schedule_day' => ['nullable', 'string', 'max:255'],
            'photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'is_active'    => ['nullable', 'boolean'],
            'order'        => ['nullable', 'integer'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('kesiswaan/ekstrakurikuler', 'public');
        }

        $ekskul = Extracurricular::create([
            'name'         => $validated['name'],
            'category'     => $validated['category'],
            'description'  => $validated['description'] ?? null,
            'coach_name'   => $validated['coach_name'] ?? null,
            'schedule_day' => $validated['schedule_day'] ?? null,
            'photo'        => $photoPath,
            'is_active'    => $request->boolean('is_active', true),
            'order'        => $validated['order'] ?? 0,
        ]);

        AuditLog::log('create', "Menambahkan ekstrakurikuler baru: {$ekskul->name}", $ekskul);

        return redirect()->route('admin.student-activities.index', ['tab' => 'ekskul'])
            ->with('success', 'Ekstrakurikuler berhasil ditambahkan.');
    }

    public function update(Request $request, Extracurricular $extracurricular): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'category'     => ['required', 'string', 'in:olahraga,seni,teknologi,kepemimpinan,keagamaan,lainnya'],
            'description'  => ['nullable', 'string', 'max:2000'],
            'coach_name'   => ['nullable', 'string', 'max:255'],
            'schedule_day' => ['nullable', 'string', 'max:255'],
            'photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_photo' => ['nullable', 'boolean'],
            'is_active'    => ['nullable', 'boolean'],
            'order'        => ['nullable', 'integer'],
        ]);

        $photoPath = $extracurricular->photo;
        if ($request->boolean('remove_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        } elseif ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('kesiswaan/ekstrakurikuler', 'public');
        }

        $extracurricular->update([
            'name'         => $validated['name'],
            'category'     => $validated['category'],
            'description'  => $validated['description'] ?? null,
            'coach_name'   => $validated['coach_name'] ?? null,
            'schedule_day' => $validated['schedule_day'] ?? null,
            'photo'        => $photoPath,
            'is_active'    => $request->boolean('is_active', true),
            'order'        => $validated['order'] ?? 0,
        ]);

        AuditLog::log('update', "Memperbarui ekstrakurikuler: {$extracurricular->name}", $extracurricular);

        return redirect()->route('admin.student-activities.index', ['tab' => 'ekskul'])
            ->with('success', 'Data ekstrakurikuler berhasil diperbarui.');
    }

    public function destroy(Extracurricular $extracurricular): RedirectResponse
    {
        $name = $extracurricular->name;

        if ($extracurricular->photo && Storage::disk('public')->exists($extracurricular->photo)) {
            Storage::disk('public')->delete($extracurricular->photo);
        }

        $extracurricular->delete();

        AuditLog::log('delete', "Menghapus ekstrakurikuler: {$name}");

        return redirect()->route('admin.student-activities.index', ['tab' => 'ekskul'])
            ->with('success', 'Ekstrakurikuler berhasil dihapus.');
    }
}
