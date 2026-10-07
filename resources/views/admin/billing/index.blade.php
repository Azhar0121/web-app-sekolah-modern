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


</div>

@endsection
