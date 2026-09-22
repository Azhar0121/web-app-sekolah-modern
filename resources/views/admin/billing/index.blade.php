@extends('layouts.admin')

@section('title', 'Kelola Tagihan Siswa')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<style>
.billing-page { max-width: 1100px; }
.filter-card {
    background: #fff;
    border-radius: 1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
    padding: 1.25rem 1.5rem;
    margin-bottom: 1.5rem;
}
.filter-title { font-size: .75rem; font-weight: 600; letter-spacing: .06em; color: #6b7280; margin-bottom: .75rem; }
.status-tabs { display: flex; gap: .5rem; flex-wrap: wrap; }
.status-tab {
    padding: .4rem .9rem; border-radius: 2rem; font-size: .82rem;
    font-weight: 500; border: 1.5px solid #e5e7eb;
    background: #f9fafb; color: #374151;
    text-decoration: none; transition: all .15s;
}
.status-tab:hover { border-color: #2d6a9f; color: #1d4ed8; background: #eff6ff; }
.status-tab.active { border-color: #2d6a9f; background: #2d6a9f; color: #fff; }
.status-tab.active-warning { border-color: #d97706; background: #d97706; color: #fff; }
.status-tab.active-success { border-color: #16a34a; background: #16a34a; color: #fff; }

.student-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1rem; }
.student-bill-card {
    background: #fff; border-radius: 1rem;
    box-shadow: 0 1px 4px rgba(0,0,0,.07);
    overflow: hidden; transition: box-shadow .2s, transform .2s;
}
.student-bill-card:hover { box-shadow: 0 6px 20px rgba(0,0,0,.1); transform: translateY(-2px); }
.bill-card-header {
    padding: 1rem 1.25rem .75rem;
    display: flex; align-items: center; gap: .75rem;
    border-bottom: 1px solid #f3f4f6;
}
.bill-avatar {
    width: 42px; height: 42px; border-radius: 50%;
    background: linear-gradient(135deg, #2d6a9f, #3b8abf);
    color: #fff; font-weight: 700; font-size: 1rem;
    display: flex; align-items: center; justify-content: center; flex-shrink: 0;
}
.bill-avatar.clean { background: linear-gradient(135deg, #16a34a, #22c55e); }
.bill-name { font-weight: 600; font-size: .95rem; margin: 0; }
.bill-meta { font-size: .75rem; color: #6b7280; }
.bill-card-body { padding: 1rem 1.25rem; }
.bill-stat { display: flex; justify-content: space-between; align-items: center; margin-bottom: .5rem; }
.bill-stat:last-child { margin-bottom: 0; }
.bill-stat-label { font-size: .78rem; color: #6b7280; }
.bill-stat-value { font-size: .9rem; font-weight: 600; }
.text-unpaid { color: #dc2626; }
.text-paid { color: #16a34a; }
.bill-card-footer { padding: .75rem 1.25rem; background: #f9fafb; border-top: 1px solid #f3f4f6; }

.empty-state { text-align: center; padding: 4rem 2rem; background: #fff; border-radius: 1rem; box-shadow: 0 1px 4px rgba(0,0,0,.07); }
.empty-state .empty-icon { font-size: 3rem; margin-bottom: 1rem; }

.results-bar { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
.results-bar small { color: #6b7280; }
</style>

<div class="billing-page">

    {{-- HEADER --}}
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold mb-0">Kelola Tagihan Siswa</h4>
            <small class="text-muted">Lihat, tambah, dan konfirmasi pembayaran tagihan siswa</small>
        </div>
    </div>

    {{-- FILTER PANEL --}}
    <div class="filter-card">
        <form action="{{ route('admin.billing.index') }}" method="GET" id="filter-form">

            <div class="row g-3 mb-3">
                {{-- Cari nama --}}
                <div class="col-md-5">
                    <label class="filter-title">CARI NAMA SISWA</label>
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0">
                            <i class="bi bi-search text-muted"></i>
                        </span>
                        <input type="text" name="search" class="form-control border-start-0 ps-0"
                               placeholder="Ketik nama siswa..." value="{{ $search }}">
                    </div>
                </div>

                {{-- Filter kelas --}}
                <div class="col-md-4">
                    <label class="filter-title">FILTER KELAS</label>
                    <select name="classroom_id" class="form-select" onchange="document.getElementById('filter-form').submit()">
                        <option value="">— Semua Kelas —</option>
                        @foreach ($classrooms as $cls)
                            <option value="{{ $cls->id }}" {{ $classroomId == $cls->id ? 'selected' : '' }}>
                                {{ $cls->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Tombol cari --}}
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-funnel me-1"></i> Terapkan Filter
                    </button>
                </div>
            </div>

            {{-- Tab status tagihan --}}
            <div>
                <div class="filter-title">STATUS TAGIHAN</div>
                <div class="status-tabs">
                    <a href="{{ request()->fullUrlWithQuery(['has_unpaid' => '']) }}"
                       class="status-tab {{ !$statusFilter ? 'active' : '' }}">
                        Semua Siswa
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['has_unpaid' => 'yes']) }}"
                       class="status-tab {{ $statusFilter === 'yes' ? 'active-warning' : '' }}">
                        <i class="bi bi-exclamation-circle me-1"></i>
                        Ada Tunggakan
                    </a>
                    <a href="{{ request()->fullUrlWithQuery(['has_unpaid' => 'no']) }}"
                       class="status-tab {{ $statusFilter === 'no' ? 'active-success' : '' }}">
                        <i class="bi bi-check-circle me-1"></i>
                        Lunas Semua
                    </a>
                </div>
            </div>

        </form>
    </div>

    {{-- HASIL --}}
    <div class="results-bar">
        <small>
            Menampilkan <strong>{{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}</strong>
            dari <strong>{{ $students->total() }}</strong> siswa
            @if ($search) • pencarian "<strong>{{ $search }}</strong>" @endif
            @if ($classroomId)
                • kelas <strong>{{ $classrooms->firstWhere('id', $classroomId)?->name }}</strong>
            @endif
        </small>

        @if ($search || $classroomId || $statusFilter)
            <a href="{{ route('admin.billing.index') }}" class="btn btn-sm btn-outline-secondary">
                <i class="bi bi-x-circle me-1"></i> Reset Filter
            </a>
        @endif
    </div>

    {{-- GRID SISWA --}}
    @if ($students->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">🔍</div>
            <h5 class="fw-bold mb-2">Tidak ada siswa ditemukan</h5>
            <p class="text-muted mb-3">Coba ubah filter atau kata kunci pencarian.</p>
            <a href="{{ route('admin.billing.index') }}" class="btn btn-outline-secondary btn-sm">Reset Filter</a>
        </div>
    @else
        <div class="student-grid">
            @foreach ($students as $student)
            @php
                $unpaidCount = $student->billingRecords->count();
                $unpaidTotal = $student->billingRecords->sum('amount');
                $classroom   = $student->classroomStudents->first()?->classroom;
            @endphp
            <div class="student-bill-card">
                <div class="bill-card-header">
                    <div class="bill-avatar {{ $unpaidCount === 0 ? 'clean' : '' }}">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="bill-name text-truncate">{{ $student->name }}</div>
                        <div class="bill-meta">
                            {{ $classroom?->name ?? 'Kelas belum ditentukan' }}
                            @if ($student->studentProfile?->nisn)
                                &nbsp;·&nbsp; NISN: {{ $student->studentProfile->nisn }}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="bill-card-body">
                    <div class="bill-stat">
                        <span class="bill-stat-label">Tagihan Belum Lunas</span>
                        @if ($unpaidCount > 0)
                            <span class="bill-stat-value text-unpaid">
                                Rp {{ number_format($unpaidTotal, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="bill-stat-value text-paid">
                                <i class="bi bi-check-circle-fill me-1"></i> Lunas
                            </span>
                        @endif
                    </div>
                    <div class="bill-stat">
                        <span class="bill-stat-label">Jumlah Item Belum Lunas</span>
                        <span>
                            @if ($unpaidCount > 0)
                                <span class="badge text-bg-warning">{{ $unpaidCount }} item</span>
                            @else
                                <span class="badge text-bg-success">0 item</span>
                            @endif
                        </span>
                    </div>
                </div>

                <div class="bill-card-footer">
                    <a href="{{ route('admin.billing.show', $student->id) }}"
                       class="btn btn-sm w-100 {{ $unpaidCount > 0 ? 'btn-warning' : 'btn-outline-secondary' }}">
                        <i class="bi bi-receipt me-1"></i>
                        Kelola Tagihan
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $students->links() }}
        </div>
    @endif

</div>
@endsection
