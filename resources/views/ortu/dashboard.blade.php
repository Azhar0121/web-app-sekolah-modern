@extends('layouts.app')

@section('title', 'Portal Orang Tua / Wali')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
.ortu-dashboard { max-width: 960px; margin: 0 auto; padding-bottom: 3rem; }

.ortu-hero {
    background: linear-gradient(135deg, #1e3a5f 0%, #2d6a9f 60%, #3b8abf 100%);
    border-radius: 1.25rem;
    padding: 2.5rem 2.5rem 2rem;
    color: #fff;
    position: relative;
    overflow: hidden;
    margin-bottom: 1.5rem;
}
.ortu-hero::before {
    content: '';
    position: absolute; inset: 0;
    background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Ccircle cx='30' cy='30' r='20'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
}
.ortu-hero-label {
    display: inline-flex; align-items: center; gap: 0.5rem;
    background: rgba(255,255,255,.15); border-radius: 2rem;
    padding: 0.3rem 1rem; font-size: 0.72rem;
    letter-spacing: .08em; font-weight: 600;
    margin-bottom: 1rem;
}
.ortu-hero h1 { font-size: 1.7rem; font-weight: 700; margin-bottom: .5rem; }
.ortu-hero h1 span { color: #93c5fd; }
.ortu-hero p { opacity: .8; font-size: .95rem; margin-bottom: 1.5rem; }
.ortu-hero-meta {
    display: flex; gap: 1.5rem; flex-wrap: wrap;
    padding-top: 1.25rem;
    border-top: 1px solid rgba(255,255,255,.15);
}
.ortu-meta-item { display: flex; align-items: center; gap: .5rem; font-size: .85rem; }
.ortu-meta-item i { opacity: .7; }
.ortu-hero-icon {
    position: absolute; right: 2rem; top: 50%; transform: translateY(-50%);
    font-size: 6rem; opacity: .08; pointer-events: none;
}

.ortu-quick-actions {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .75rem;
    margin-bottom: 1.5rem;
}
@media (min-width: 576px) { .ortu-quick-actions { grid-template-columns: repeat(4, 1fr); } }
.ortu-quick-card {
    background: #fff; border-radius: 1rem;
    padding: 1.25rem 1rem; text-align: center;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
    text-decoration: none; color: inherit;
    transition: transform .2s, box-shadow .2s;
    display: flex; flex-direction: column; align-items: center; gap: .5rem;
}
.ortu-quick-card:hover { transform: translateY(-3px); box-shadow: 0 6px 20px rgba(0,0,0,.1); color: inherit; }
.ortu-quick-icon {
    width: 48px; height: 48px; border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.3rem;
}
.qi-attendance  { background: #dbeafe; color: #1d4ed8; }
.qi-grade       { background: #dcfce7; color: #15803d; }
.qi-billing     { background: #fef3c7; color: #b45309; }
.qi-izin        { background: #fce7f3; color: #be185d; }
.qi-rapor       { background: #ede9fe; color: #6d28d9; }
.ortu-quick-card strong { font-size: .82rem; font-weight: 600; }
.ortu-quick-card small { font-size: .72rem; color: #6b7280; }

.child-card {
    background: #fff; border-radius: 1.25rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
    overflow: hidden; margin-bottom: 1.25rem;
    transition: box-shadow .2s;
}
.child-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.1); }
.child-card-header {
    padding: 1.25rem 1.5rem;
    background: linear-gradient(90deg, #f0f9ff, #e0f2fe);
    display: flex; align-items: center; gap: 1rem;
}
.child-avatar {
    width: 52px; height: 52px; border-radius: 50%;
    background: linear-gradient(135deg, #2d6a9f, #3b8abf);
    color: #fff; font-size: 1.3rem; font-weight: 700;
    display: flex; align-items: center; justify-content: center;
    flex-shrink: 0;
}
.child-info h5 { margin: 0; font-weight: 700; font-size: 1.05rem; }
.child-info span { font-size: .82rem; color: #64748b; }
.child-badges { margin-left: auto; display: flex; gap: .4rem; flex-wrap: wrap; justify-content: flex-end; }
.child-card-body { padding: 1.25rem 1.5rem; }
.child-action-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: .75rem;
}
@media (min-width: 576px) { .child-action-grid { grid-template-columns: repeat(3, 1fr); } }
.child-action-btn {
    display: flex; align-items: center; gap: .6rem;
    padding: .75rem 1rem; border-radius: .75rem;
    border: 1.5px solid #e5e7eb; background: #f9fafb;
    text-decoration: none; color: inherit; font-size: .85rem;
    font-weight: 500; transition: all .2s;
}
.child-action-btn:hover { border-color: #2d6a9f; background: #eff6ff; color: #1d4ed8; }
.child-action-btn i { font-size: 1.1rem; }
.btn-presensi i { color: #1d4ed8; }
.btn-nilai i { color: #15803d; }
.btn-tagihan i { color: #b45309; }
.btn-rapor i { color: #6d28d9; }

.ortu-empty {
    text-align: center; padding: 4rem 2rem;
    background: #fff; border-radius: 1.25rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
}
.ortu-empty .empty-icon { font-size: 3.5rem; margin-bottom: 1rem; }
</style>

@include('layouts.partials.announcements-banner')

<div class="ortu-dashboard">

    <div class="ortu-hero">
        <div class="ortu-hero-label">
            <i class="bi bi-shield-check"></i>
            PORTAL ORANG TUA / WALI
        </div>

        <h1>
            Selamat datang,
            <span>{{ auth()->user()->name }}</span>
        </h1>

        <p>
            Pantau perkembangan akademik, kehadiran, tagihan,
            dan aktivitas anak Anda dari satu tempat.
        </p>

        <div class="ortu-hero-meta">
            <div class="ortu-meta-item">
                <i class="bi bi-people-fill"></i>
                <span>
                    <strong>{{ $children->count() }}</strong>
                    {{ $children->count() === 1 ? 'anak terdaftar' : 'anak terdaftar' }}
                </span>
            </div>
            @if ($totalPendingLeave > 0)
            <div class="ortu-meta-item">
                <i class="bi bi-hourglass-split"></i>
                <span><strong>{{ $totalPendingLeave }}</strong> pengajuan izin menunggu</span>
            </div>
            @endif
            <div class="ortu-meta-item">
                <i class="bi bi-calendar3"></i>
                <span>{{ now()->translatedFormat('l, d F Y') }}</span>
            </div>
        </div>

        <i class="bi bi-house-heart-fill ortu-hero-icon"></i>
    </div>

    <div class="ortu-quick-actions">
        <a href="{{ route('ortu.leave-requests.index') }}" class="ortu-quick-card">
            <div class="ortu-quick-icon qi-izin"><i class="bi bi-envelope-paper-fill"></i></div>
            <strong>Ajukan Izin</strong>
            <small>Kirim surat izin anak</small>
        </a>
        <a href="{{ route('ortu.communication.index') }}" class="ortu-quick-card">
            <div class="ortu-quick-icon qi-leave" style="background:#e0f2fe; color:#0369a1;"><i class="bi bi-chat-dots-fill"></i></div>
            <strong>Konsultasi Guru</strong>
            <small>Pesan terarah dengan guru</small>
        </a>
        <a href="{{ route('ortu.leave-requests.index') }}" class="ortu-quick-card">
            <div class="ortu-quick-icon qi-attendance"><i class="bi bi-calendar-check-fill"></i></div>
            <strong>Riwayat Izin</strong>
            <small>Lihat pengajuan izin</small>
        </a>
        @if ($children->isNotEmpty())
        <a href="{{ route('ortu.schedule.index', $children->first()) }}" class="ortu-quick-card">
            <div class="ortu-quick-icon qi-attendance" style="background:#e0e7ff; color:#4338ca;"><i class="bi bi-calendar3"></i></div>
            <strong>Jadwal</strong>
            <small>Jadwal pelajaran anak</small>
        </a>
        <a href="{{ route('ortu.attendance.index', $children->first()) }}" class="ortu-quick-card">
            <div class="ortu-quick-icon qi-attendance"><i class="bi bi-calendar-check-fill"></i></div>
            <strong>Presensi</strong>
            <small>Riwayat kehadiran</small>
        </a>
        <a href="{{ route('ortu.grades.index', $children->first()) }}" class="ortu-quick-card">
            <div class="ortu-quick-icon qi-grade"><i class="bi bi-graph-up-arrow"></i></div>
            <strong>Nilai</strong>
            <small>Rapor akademik</small>
        </a>
        @endif
    </div>

    @if ($children->isEmpty())
        <div class="ortu-empty">
            <div class="empty-icon">👨‍👩‍👧</div>
            <h5 class="fw-bold mb-2">Belum ada data anak</h5>
            <p class="text-muted mb-0">
                Akun Anda belum tertaut ke data siswa.<br>
                Hubungi Tata Usaha sekolah untuk penghubungan akun.
            </p>
        </div>
    @else
        <h6 class="fw-semibold text-muted mb-3" style="letter-spacing:.05em;">DATA ANAK</h6>

        @foreach ($children as $child)
        <div class="child-card">
            <div class="child-card-header">
                <div class="child-avatar">
                    {{ strtoupper(substr($child->name, 0, 1)) }}
                </div>
                <div class="child-info">
                    <h5>{{ $child->name }}</h5>
                    <span>
                        <i class="bi bi-building me-1"></i>
                        {{ $child->classroomForDisplay?->name ?? 'Kelas belum ditentukan' }}
                    </span>
                </div>
                <div class="child-badges">
                    @if ($child->unpaidBillingCount > 0)
                        <span class="badge text-bg-warning">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            {{ $child->unpaidBillingCount }} tagihan belum lunas
                        </span>
                    @else
                        <span class="badge text-bg-success">
                            <i class="bi bi-check-circle me-1"></i>
                            Tagihan lunas
                        </span>
                    @endif
                    @if ($child->pendingLeaveCount > 0)
                        <span class="badge text-bg-secondary">
                            {{ $child->pendingLeaveCount }} izin menunggu
                        </span>
                    @endif
                </div>
            </div>

            <div class="child-card-body">
                <div class="child-action-grid">
                    <a href="{{ route('ortu.schedule.index', $child) }}" class="child-action-btn" style="background:#eff6ff; color:#1e40af; border:1px solid #dbeafe;">
                        <i class="bi bi-calendar3" style="color:#2563eb;"></i>
                        <span>Jadwal Pelajaran</span>
                    </a>
                    <a href="{{ route('ortu.attendance.index', $child) }}" class="child-action-btn btn-presensi">
                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Riwayat Presensi</span>
                    </a>
                    <a href="{{ route('ortu.grades.index', $child) }}" class="child-action-btn btn-nilai">
                        <i class="bi bi-graph-up-arrow"></i>
                        <span>Nilai Rapor</span>
                    </a>
                    <a href="{{ route('ortu.billing.index', $child) }}" class="child-action-btn btn-tagihan">
                        <i class="bi bi-receipt"></i>
                        <span>
                            Status Tagihan
                            @if ($child->unpaidBillingCount > 0)
                                <span class="badge text-bg-warning ms-1" style="font-size:.65rem;">{{ $child->unpaidBillingCount }}</span>
                            @endif
                        </span>
                    </a>
                    <a href="{{ route('ortu.report-cards.index', $child) }}" class="child-action-btn btn-rapor">
                        <i class="bi bi-file-earmark-pdf-fill"></i>
                        <span>Rapor Digital</span>
                    </a>
                    <a href="{{ route('ortu.communication.index') }}" class="child-action-btn" style="background:#f0fdf4; color:#15803d; border:1px solid #dcfce7;">
                        <i class="bi bi-chat-dots-fill" style="color:#16a34a;"></i>
                        <span>Konsultasi Guru</span>
                    </a>
                    <a href="{{ route('ortu.leave-requests.create') }}" class="child-action-btn">
                        <i class="bi bi-envelope-paper" style="color:#be185d;"></i>
                        <span>Ajukan Izin</span>
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    @endif

</div>
@endsection
