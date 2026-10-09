<?php

namespace App\Http\Controllers\Admin\StudentActivity;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\OsisActivity;
use App\Models\OsisMember;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OsisController extends Controller
{
    public function storeActivity(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:255'],
            'date'        => ['nullable', 'date'],
            'description' => ['nullable', 'string', 'max:2000'],
            'photo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'gallery'     => ['nullable', 'array'],
            'gallery.*'   => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'is_featured' => ['nullable', 'boolean'],
            'is_active'   => ['nullable', 'boolean'],
            'order'       => ['nullable', 'integer'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('kesiswaan/osis/activities', 'public');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $galleryPaths[] = $file->store('kesiswaan/osis/activities/gallery', 'public');
                }
            }
        }

        $activity = OsisActivity::create([
            'title'       => $validated['title'],
            'date'        => $validated['date'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo'       => $photoPath,
            'gallery'     => !empty($galleryPaths) ? $galleryPaths : null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active', true),
            'order'       => $validated['order'] ?? 0,
        ]);

        AuditLog::log('create', "Menambahkan agenda/kegiatan OSIS: {$activity->title}", $activity);

        return redirect()->route('admin.student-activities.index', ['tab' => 'osis'])
            ->with('success', 'Kegiatan OSIS berhasil ditambahkan.');
    }

    public function updateActivity(Request $request, OsisActivity $activity): RedirectResponse
    {
        $validated = $request->validate([
            'title'            => ['required', 'string', 'max:255'],
            'date'             => ['nullable', 'date'],
            'description'      => ['nullable', 'string', 'max:2000'],
            'photo'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'gallery'          => ['nullable', 'array'],
            'gallery.*'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'remove_photo'     => ['nullable', 'boolean'],
            'delete_gallery'   => ['nullable', 'array'],
            'delete_gallery.*' => ['nullable', 'string'],
            'is_featured'      => ['nullable', 'boolean'],
            'is_active'        => ['nullable', 'boolean'],
            'order'            => ['nullable', 'integer'],
        ]);

        $photoPath = $activity->photo;
        if ($request->boolean('remove_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        } elseif ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('kesiswaan/osis/activities', 'public');
        }

        $currentGallery = is_array($activity->gallery) ? $activity->gallery : [];

        // Hapus file galeri yang dicentang
        if ($request->filled('delete_gallery')) {
            $toDelete = (array) $request->input('delete_gallery');
            foreach ($toDelete as $delPath) {
                if (in_array($delPath, $currentGallery)) {
                    if (Storage::disk('public')->exists($delPath)) {
                        Storage::disk('public')->delete($delPath);
                    }
                    $currentGallery = array_values(array_filter($currentGallery, fn ($p) => $p !== $delPath));
                }
            }
        }

        // Tambah file galeri baru
        if ($request->hasFile('gallery')) {
            foreach ($request->file('gallery') as $file) {
                if ($file->isValid()) {
                    $currentGallery[] = $file->store('kesiswaan/osis/activities/gallery', 'public');
                }
            }
        }

        $activity->update([
            'title'       => $validated['title'],
            'date'        => $validated['date'] ?? null,
            'description' => $validated['description'] ?? null,
            'photo'       => $photoPath,
            'gallery'     => !empty($currentGallery) ? $currentGallery : null,
            'is_featured' => $request->boolean('is_featured'),
            'is_active'   => $request->boolean('is_active', true),
            'order'       => $validated['order'] ?? 0,
        ]);

        AuditLog::log('update', "Memperbarui agenda/kegiatan OSIS: {$activity->title}", $activity);

        return redirect()->route('admin.student-activities.index', ['tab' => 'osis'])
            ->with('success', 'Kegiatan OSIS berhasil diperbarui.');
    }

    public function destroyActivity(OsisActivity $activity): RedirectResponse
    {
        $title = $activity->title;

        if ($activity->photo && Storage::disk('public')->exists($activity->photo)) {
            Storage::disk('public')->delete($activity->photo);
        }

        if (is_array($activity->gallery)) {
            foreach ($activity->gallery as $gFile) {
                if ($gFile && Storage::disk('public')->exists($gFile)) {
                    Storage::disk('public')->delete($gFile);
                }
            }
        }

        $activity->delete();

        AuditLog::log('delete', "Menghapus agenda/kegiatan OSIS: {$title}");

        return redirect()->route('admin.student-activities.index', ['tab' => 'osis'])
            ->with('success', 'Kegiatan OSIS berhasil dihapus.');
    }

    public function storeMember(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'position'    => ['required', 'string', 'max:255'],
            'department'  => ['nullable', 'string', 'max:255'],
            'class_name'  => ['nullable', 'string', 'max:50'],
            'period'      => ['nullable', 'string', 'max:50'],
            'photo'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'is_active'   => ['nullable', 'boolean'],
            'order'       => ['nullable', 'integer'],
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('kesiswaan/osis/members', 'public');
        }

        $member = OsisMember::create([
            'name'       => $validated['name'],
            'position'   => $validated['position'],
            'department' => $validated['department'] ?? 'Badan Pengurus Harian (BPH)',
            'class_name' => $validated['class_name'] ?? null,
            'period'     => $validated['period'] ?? '2026/2027',
            'photo'      => $photoPath,
            'is_active'  => $request->boolean('is_active', true),
            'order'      => $validated['order'] ?? 0,
        ]);

        AuditLog::log('create', "Menambahkan pengurus OSIS: {$member->name} ({$member->position})", $member);

        return redirect()->route('admin.student-activities.index', ['tab' => 'osis'])
            ->with('success', 'Anggota pengurus OSIS berhasil ditambahkan.');
    }

    public function updateMember(Request $request, OsisMember $member): RedirectResponse
    {
        $validated = $request->validate([
            'name'         => ['required', 'string', 'max:255'],
            'position'     => ['required', 'string', 'max:255'],
            'department'   => ['nullable', 'string', 'max:255'],
            'class_name'   => ['nullable', 'string', 'max:50'],
            'period'       => ['nullable', 'string', 'max:50'],
            'photo'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_photo' => ['nullable', 'boolean'],
            'is_active'    => ['nullable', 'boolean'],
            'order'        => ['nullable', 'integer'],
        ]);

        $photoPath = $member->photo;
        if ($request->boolean('remove_photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = null;
        } elseif ($request->hasFile('photo')) {
            if ($photoPath && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
            }
            $photoPath = $request->file('photo')->store('kesiswaan/osis/members', 'public');
        }

        $member->update([
            'name'       => $validated['name'],
            'position'   => $validated['position'],
            'department' => $validated['department'] ?? $member->department,
            'class_name' => $validated['class_name'] ?? null,
            'period'     => $validated['period'] ?? $member->period,
            'photo'      => $photoPath,
            'is_active'  => $request->boolean('is_active', true),
            'order'      => $validated['order'] ?? 0,
        ]);

        AuditLog::log('update', "Memperbarui pengurus OSIS: {$member->name}", $member);

        return redirect()->route('admin.student-activities.index', ['tab' => 'osis'])
            ->with('success', 'Data pengurus OSIS berhasil diperbarui.');
    }

    public function destroyMember(OsisMember $member): RedirectResponse
    {
        $name = $member->name;

        if ($member->photo && Storage::disk('public')->exists($member->photo)) {
            Storage::disk('public')->delete($member->photo);
        }

        $member->delete();

        AuditLog::log('delete', "Menghapus pengurus OSIS: {$name}");

        return redirect()->route('admin.student-activities.index', ['tab' => 'osis'])
            ->with('success', 'Data pengurus OSIS berhasil dihapus.');
    }
}