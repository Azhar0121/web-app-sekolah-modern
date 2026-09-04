@extends('layouts.admin')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')

<link rel="stylesheet" href="{{ asset('css/schedules/create.css') }}">

<div class="schedule-create-page">

    {{-- HEADER --}}
    <div class="schedule-create-header">

        <div class="schedule-create-header-content">

            <div class="schedule-create-title-area">

                <div class="schedule-create-title-icon">
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

                    <span class="schedule-create-label">
                        AKADEMIK
                    </span>

                    <h1>
                        Tambah Jadwal Pelajaran
                    </h1>

                    <p>
                        Tambahkan jadwal pelajaran untuk kelas yang dipilih.
                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- FORM CARD --}}
    <div class="schedule-create-card">

        <div class="schedule-create-card-header">

            <div class="schedule-create-card-icon">

                <svg viewBox="0 0 24 24"
                     fill="none"
                     stroke="currentColor"
                     stroke-width="2">

                    <line x1="12" y1="5" x2="12" y2="19"/>
                    <line x1="5" y1="12" x2="19" y2="12"/>

                </svg>

            </div>

            <div>

                <h2>
                    Form Jadwal Pelajaran
                </h2>

                <p>
                    Lengkapi informasi jadwal yang akan ditambahkan.
                </p>

            </div>

        </div>


        <div class="schedule-create-card-body">

            <form method="POST"
                  action="{{ route('admin.schedules.store') }}">

                @csrf

                @php($schedule = null)

                @php($reloadBaseUrl = route('admin.schedules.create'))

                @include('admin.schedules.form')


                {{-- ACTION --}}
                <div class="schedule-create-actions">

                    <a href="{{ route('admin.schedules.index', ['academic_year_id' => $selectedYearId, 'classroom_id' => $selectedClassroomId]) }}"
                       class="schedule-create-cancel">

                        Batal

                    </a>

                    <button type="submit"
                            class="schedule-create-save">

                        <svg viewBox="0 0 24 24"
                             fill="none"
                             stroke="currentColor"
                             stroke-width="2">

                            <path d="M19 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h11l5 5v11a2 2 0 0 1-2 2Z"/>
                            <polyline points="17 21 17 13 7 13 7 21"/>
                            <polyline points="7 3 7 8 15 8"/>

                        </svg>

                        Simpan

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection