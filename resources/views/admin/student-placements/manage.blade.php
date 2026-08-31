@extends('layouts.admin')

@section('title', 'Kelola Siswa Kelas')

@section('content')

<link rel="stylesheet" href="{{ asset('css/student-placements/manage.css') }}">

<div class="student-manage-page">


{{-- HEADER --}}
<div class="student-manage-header">
    <div class="student-manage-header-content">
        <div class="student-manage-title">
            <span class="student-manage-label">KELOLA PENEMPATAN SISWA</span>
            <h1>Kelola Siswa Kelas</h1>
            <p>
                Atur siswa yang terdaftar pada kelas
                <strong>{{ $classroom->name }}</strong>
                untuk tahun ajaran yang dipilih.
            </p>
        </div>

        <a href="{{ route('admin.student-placements.index', ['academic_year_id' => $selectedYearId]) }}"
           class="student-back-button">
            <span class="back-icon">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2" stroke-linecap="round"
                     stroke-linejoin="round">
                    <path d="M19 12H5"></path>
                    <path d="M12 19l-7-7 7-7"></path>
                </svg>
            </span>
            Kembali
        </a>
    </div>
</div>

{{-- ERROR --}}
@if (session('error'))
    <div class="student-alert">
        <div class="student-alert-icon">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none"
                 stroke="currentColor" stroke-width="2" stroke-linecap="round"
                 stroke-linejoin="round">
                <circle cx="12" cy="12" r="9"></circle>
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
            </svg>
        </div>

        <div>{{ session('error') }}</div>
    </div>
@endif

{{-- FILTER TAHUN AJARAN --}}
<div class="student-filter-card">
    <div class="student-filter-icon">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
             stroke="currentColor" stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round">
            <rect x="3" y="4" width="18" height="17" rx="2"></rect>
            <path d="M16 2v4"></path>
            <path d="M8 2v4"></path>
            <path d="M3 10h18"></path>
        </svg>
    </div>

    <div class="student-filter-content">
        <label for="academic_year_id">Tahun Ajaran</label>

        <form method="GET"
              action="{{ route('admin.student-placements.manage', $classroom) }}">

            <select name="academic_year_id"
                    id="academic_year_id"
                    class="student-year-select"
                    onchange="this.form.submit()">

                @foreach ($academicYears as $year)
                    <option value="{{ $year->id }}"
                            @selected($selectedYearId === $year->id)>
                        {{ $year->name }}
                        @if ($year->is_active)
                            (Aktif)
                        @endif
                    </option>
                @endforeach

            </select>

        </form>
    </div>
</div>

{{-- CONTENT --}}
<div class="student-manage-grid">

    {{-- SISWA TERDAFTAR --}}
    <div class="student-panel registered-panel">

        <div class="student-panel-header">
            <div class="student-panel-heading">
                <div class="student-panel-icon registered-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>

                <div>
                    <h2>Siswa Terdaftar</h2>
                    <span>{{ $enrollments->count() }} siswa di kelas ini</span>
                </div>
            </div>
        </div>

        <div class="student-list">

            @forelse ($enrollments as $enrollment)

                <div class="student-list-item">

                    <div class="student-info">
                        <div class="student-avatar">
                            {{ strtoupper(substr($enrollment->student->name, 0, 1)) }}
                        </div>

                        <div class="student-name">
                            <strong>{{ $enrollment->student->name }}</strong>
                            <span>Siswa terdaftar</span>
                        </div>
                    </div>

                    <form method="POST"
                          action="{{ route('admin.student-placements.destroy', $enrollment) }}"
                          onsubmit="return confirm('Keluarkan {{ $enrollment->student->name }} dari kelas ini?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="remove-student-button">
                            <svg width="15" height="15" viewBox="0 0 24 24"
                                 fill="none" stroke="currentColor"
                                 stroke-width="2" stroke-linecap="round"
                                 stroke-linejoin="round">
                                <polyline points="3 6 5 6 21 6"></polyline>
                                <path d="M19 6l-1 14H6L5 6"></path>
                                <path d="M10 11v6"></path>
                                <path d="M14 11v6"></path>
                                <path d="M9 6V4h6v2"></path>
                            </svg>
                            Keluarkan
                        </button>

                    </form>

                </div>

            @empty

                <div class="student-empty">
                    <div class="student-empty-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8 12h8"></path>
                        </svg>
                    </div>

                    <strong>Belum ada siswa</strong>
                    <span>Belum ada siswa yang ditempatkan di kelas ini.</span>
                </div>

            @endforelse

        </div>

    </div>

    {{-- TAMBAH SISWA --}}
    <div class="student-panel add-panel">

        <div class="student-panel-header">
            <div class="student-panel-heading">

                <div class="student-panel-icon add-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24"
                         fill="none" stroke="currentColor"
                         stroke-width="2" stroke-linecap="round"
                         stroke-linejoin="round">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 8v8"></path>
                        <path d="M8 12h8"></path>
                    </svg>
                </div>

                <div>
                    <h2>Tambah Siswa</h2>
                    <span>Tambahkan siswa ke kelas ini</span>
                </div>

            </div>
        </div>

        <div class="student-add-body">

            @if ($availableStudents->isEmpty())

                <div class="student-empty student-empty-small">

                    <div class="student-empty-icon">
                        <svg width="22" height="22" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <circle cx="12" cy="12" r="9"></circle>
                            <path d="M8 12h8"></path>
                        </svg>
                    </div>

                    <strong>Tidak ada siswa tersedia</strong>

                    <span>
                        Semua siswa sudah terdaftar di suatu kelas
                        untuk tahun ajaran ini.
                    </span>

                </div>

            @else

                <form method="POST"
                      action="{{ route('admin.student-placements.store', $classroom) }}">

                    @csrf

                    <input type="hidden"
                           name="academic_year_id"
                           value="{{ $selectedYearId }}">

                    <div class="student-form-group">

                        <label for="student_id">Pilih Siswa</label>

                        <select name="student_id"
                                id="student_id"
                                class="student-form-select"
                                required>

                            <option value="">-- Pilih Siswa --</option>

                            @foreach ($availableStudents as $student)

                                <option value="{{ $student->id }}">
                                    {{ $student->name }}
                                </option>

                            @endforeach

                        </select>

                    </div>

                    <button type="submit" class="add-student-button">

                        <svg width="16" height="16" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor"
                             stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round">
                            <path d="M12 5v14"></path>
                            <path d="M5 12h14"></path>
                        </svg>

                        Tambah Siswa

                    </button>

                </form>

            @endif

        </div>

    </div>

</div>


</div>

@endsection
