@extends('layouts.admin')

@section('title', 'Kelola Tagihan Siswa')

@section('content')

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="{{ asset('css/admin/billing/index.css') }}">

<div class="billing-page">


{{-- HEADER --}}
<div class="billing-hero">

    <div class="billing-hero-content">

        <div class="billing-hero-icon">
            <i class="bi bi-receipt-cutoff"></i>
        </div>

        <div>
            <span class="billing-eyebrow">
                KEUANGAN SISWA
            </span>

            <h1>Kelola Tagihan Siswa</h1>

            <p>
                Lihat, tambah, dan konfirmasi pembayaran tagihan siswa
                dengan lebih mudah dan terstruktur.
            </p>
        </div>

    </div>

    {{-- ACTION BUTTONS DI HERO (TERINTEGRASI & PROFESIONAL) --}}
    <div class="billing-hero-actions">
        <button type="button" class="btn-hero-action btn-hero-primary" data-bs-toggle="modal" data-bs-target="#batchBillingModal">
            <i class="bi bi-collection-fill"></i>
            <span>Terbitkan Tagihan Massal</span>
        </button>

        <a href="{{ route('admin.billing.export', request()->query()) }}" class="btn-hero-action btn-hero-secondary" title="Unduh data tagihan dalam format CSV/Excel">
            <i class="bi bi-file-earmark-excel-fill"></i>
            <span>Ekspor CSV</span>
        </a>
    </div>

</div>


{{-- FILTER --}}
<div class="billing-filter-card">

    <div class="billing-filter-header">

        <div class="billing-filter-icon">
            <i class="bi bi-funnel-fill"></i>
        </div>

        <div>
            <h2>Filter Data Siswa</h2>
            <p>Gunakan pencarian atau filter kelas untuk menemukan siswa.</p>
        </div>

    </div>


    <form
        action="{{ route('admin.billing.index') }}"
        method="GET"
        id="filter-form"
    >

        <div class="billing-filter-grid">

            {{-- CARI --}}
            <div class="billing-field billing-search-field">

                <label for="search">
                    CARI NAMA SISWA
                </label>

                <div class="billing-input-wrap">

                    <i class="bi bi-search"></i>

                    <input
                        type="text"
                        id="search"
                        name="search"
                        placeholder="Ketik nama siswa..."
                        value="{{ $search }}"
                    >

                </div>

            </div>


            {{-- KELAS --}}
            <div class="billing-field">

                <label for="classroom_id">
                    FILTER KELAS
                </label>

                <div class="billing-select-wrap">

                    <select
                        name="classroom_id"
                        id="classroom_id"
                        onchange="document.getElementById('filter-form').submit()"
                    >

                        <option value="">Semua Kelas</option>

                        @foreach ($classrooms as $cls)

                            <option
                                value="{{ $cls->id }}"
                                {{ $classroomId == $cls->id ? 'selected' : '' }}
                            >
                                {{ $cls->name }}
                            </option>

                        @endforeach

                    </select>

                    <i class="bi bi-chevron-down"></i>

                </div>

            </div>


            {{-- BUTTON --}}
            <div class="billing-filter-action">

                <button type="submit" class="btn-apply-filter">
                    <i class="bi bi-funnel"></i>
                    Terapkan Filter
                </button>

            </div>

        </div>


        {{-- STATUS --}}
        <div class="billing-status-filter">

            <div class="billing-status-title">
                STATUS TAGIHAN
            </div>

            <div class="status-tabs">

                <a
                    href="{{ request()->fullUrlWithQuery(['has_unpaid' => '']) }}"
                    class="status-tab {{ !$statusFilter ? 'active' : '' }}"
                >
                    <i class="bi bi-people-fill"></i>
                    Semua Siswa
                </a>


                <a
                    href="{{ request()->fullUrlWithQuery(['has_unpaid' => 'yes']) }}"
                    class="status-tab {{ $statusFilter === 'yes' ? 'active-warning' : '' }}"
                >
                    <i class="bi bi-exclamation-circle-fill"></i>
                    Ada Tunggakan
                </a>


                <a
                    href="{{ request()->fullUrlWithQuery(['has_unpaid' => 'no']) }}"
                    class="status-tab {{ $statusFilter === 'no' ? 'active-success' : '' }}"
                >
                    <i class="bi bi-check-circle-fill"></i>
                    Lunas Semua
                </a>

            </div>

        </div>

    </form>

</div>


{{-- RESULTS BAR --}}
<div class="billing-results-bar">

    <div class="billing-results-info">

        <span class="results-icon">
            <i class="bi bi-list-ul"></i>
        </span>

        <span>
            Menampilkan
            <strong>{{ $students->firstItem() ?? 0 }}–{{ $students->lastItem() ?? 0 }}</strong>
            dari
            <strong>{{ $students->total() }}</strong>
            siswa

            @if ($search)
                <span class="result-filter">
                    · pencarian "<strong>{{ $search }}</strong>"
                </span>
            @endif

            @if ($classroomId)
                <span class="result-filter">
                    · kelas
                    <strong>
                        {{ $classrooms->firstWhere('id', $classroomId)?->name }}
                    </strong>
                </span>
            @endif
        </span>

    </div>


    @if ($search || $classroomId || $statusFilter)

        <a
            href="{{ route('admin.billing.index') }}"
            class="btn-reset-filter"
        >
            <i class="bi bi-arrow-counterclockwise"></i>
            Reset Filter
        </a>

    @endif

</div>


{{-- EMPTY --}}
@if ($students->isEmpty())

    <div class="billing-empty-state">

        <div class="empty-icon">
            <i class="bi bi-search"></i>
        </div>

        <h3>Tidak Ada Siswa Ditemukan</h3>

        <p>
            Tidak ada data siswa yang sesuai dengan filter atau kata kunci pencarian.
        </p>

        <a
            href="{{ route('admin.billing.index') }}"
            class="btn-reset-empty"
        >
            <i class="bi bi-arrow-counterclockwise"></i>
            Reset Filter
        </a>

    </div>

@else

    {{-- STUDENT GRID --}}
    <div class="student-grid">

        @foreach ($students as $student)

            @php
                $unpaidCount = $student->billingRecords->count();
                $unpaidTotal = $student->billingRecords->sum('amount');
                $classroom   = $student->classroomStudents->first()?->classroom;
            @endphp

            <div class="student-bill-card">

                {{-- CARD HEADER --}}
                <div class="bill-card-header">

                    <div class="bill-avatar {{ $unpaidCount === 0 ? 'clean' : '' }}">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>

                    <div class="bill-student-info">

                        <div class="bill-name">
                            {{ $student->name }}
                        </div>

                        <div class="bill-meta">

                            <span>
                                <i class="bi bi-mortarboard"></i>
                                {{ $classroom?->name ?? 'Kelas belum ditentukan' }}
                            </span>

                            @if ($student->studentProfile?->nisn)

                                <span class="bill-meta-divider">·</span>

                                <span>
                                    NISN {{ $student->studentProfile->nisn }}
                                </span>

                            @endif

                        </div>

                    </div>

                </div>


                {{-- CARD BODY --}}
                <div class="bill-card-body">

                    <div class="bill-stat">

                        <div class="bill-stat-label">
                            <i class="bi bi-wallet2"></i>
                            Tagihan Belum Lunas
                        </div>

                        @if ($unpaidCount > 0)

                            <div class="bill-stat-value text-unpaid">
                                Rp {{ number_format($unpaidTotal, 0, ',', '.') }}
                            </div>

                        @else

                            <div class="bill-stat-value text-paid">
                                <i class="bi bi-check-circle-fill"></i>
                                Lunas
                            </div>

                        @endif

                    </div>


                    <div class="bill-stat">

                        <div class="bill-stat-label">
                            <i class="bi bi-receipt"></i>
                            Item Belum Lunas
                        </div>

                        @if ($unpaidCount > 0)

                            <span class="bill-count unpaid-count">
                                {{ $unpaidCount }} item
                            </span>

                        @else

                            <span class="bill-count paid-count">
                                0 item
                            </span>

                        @endif

                    </div>

                </div>


                {{-- CARD FOOTER --}}
                <div class="bill-card-footer">

                    <a
                        href="{{ route('admin.billing.show', $student->id) }}"
                        class="btn-manage-billing {{ $unpaidCount > 0 ? 'has-unpaid' : 'is-clean' }}"
                    >
                        <i class="bi bi-receipt"></i>
                        Kelola Tagihan
                        <i class="bi bi-arrow-right"></i>
                    </a>

                </div>

            </div>

        @endforeach

    </div>


    {{-- PAGINATION --}}
    <div class="billing-pagination">
        {{ $students->links() }}
    </div>

@endif


{{-- =========================================================
     MODAL TERBITKAN TAGIHAN MASSAL PER KELAS / ANGKATAN
========================================================= --}}
<div class="modal fade" id="batchBillingModal" tabindex="-1" aria-labelledby="batchBillingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-bottom">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded p-2 text-white" style="background: #0b294d;">
                        <i class="bi bi-collection-fill"></i>
                    </div>
                    <div>
                        <h5 class="modal-title fw-bold mb-0" id="batchBillingModalLabel">Terbitkan Tagihan Massal</h5>
                        <small class="text-muted">Buat tagihan sekaligus untuk seluruh siswa di kelas terpilih</small>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('admin.billing.batch-store') }}" method="POST">
                @csrf
                <div class="modal-body p-4">

                    {{-- Target Kelas --}}
                    <div class="mb-3">
                        <label for="batch_classroom_id" class="form-label fw-semibold">Target Siswa / Kelas <span class="text-danger">*</span></label>
                        <select name="classroom_id" id="batch_classroom_id" class="form-select" required>
                            <option value="">-- Pilih Target Kelas --</option>
                            <option value="all">★ Seluruh Siswa (Semua Kelas)</option>
                            <optgroup label="Pilih Kelas Tertentu:">
                                @foreach ($classrooms as $cls)
                                    <option value="{{ $cls->id }}">{{ $cls->name }}</option>
                                @endforeach
                            </optgroup>
                        </select>
                        <div class="form-text">Tagihan akan otomatis dibuatkan untuk setiap siswa yang terdaftar di kelas ini.</div>
                    </div>

                    {{-- Tahun Ajaran --}}
                    <div class="mb-3">
                        <label for="batch_academic_year_id" class="form-label fw-semibold">Tahun Ajaran <span class="text-danger">*</span></label>
                        <select name="academic_year_id" id="batch_academic_year_id" class="form-select" required>
                            @if ($activeYear)
                                <option value="{{ $activeYear->id }}" selected>{{ $activeYear->name }} (Tahun Ajaran Aktif)</option>
                            @else
                                <option value="">-- Pilih Tahun Ajaran --</option>
                            @endif
                        </select>
                    </div>

                    {{-- Uraian Tagihan --}}
                    <div class="mb-3">
                        <label for="batch_description" class="form-label fw-semibold">Uraian / Nama Tagihan <span class="text-danger">*</span></label>
                        <input
                            type="text"
                            name="description"
                            id="batch_description"
                            class="form-control"
                            placeholder="Contoh: SPP Bulan November 2026 atau Iuran Komite"
                            required
                            maxlength="255"
                        >
                    </div>

                    {{-- Nominal --}}
                    <div class="mb-3">
                        <label for="batch_amount" class="form-label fw-semibold">Nominal per Siswa (Rp) <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text bg-light fw-bold">Rp</span>
                            <input
                                type="number"
                                name="amount"
                                id="batch_amount"
                                class="form-control"
                                placeholder="Contoh: 250000"
                                min="1"
                                step="1"
                                required
                            >
                        </div>
                    </div>

                    {{-- Jatuh Tempo --}}
                    <div class="mb-3">
                        <label for="batch_due_date" class="form-label fw-semibold">Batas Jatuh Tempo <span class="text-muted fw-normal">(opsional)</span></label>
                        <input
                            type="date"
                            name="due_date"
                            id="batch_due_date"
                            class="form-control"
                            value="{{ date('Y-m-10', strtotime('+1 month')) }}"
                        >
                        <div class="form-text">Contoh standar: tanggal 10 bulan berikutnya.</div>
                    </div>

                    {{-- Catatan --}}
                    <div class="mb-0">
                        <label for="batch_notes" class="form-label fw-semibold">Catatan Tambahan <span class="text-muted fw-normal">(opsional)</span></label>
                        <textarea
                            name="notes"
                            id="batch_notes"
                            rows="2"
                            class="form-control"
                            placeholder="Informasi rekening bank atau catatan untuk orang tua..."
                        ></textarea>
                    </div>

                </div>

                <div class="modal-footer bg-light border-top">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2" onclick="return confirm('Apakah Anda yakin ingin menerbitkan tagihan ini untuk semua siswa di target yang dipilih?');">
                        <i class="bi bi-send-check-fill"></i>
                        Terbitkan Tagihan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</div>

@endsection
