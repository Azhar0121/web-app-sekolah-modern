@php
    use App\Models\Announcement;
    $userRole = auth()->user()?->role?->slug ?? '';
    $bannerAnnouncements = Announcement::active()
        ->forRole($userRole)
        ->orderBy('priority', 'desc') // urgent dulu
        ->orderByDesc('published_at')
        ->take(3)
        ->get();
@endphp

@if ($bannerAnnouncements->isNotEmpty())
<div class="announcements-wrapper mb-4">
    @foreach ($bannerAnnouncements as $ann)
    <div class="announcement-banner priority-{{ $ann->priority }} d-flex align-items-start gap-3">
        <div class="announcement-icon">
            @if ($ann->priority === 'urgent') 🚨
            @elseif ($ann->priority === 'penting') ⚠️
            @else 📢
            @endif
        </div>
        <div class="flex-grow-1">
            <div class="d-flex align-items-center gap-2 mb-1">
                <strong>{{ $ann->title }}</strong>
                <span class="badge {{ $ann->priorityBadgeClass() }} badge-sm">{{ $ann->priorityLabel() }}</span>
                <small class="text-muted ms-auto">{{ $ann->published_at?->translatedFormat('d F Y') }}</small>
            </div>
            <div class="announcement-content">{!! nl2br(e($ann->content)) !!}</div>
        </div>
    </div>
    @endforeach
</div>

<style>
.announcements-wrapper { display: flex; flex-direction: column; gap: 0.5rem; }
.announcement-banner {
    padding: 1rem 1.25rem;
    border-radius: 0.75rem;
    border-left: 4px solid;
    background: #fff;
    box-shadow: 0 1px 3px rgba(0,0,0,.06);
}
.announcement-banner.priority-urgent { border-color: #ef4444; background: #fef2f2; }
.announcement-banner.priority-penting { border-color: #f59e0b; background: #fffbeb; }
.announcement-banner.priority-normal { border-color: #3b82f6; background: #eff6ff; }
.announcement-icon { font-size: 1.2rem; flex-shrink: 0; margin-top: 2px; }
.announcement-content { font-size: 0.9rem; color: #4b5563; line-height: 1.5; }
.badge-sm { font-size: 0.7rem; }
</style>
@endif
