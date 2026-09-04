@extends('layouts.admin')

@section('title', 'Penugasan Mengajar')

@section('content')

    <link rel="stylesheet" href="{{ asset('css/teaching/index.css') }}">

    <div class="teaching-page">

        {{-- =====================================================
         HEADER
         ===================================================== --}}
        <div class="teaching-header">

            <div class="teaching-header-content">

                <div class="teaching-title-area">

                    <span class="teaching-label">
                        PENUGASAN MENGAJAR
                    </span>

                    <h1>Penugasan Mengajar</h1>

                    <p>
                        Kelola guru pengampu berdasarkan kelas dan mata pelajaran
                        untuk mendukung kegiatan belajar mengajar.
                    </p>

                </div>

                <a href="{{ route('admin.teaching-assignments.create') }}" class="teaching-add-button">
                    <span>+</span>
                    Tambah Penugasan
                </a>

            </div>

        </div>


        {{-- =====================================================
         FILTER TAHUN AJARAN
         ===================================================== --}}
        <div class="teaching-filter-card">

            <div class="teaching-filter-header">

                <div class="teaching-filter-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>
                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>
                        <line x1="3" y1="10" x2="21" y2="10"></line>
                    </svg>
                </div>

                <div>
                    <h2>Tahun Ajaran</h2>

                    <p>
                        Pilih tahun ajaran untuk melihat penugasan mengajar.
                    </p>
                </div>

            </div>


            <form method="GET" action="{{ route('admin.teaching-assignments.index') }}" class="teaching-filter-form">

                <div class="teaching-filter-field">

                    <label for="academic_year_id">
                        Tahun Ajaran
                    </label>

                    <select id="academic_year_id" name="academic_year_id" class="teaching-select"
                        onchange="this.form.submit()">

                        @foreach ($academicYears as $year)
                            <option value="{{ $year->id }}" @selected($selectedYearId === $year->id)>
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


        {{-- =====================================================
         TABLE CARD
         ===================================================== --}}
        <div class="teaching-table-card">

            {{-- CARD TOP --}}
            <div class="teaching-card-top">

                <div class="teaching-card-title">

                    <div class="teaching-title-icon">
                        <svg width="21" height="21" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                            <circle cx="9" cy="7" r="4"></circle>
                            <path d="M22 21v-2a4 4 0 0 0-3-3.87"></path>
                            <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                        </svg>
                    </div>

                    <div>
                        <h2>Daftar Penugasan</h2>

                        <p>
                            Guru pengampu kelas dan mata pelajaran
                        </p>
                    </div>

                </div>

            </div>


            {{-- TABLE --}}
            <div class="teaching-table-wrapper">

                <table class="teaching-table">

                    <thead>
                        <tr>
                            <th>Kelas</th>
                            <th>Mata Pelajaran</th>
                            <th>Guru Pengampu</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($assignments as $assignment)
                            <tr>

                                <td>
                                    <div class="teaching-class">

                                        <div class="teaching-class-avatar">
                                            {{ strtoupper(substr($assignment->classroom->name, 0, 1)) }}
                                        </div>

                                        <div>
                                            <strong>
                                                {{ $assignment->classroom->name }}
                                            </strong>

                                            <span>
                                                Kelas
                                            </span>
                                        </div>

                                    </div>
                                </td>


                                <td>

                                    <div class="teaching-subject">

                                        <span class="teaching-code">
                                            {{ $assignment->subject->code }}
                                        </span>

                                        <span class="teaching-subject-name">
                                            {{ $assignment->subject->name }}
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <div class="teaching-teacher">

                                        <div class="teaching-teacher-icon">
                                            <svg width="17" height="17" viewBox="0 0 24 24" fill="none"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                                                <circle cx="12" cy="7" r="4"></circle>
                                            </svg>
                                        </div>

                                        <span>
                                            {{ $assignment->teacher->name }}
                                        </span>

                                    </div>

                                </td>


                                <td>

                                    <div class="teaching-actions">

                                        <a href="{{ route('admin.teaching-assignments.edit', $assignment) }}"
                                            class="teaching-edit-button">
                                            Edit
                                        </a>


                                        <form method="POST"
                                            action="{{ route('admin.teaching-assignments.destroy', $assignment) }}"
                                            class="teaching-delete-form" onsubmit="return confirm('Hapus penugasan ini?');">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="teaching-delete-button">
                                                Hapus
                                            </button>

                                        </form>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="4" class="teaching-empty">

                                    <div class="teaching-empty-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round">
                                            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                        </svg>
                                    </div>

                                    <strong>
                                        Belum ada penugasan mengajar
                                    </strong>

                                    <span>
                                        Belum ada penugasan mengajar untuk tahun ajaran ini.
                                    </span>

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =====================================================
         INFORMATION
         ===================================================== --}}
        <div class="teaching-info">

            <div class="teaching-info-icon">
                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="12" cy="12" r="10"></circle>
                    <line x1="12" y1="16" x2="12" y2="12"></line>
                    <line x1="12" y1="8" x2="12.01" y2="8"></line>
                </svg>
            </div>

            <p>
                Penugasan mengajar menentukan guru mana yang bisa mengunggah
                materi/tugas untuk kelas &amp; mata pelajaran tertentu di Portal Guru.
            </p>

        </div>

    </div>

@endsection
