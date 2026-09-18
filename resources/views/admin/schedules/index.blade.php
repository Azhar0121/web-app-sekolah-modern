@extends('layouts.admin')

@section('title', 'Jadwal Pelajaran')

@section('content')

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<link rel="stylesheet" href="{{ asset('css/schedules/index.css') }}">

<div class="schedule-page">

    {{-- HEADER --}}
    <div class="schedule-header">

        <div class="schedule-header-content">

            <div class="schedule-title-area">

                <div class="schedule-title-content">

                    <span class="schedule-label">
                        AKADEMIK
                    </span>

                    <h1>
                        Jadwal Pelajaran
                    </h1>

                    <p>
                        Kelola jadwal pelajaran berdasarkan tahun ajaran dan kelas.
                    </p>

                </div>

            </div>


            {{-- HERO ICON --}}
            <div class="schedule-hero-icon">

                <svg width="36" height="36" viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.7"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <rect x="3" y="4" width="18" height="18" rx="2"></rect>

                    <line x1="16" y1="2" x2="16" y2="6"></line>
                    <line x1="8" y1="2" x2="8" y2="6"></line>

                    <line x1="3" y1="10" x2="21" y2="10"></line>

                    <path d="M8 14h2"></path>
                    <path d="M14 14h2"></path>
                    <path d="M8 18h2"></path>
                    <path d="M14 18h2"></path>

                </svg>

            </div>

        </div>

    </div>


    {{-- FILTER --}}
    <div class="schedule-filter-card">

        <div class="schedule-filter-header">

            <div class="schedule-filter-icon">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <path d="M22 3H2l8 9.46V19l4 2v-8.54L22 3Z"></path>

                </svg>

            </div>

            <div>

                <h2>
                    Filter Jadwal
                </h2>

                <p>
                    Pilih tahun ajaran dan kelas yang ingin ditampilkan.
                </p>

            </div>

        </div>


        <div class="schedule-filter-body">

            <form method="GET"
                  action="{{ route('admin.schedules.index') }}"
                  class="schedule-filter-form">

                <div class="schedule-filter-field">

                    <label for="academic_year_id">
                        Tahun Ajaran
                    </label>

                    <select id="academic_year_id"
                            name="academic_year_id"
                            class="schedule-select"
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

                </div>


                <div class="schedule-filter-field">

                    <label for="classroom_id">
                        Kelas
                    </label>

                    <select id="classroom_id"
                            name="classroom_id"
                            class="schedule-select"
                            onchange="this.form.submit()">

                        @foreach ($classrooms as $classroom)

                            <option value="{{ $classroom->id }}"
                                @selected($selectedClassroomId === $classroom->id)>

                                {{ $classroom->name }}

                            </option>

                        @endforeach

                    </select>

                </div>

            </form>

        </div>

    </div>


    {{-- TABLE --}}
    <div class="schedule-table-card">

        <div class="schedule-card-top">

            <div class="schedule-card-heading">

                <div class="schedule-heading-icon">

                    <svg viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                        stroke-linecap="round"
                        stroke-linejoin="round">

                        <rect x="3" y="4" width="18" height="18" rx="2"></rect>

                        <line x1="16" y1="2" x2="16" y2="6"></line>
                        <line x1="8" y1="2" x2="8" y2="6"></line>

                        <line x1="3" y1="10" x2="21" y2="10"></line>

                    </svg>

                </div>

                <div>

                    <h2>
                        Daftar Jadwal
                    </h2>

                    <span>
                        Jadwal pelajaran kelas yang dipilih
                    </span>

                </div>

            </div>


            {{-- TAMBAH JADWAL --}}
            <a href="{{ route('admin.schedules.create', [
                'academic_year_id' => $selectedYearId,
                'classroom_id' => $selectedClassroomId
            ]) }}"
               class="schedule-add-button">

                <svg viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round">

                    <line x1="12" y1="5" x2="12" y2="19"></line>
                    <line x1="5" y1="12" x2="19" y2="12"></line>

                </svg>

                Tambah Jadwal

            </a>

        </div>


        <div class="table-responsive">

            <table class="schedule-table">

                <thead>

                    <tr>

                        <th class="schedule-day-column">
                            Hari
                        </th>

                        <th class="schedule-time-column">
                            Jam
                        </th>

                        <th>
                            Mata Pelajaran
                        </th>

                        <th>
                            Guru
                        </th>

                        <th>
                            Ruangan
                        </th>

                        <th class="schedule-action-column">
                            Aksi
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse ($schedules as $schedule)

                        <tr>

                            <td>

                                <div class="schedule-day">

                                    <span class="schedule-day-dot"></span>

                                    {{ $schedule->day_of_week }}

                                </div>

                            </td>


                            <td>

                                <div class="schedule-time">

                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <circle cx="12" cy="12" r="9"></circle>

                                        <polyline points="12 7 12 12 15 14"></polyline>

                                    </svg>

                                    {{ $schedule->start_time->format('H:i') }}
                                    -
                                    {{ $schedule->end_time->format('H:i') }}

                                </div>

                            </td>


                            <td>

                                <div class="schedule-subject">

                                    <span class="schedule-code">
                                        {{ $schedule->teachingAssignment->subject->code }}
                                    </span>

                                    <span class="schedule-subject-name">
                                        {{ $schedule->teachingAssignment->subject->name }}
                                    </span>

                                </div>

                            </td>


                            <td>

                                <div class="schedule-teacher">

                                    <div class="schedule-teacher-avatar">
                                        {{ strtoupper(substr($schedule->teachingAssignment->teacher->name, 0, 1)) }}
                                    </div>

                                    <span>
                                        {{ $schedule->teachingAssignment->teacher->name }}
                                    </span>

                                </div>

                            </td>


                            <td>

                                <div class="schedule-room">

                                    <svg viewBox="0 0 24 24"
                                         fill="none"
                                         stroke="currentColor"
                                         stroke-width="2">

                                        <path d="M3 21h18"></path>
                                        <path d="M5 21V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v16"></path>
                                        <path d="M9 21v-4h6v4"></path>
                                        <path d="M9 7h1"></path>
                                        <path d="M14 7h1"></path>
                                        <path d="M9 11h1"></path>
                                        <path d="M14 11h1"></path>

                                    </svg>

                                    {{ $schedule->room ?: '-' }}

                                </div>

                            </td>


                            <td>

                                <div class="schedule-actions">

                                    <a href="{{ route('admin.schedules.edit', $schedule) }}"
                                       class="schedule-edit-button">

                                        <svg viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="2">

                                            <path d="M12 20h9"></path>
                                            <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"></path>

                                        </svg>

                                        Edit

                                    </a>


                                    <form method="POST"
                                          action="{{ route('admin.schedules.destroy', $schedule) }}"
                                          class="schedule-delete-form"
                                          onsubmit="return confirm('Hapus jadwal ini?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="schedule-delete-button">

                                            <svg viewBox="0 0 24 24"
                                                 fill="none"
                                                 stroke="currentColor"
                                                 stroke-width="2">

                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6l-1 14H6L5 6"></path>
                                                <path d="M10 11v5"></path>
                                                <path d="M14 11v5"></path>
                                                <path d="M9 6V4h6v2"></path>

                                            </svg>

                                            Hapus

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td colspan="6">

                                <div class="schedule-empty">

                                    <div class="schedule-empty-icon">

                                        <svg viewBox="0 0 24 24"
                                             fill="none"
                                             stroke="currentColor"
                                             stroke-width="1.7"
                                             stroke-linecap="round"
                                             stroke-linejoin="round">

                                            <rect x="3" y="4" width="18" height="18" rx="2"></rect>

                                            <line x1="16" y1="2" x2="16" y2="6"></line>
                                            <line x1="8" y1="2" x2="8" y2="6"></line>

                                            <line x1="3" y1="10" x2="21" y2="10"></line>

                                            <path d="M8 14h2"></path>
                                            <path d="M14 14h2"></path>

                                        </svg>

                                    </div>

                                    <h3>
                                        Belum Ada Jadwal
                                    </h3>

                                    <p>
                                        Belum ada jadwal untuk kelas &amp; tahun ajaran ini.
                                    </p>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>


    {{-- INFORMATION --}}
    <div class="schedule-info">

        <div class="schedule-info-icon">

            <svg viewBox="0 0 24 24"
                 fill="none"
                 stroke="currentColor"
                 stroke-width="2"
                 stroke-linecap="round"
                 stroke-linejoin="round">

                <circle cx="12" cy="12" r="9"></circle>

                <line x1="12" y1="11" x2="12" y2="16"></line>

                <line x1="12" y1="8" x2="12.01" y2="8"></line>

            </svg>

        </div>

        <div>

            <p>

                Jadwal hanya bisa dibuat dari mata pelajaran yang sudah punya

                <a href="{{ route('admin.teaching-assignments.index', [
                    'academic_year_id' => $selectedYearId
                ]) }}">
                    penugasan mengajar
                </a>

                untuk kelas ini. Sistem otomatis menolak jadwal yang bentrok
                jam dengan guru atau kelas yang sama.

            </p>

        </div>

    </div>

</div>

@endsection