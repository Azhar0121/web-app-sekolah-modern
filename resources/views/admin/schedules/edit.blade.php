@extends('layouts.admin')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')

<link rel="stylesheet" href="{{ asset('css/schedules/edit.css') }}">

<div class="schedule-edit-page">

    {{-- HEADER --}}
    <div class="schedule-edit-header">

        <div class="schedule-edit-header-content">

            <div class="schedule-edit-title-area">

                <div class="schedule-edit-title-icon">
                    <svg viewBox="0 0 24 24"
                         fill="none"
                         stroke="currentColor"
                         stroke-width="2">

                        <rect x="3" y="4" width="18" height="18" rx="2"/>
                        <line x1="16" y1="2" x2="16" y2="6"/>
                        <line x1="8" y1="2" x2="8" y2="6"/>
                        <line x1="3" y1="10" x2="21" y2="10"/>

                    </svg>
                </div>

                <div>

                    <span class="schedule-edit-label">
                        AKADEMIK
                    </span>

                    <h1>
                        Edit Jadwal Pelajaran
                    </h1>

                    <p>
                        Perbarui informasi jadwal pelajaran yang sudah tersimpan.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- FORM CARD --}}
    <div class="schedule-edit-card">

        <div class="schedule-edit-card-header">

            <div class="schedule-edit-card-icon">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <path d="M12 20h9"/>
                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4Z"/>

                </svg>

            </div>

            <div>

                <h2>
                    Form Edit Jadwal
                </h2>

                <p>
                    Sesuaikan informasi jadwal sesuai kebutuhan.
                </p>

            </div>

        </div>


        <div class="schedule-edit-card-body">

            <form method="POST"
                  action="{{ route('admin.schedules.update', $schedule) }}">

                @csrf

                @method('PUT')

                @php($reloadBaseUrl = route('admin.schedules.edit', $schedule))

                @include('admin.schedules.form')


                {{-- ACTION --}}
                <div class="schedule-edit-actions">

                    <a href="{{ route('admin.schedules.index', ['academic_year_id' => $selectedYearId, 'classroom_id' => $selectedClassroomId]) }}"
                       class="schedule-edit-cancel">

                        Batal

                    </a>

                    <button type="submit"
                            class="schedule-edit-save">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>

                        </svg>

                        Simpan Perubahan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection