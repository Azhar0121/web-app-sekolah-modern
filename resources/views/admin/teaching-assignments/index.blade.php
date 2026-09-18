@extends('layouts.admin')

@section('title', 'Penugasan Mengajar')

@section('content')

<link rel="stylesheet" href="{{ asset('css/teaching/index.css') }}">

<div class="teaching-page">


    {{-- =====================================================
         HERO
         ===================================================== --}}

    <div class="teaching-header">

        <div class="teaching-header-content">

            {{-- HERO TITLE --}}
            <div class="teaching-title-area">

                <span class="teaching-label">
                    ADMINISTRASI AKADEMIK
                </span>

                <h1>
                    Penugasan Mengajar
                </h1>

                <p>
                    Atur guru pengampu berdasarkan kelas dan mata pelajaran
                    untuk setiap tahun ajaran.
                </p>

            </div>


            {{-- HERO ICON --}}
            <div class="teaching-hero-icon" aria-hidden="true">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.6"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M3 10.5 12 5l9 5.5-9 5.5-9-5.5Z"/>
                    <path d="M6.5 12.5V17c3 2.2 8 2.2 11 0v-4.5"/>
                    <path d="M21 10.5V16"/>

                </svg>

            </div>

        </div>

    </div>



    {{-- =====================================================
         FILTER TAHUN AJARAN
         ===================================================== --}}

    <div class="teaching-filter-card">

        {{-- FILTER HEADER --}}
        <div class="teaching-filter-header">

            <div class="teaching-filter-icon" aria-hidden="true">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <rect x="3" y="4" width="18" height="17" rx="2"/>
                    <path d="M16 2v4"/>
                    <path d="M8 2v4"/>
                    <path d="M3 10h18"/>

                </svg>

            </div>


            <div>

                <h2>
                    Tahun Ajaran
                </h2>

                <p>
                    Pilih tahun ajaran untuk menampilkan penugasan mengajar.
                </p>

            </div>

        </div>


        {{-- FILTER BODY --}}
        <div class="teaching-filter-body">

            <form method="GET"
                  action="{{ route('admin.teaching-assignments.index') }}">

                <div class="teaching-filter-field">

                    <label for="academic_year_id">
                        Tahun Ajaran
                    </label>

                    <select
                        id="academic_year_id"
                        name="academic_year_id"
                        class="teaching-select"
                        onchange="this.form.submit()">

                        @foreach ($academicYears as $year)

                            <option
                                value="{{ $year->id }}"
                                @selected($selectedYearId === $year->id)>

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



    {{-- =====================================================
         MAIN CARD
         ===================================================== --}}

    <div class="teaching-main-card">


        {{-- CARD TOP --}}
        <div class="teaching-card-top">

            <div class="teaching-card-title">

                <div class="teaching-title-icon" aria-hidden="true">

                    <svg viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.7"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                        <path d="M4 5.5V21"/>
                        <path d="M8 7h8"/>
                        <path d="M8 11h8"/>

                    </svg>

                </div>


                <div>

                    <h2>
                        Daftar Penugasan
                    </h2>

                    <p>
                        Guru, kelas, dan mata pelajaran yang telah ditugaskan.
                    </p>

                </div>

            </div>


            {{-- TAMBAH PENUGASAN --}}
            <a
                href="{{ route('admin.teaching-assignments.create', ['academic_year_id' => $selectedYearId]) }}"
                class="teaching-add-button">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M12 5v14"/>
                    <path d="M5 12h14"/>

                </svg>

                <span>
                    Tambah Penugasan
                </span>

            </a>

        </div>



        {{-- =================================================
             TABLE
             ================================================= --}}

        <div class="teaching-table-wrapper">

            <table class="teaching-table">

                <thead>

                    <tr>

                        <th>
                            Kelas
                        </th>

                        <th>
                            Mata Pelajaran
                        </th>

                        <th>
                            Guru Pengampu
                        </th>

                        <th class="text-end">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($assignments as $assignment)

                        <tr>


                            {{-- KELAS --}}
                            <td>

                                <div class="teaching-class">

                                    <div class="teaching-class-avatar">

                                        <svg viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                            stroke-linejoin="round">

                                            <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                                            <path d="M4 5.5V21"/>

                                        </svg>

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



                            {{-- MATA PELAJARAN --}}
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



                            {{-- GURU --}}
                            <td>

                                <div class="teaching-teacher">

                                    <div class="teaching-teacher-icon">

                                        <svg viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.7"
                                            stroke-linecap="round"
                                            stroke-linejoin="round">

                                            <circle cx="12" cy="8" r="3"/>
                                            <path d="M5 21a7 7 0 0 1 14 0"/>

                                        </svg>

                                    </div>


                                    <span class="teaching-teacher-name">
                                        {{ $assignment->teacher->name }}
                                    </span>

                                </div>

                            </td>



                            {{-- AKSI --}}
                            <td class="text-end">

                                <div class="teaching-actions">


                                    {{-- EDIT --}}
                                    <a
                                        href="{{ route('admin.teaching-assignments.edit', $assignment) }}"
                                        class="teaching-edit-button">

                                        <svg viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                            stroke-linecap="round"
                                            stroke-linejoin="round">

                                            <path d="M12 20h9"/>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"/>

                                        </svg>

                                        Edit

                                    </a>



                                    {{-- HAPUS --}}
                                    <form
                                        method="POST"
                                        action="{{ route('admin.teaching-assignments.destroy', $assignment) }}"
                                        class="teaching-delete-form"
                                        onsubmit="return confirm('Hapus penugasan ini?');">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="teaching-delete-button">

                                            <svg viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                                stroke-linecap="round"
                                                stroke-linejoin="round">

                                                <path d="M4 7h16"/>
                                                <path d="M10 11v6"/>
                                                <path d="M14 11v6"/>
                                                <path d="M6 7l1 14h10l1-14"/>
                                                <path d="M9 7V4h6v3"/>

                                            </svg>

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty


                        {{-- EMPTY STATE --}}
                        <tr>

                            <td colspan="4" class="teaching-empty">

                                <div class="teaching-empty-icon">

                                    <svg viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                        stroke-linecap="round"
                                        stroke-linejoin="round">

                                        <path d="M4 5.5A2.5 2.5 0 0 1 6.5 3H20v16H6.5A2.5 2.5 0 0 0 4 21V5.5Z"/>
                                        <path d="M4 5.5V21"/>

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
         INFO
         ===================================================== --}}

    <div class="teaching-info">

        <div class="teaching-info-icon">

            <svg viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="1.7"
                stroke-linecap="round"
                stroke-linejoin="round">

                <circle cx="12" cy="12" r="9"/>
                <path d="M12 10v6"/>
                <path d="M12 7h.01"/>

            </svg>

        </div>


        <p>
            Penugasan mengajar menentukan guru mana yang bisa mengunggah
            materi/tugas untuk kelas dan mata pelajaran tertentu di Portal Guru.
        </p>

    </div>


</div>

@endsection