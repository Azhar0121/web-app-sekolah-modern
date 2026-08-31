@extends('layouts.admin')

@section('title', 'Penempatan Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/student-placements/index.css') }}">

<div class="student-placement-page">

    {{-- HEADER --}}
    <div class="student-placement-header">
        <div class="student-placement-header-content">

            <div class="student-placement-title-area">
                <span class="student-placement-label">
                    PENEMPATAN SISWA
                </span>

                <h1>Penempatan Siswa ke Kelas</h1>

                <p>
                    Kelola penempatan siswa berdasarkan tahun ajaran dan kelas
                    yang tersedia di sistem.
                </p>
            </div>

        </div>
    </div>


    {{-- FILTER CARD --}}
    <div class="student-placement-filter-card">

        <div class="student-placement-filter-header">

            <div class="student-placement-filter-icon">
                <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2"
                     stroke-linecap="round" stroke-linejoin="round"
                     aria-hidden="true">
                    <path d="M3 4h18"></path>
                    <path d="M6 9h12"></path>
                    <path d="M9 14h6"></path>
                    <path d="M11 19h2"></path>
                </svg>
            </div>

            <div>
                <h2>Filter Tahun Ajaran</h2>
                <p>Pilih tahun ajaran untuk melihat daftar kelas.</p>
            </div>

        </div>

        <div class="student-placement-filter-body">

            <form method="GET"
                  action="{{ route('admin.student-placements.index') }}">

                <div class="student-placement-form-group">

                    <label for="academic_year_id">
                        Tahun Ajaran
                    </label>

                    <select
                        name="academic_year_id"
                        id="academic_year_id"
                        class="student-placement-select"
                        onchange="this.form.submit()"
                    >
                        @foreach ($academicYears as $year)
                            <option
                                value="{{ $year->id }}"
                                @selected($selectedYearId === $year->id)
                            >
                                {{ $year->name }}
                                @if ($year->is_active)
                                    (Aktif)
                                @endif
                            </option>
                        @endforeach
                    </select>

                </div>

            </form>

        </div>

    </div>


    {{-- TABLE CARD --}}
    <div class="student-placement-main-card">

        <div class="student-placement-card-top">

            <div class="student-placement-card-title">

                <div class="student-placement-title-icon">
                    <svg width="21" height="21" viewBox="0 0 24 24" fill="none"
                         stroke="currentColor" stroke-width="2"
                         stroke-linecap="round" stroke-linejoin="round"
                         aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                        <circle cx="9" cy="7" r="4"></circle>
                        <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                    </svg>
                </div>

                <div>
                    <h2>Daftar Kelas</h2>
                    <p>
                        Pilih kelas untuk mengatur siswa yang ditempatkan.
                    </p>
                </div>

            </div>

        </div>


        <div class="student-placement-table-wrapper">

            <table class="student-placement-table">

                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Tingkat</th>
                        <th class="text-center">Jumlah Siswa</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>

                <tbody>

                    @forelse ($classrooms as $classroom)

                        <tr>

                            <td>
                                <div class="classroom-name">

                                    <div class="classroom-icon">
                                        <svg width="19" height="19"
                                             viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2"
                                             stroke-linecap="round"
                                             stroke-linejoin="round"
                                             aria-hidden="true">
                                            <path d="M3 21h18"></path>
                                            <path d="M5 21V5l7-3 7 3v16"></path>
                                            <path d="M9 21v-4h6v4"></path>
                                            <path d="M9 8h.01"></path>
                                            <path d="M15 8h.01"></path>
                                            <path d="M9 11h.01"></path>
                                            <path d="M15 11h.01"></path>
                                        </svg>
                                    </div>

                                    <div>
                                        <strong>
                                            {{ $classroom->name }}
                                        </strong>

                                        <span>
                                            Kelas
                                        </span>
                                    </div>

                                </div>
                            </td>


                            <td>
                                <span class="grade-badge">
                                    {{ $classroom->grade_level }}
                                </span>
                            </td>


                            <td class="text-center">

                                <div class="student-count">

                                    <span class="student-count-number">
                                        {{ $classroom->students_count }}
                                    </span>

                                    @if ($classroom->capacity)
                                        <span class="student-count-capacity">
                                            / {{ $classroom->capacity }}
                                        </span>
                                    @endif

                                </div>

                            </td>


                            <td class="text-center">

                                <a
                                    href="{{ route('admin.student-placements.manage', [
                                        'classroom' => $classroom,
                                        'academic_year_id' => $selectedYearId
                                    ]) }}"
                                    class="manage-student-button"
                                >

                                    <svg width="17" height="17"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                        <circle cx="9" cy="7" r="4"></circle>
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                    </svg>

                                    <span>Kelola Siswa</span>

                                    <svg class="button-arrow"
                                         width="15" height="15"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">
                                        <path d="M5 12h14"></path>
                                        <path d="m12 5 7 7-7 7"></path>
                                    </svg>

                                </a>

                            </td>

                        </tr>

                    @empty

                        <tr>
                            <td colspan="4" class="empty-state">

                                <div class="empty-state-icon">
                                    <svg width="23" height="23"
                                         viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2"
                                         stroke-linecap="round"
                                         stroke-linejoin="round"
                                         aria-hidden="true">
                                        <path d="M3 21h18"></path>
                                        <path d="M5 21V5l7-3 7 3v16"></path>
                                    </svg>
                                </div>

                                <strong>Belum ada kelas</strong>

                                <span>
                                    Belum tersedia kelas untuk tahun ajaran yang dipilih.
                                </span>

                            </td>
                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection