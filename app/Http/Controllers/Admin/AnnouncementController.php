<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function index(Request $request): View
    {
        $status = $request->query('status');

        $announcements = Announcement::with('author')
            ->when($status === 'published', fn ($q) => $q->where('is_published', true))
            ->when($status === 'draft', fn ($q) => $q->where('is_published', false))
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.announcements.index', compact('announcements', 'status'));
    }

    public function create(): View
    {
        return view('admin.announcements.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateAnnouncement($request);
        $validated['created_by'] = auth()->id();
        $validated['target_roles'] = $request->input('target_roles', ['all']);

        if ($validated['is_published'] && empty($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        Announcement::create($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $announcement): View
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        $validated = $this->validateAnnouncement($request);
        $validated['target_roles'] = $request->input('target_roles', ['all']);

        if ($validated['is_published'] && ! $announcement->published_at) {
            $validated['published_at'] = now();
        }

        $announcement->update($validated);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $announcement): RedirectResponse
    {
        $announcement->delete();

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Pengumuman berhasil dihapus.');
    }

    public function toggle(Announcement $announcement): RedirectResponse
    {
        $announcement->is_published = ! $announcement->is_published;

        if ($announcement->is_published && ! $announcement->published_at) {
            $announcement->published_at = now();
        }

        $announcement->save();

        $label = $announcement->is_published ? 'dipublikasikan' : 'disembunyikan';

        return back()->with('success', "Pengumuman berhasil {$label}.");
    }

    private function validateAnnouncement(Request $request): array
    {
        return $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'content'      => ['required', 'string'],
            'priority'     => ['required', 'in:normal,penting,urgent'],
            'is_published' => ['boolean'],
            'published_at' => ['nullable', 'date'],
            'expired_at'   => ['nullable', 'date', 'after:published_at'],
            'target_roles' => ['required', 'array', 'min:1'],
            'target_roles.*' => ['string', 'in:all,siswa,guru,ortu,tu,kepsek'],
        ]);
    }
}
