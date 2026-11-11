@php
    use App\Models\Announcement;
    $userRole = auth()->user()?->role?->slug ?? '';
    $bannerAnnouncements = Announcement::active()
        ->forRole($userRole)
        ->orderByRaw("CASE priority WHEN 'urgent' THEN 1 WHEN 'penting' THEN 2 ELSE 3 END")
        ->orderByDesc('published_at')
        ->take(3)
        ->get();
@endphp

@if ($bannerAnnouncements->isNotEmpty())
<div class="announcements-wrapper mb-4" role="region" aria-label="Pengumuman Sekolah">
    @foreach ($bannerAnnouncements as $ann)
    <div class="announcement-banner announcement-banner--{{ $ann->priority }} d-flex align-items-start gap-3">
        <div class="announcement-icon-box announcement-icon-box--{{ $ann->priority }}" aria-hidden="true">
            @if ($ann->priority === 'urgent')
                <i class="bi bi-exclamation-octagon-fill"></i>
            @elseif ($ann->priority === 'penting')
                <i class="bi bi-exclamation-triangle-fill"></i>
            @else
                <i class="bi bi-info-circle-fill"></i>
            @endif
        </div>
        <div class="announcement-body flex-grow-1 min-w-0">
            <div class="announcement-header d-flex flex-wrap align-items-center gap-2 mb-1">
                <h5 class="announcement-title mb-0">{{ $ann->title }}</h5>
                <span class="announcement-badge announcement-badge--{{ $ann->priority }}">
                    @if ($ann->priority === 'urgent')
                        <i class="bi bi-shield-fill-exclamation me-1"></i>{{ $ann->priorityLabel() }}
                    @elseif ($ann->priority === 'penting')
                        <i class="bi bi-star-fill me-1"></i>{{ $ann->priorityLabel() }}
                    @else
                        <i class="bi bi-info-circle me-1"></i>{{ $ann->priorityLabel() }}
                    @endif
                </span>
                @if ($ann->published_at)
                    <span class="announcement-date ms-auto">
                        <i class="bi bi-calendar3 me-1"></i>{{ $ann->published_at->translatedFormat('d F Y') }}
                    </span>
                @endif
            </div>
            <div class="announcement-content">{!! nl2br(e($ann->content)) !!}</div>
        </div>
    </div>
    @endforeach
</div>

<style>
.announcements-wrapper {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
    width: 100%;
}

.announcement-banner {
    background-color: #ffffff !important;
    border-radius: 10px;
    padding: 1rem 1.25rem;
    box-shadow: 0 2px 8px rgba(15, 23, 42, 0.05);
    border: 1px solid #e2e8f0;
    border-left: 4px solid #3b82f6;
    transition: box-shadow 0.2s ease, border-color 0.2s ease;
}

.announcement-banner:hover {
    box-shadow: 0 4px 12px rgba(15, 23, 42, 0.08);
}

.announcement-banner--normal {
    border-color: #dbeafe;
    border-left-color: #2563eb !important;
    background-color: #ffffff !important;
}

.announcement-icon-box--normal {
    background-color: #eff6ff;
    color: #1d4ed8;
    border: 1px solid #bfdbfe;
}

.announcement-badge--normal {
    background-color: #e0f2fe !important;
    color: #0369a1 !important;
    border: 1px solid #bae6fd !important;
}

.announcement-banner--penting {
    border-color: #fef3c7;
    border-left-color: #d97706 !important;
    background-color: #ffffff !important;
}

.announcement-icon-box--penting {
    background-color: #fffbeb;
    color: #b45309;
    border: 1px solid #fde68a;
}

.announcement-badge--penting {
    background-color: #fef3c7 !important;
    color: #92400e !important;
    border: 1px solid #fde68a !important;
}

.announcement-banner--urgent {
    border-color: #fee2e2;
    border-left-color: #dc2626 !important;
    background-color: #ffffff !important;
}

.announcement-icon-box--urgent {
    background-color: #fef2f2;
    color: #b91c1c;
    border: 1px solid #fecaca;
}

.announcement-badge--urgent {
    background-color: #fee2e2 !important;
    color: #991b1b !important;
    border: 1px solid #fecaca !important;
}

.announcement-icon-box {
    width: 38px;
    height: 38px;
    border-radius: 8px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 1.15rem;
    flex-shrink: 0;
    margin-top: 1px;
}

.announcement-title {
    font-size: 0.925rem !important;
    font-weight: 700 !important;
    color: #0f172a !important;
    letter-spacing: -0.01em;
}

.announcement-badge {
    font-size: 0.7rem !important;
    font-weight: 700 !important;
    padding: 0.2rem 0.55rem !important;
    border-radius: 9999px !important;
    line-height: 1.2 !important;
    display: inline-flex;
    align-items: center;
    letter-spacing: 0.02em;
}

.announcement-date {
    font-size: 0.75rem !important;
    color: #64748b !important;
    font-weight: 500 !important;
    line-height: 1;
}

.announcement-content {
    font-size: 0.85rem !important;
    color: #334155 !important;
    line-height: 1.6 !important;
    margin-top: 0.35rem;
    word-break: break-word;
}
</style>
@endif
