@extends('layouts.admin')

@section('title', 'Daftar Siswa')

@section('content')
<link rel="stylesheet" href="{{ asset('css/guru/student-profile/index.css') }}">

<div class="guru-students-page">

    {{-- Header --}}
    <div class="students-page-header">
        <div class="students-header-content">
            <div class="students-header-icon">
                <i class="bi bi-people-fill"></i>
            </div>

            <div>
                <div class="students-header-label">DATA SISWA</div>
                <h1>Daftar Siswa</h1>
                <p>Pilih kelas untuk melihat daftar siswa yang Anda ampu.</p>
            </div>
        </div>

        @if ($activeYear)
            <div class="students-year">
                <i class="bi bi-calendar3"></i>
                {{ $activeYear->name }}
            </div>
        @endif
    </div>


    {{-- Empty Classroom --}}
    @if ($classrooms->isEmpty())

        <div class="students-empty-card">
            <div class="students-empty-icon">
                <i class="bi bi-people"></i>
            </div>

            <h3>Tidak ada data siswa</h3>

            <p>
                Anda belum ditugaskan mengajar kelas manapun pada tahun ajaran ini.
            </p>
        </div>

    @else

        {{-- Pilih Kelas --}}
        <div class="students-class-card">

            <div class="students-class-header">
                <div class="students-section-icon">
                    <i class="bi bi-grid-3x3-gap-fill"></i>
                </div>

                <div>
                    <h2>Pilih Kelas</h2>
                    <p>Pilih kelas untuk menampilkan daftar siswa.</p>
                </div>
            </div>

            <div class="students-class-list">
                @foreach ($classrooms as $classroom)
                    <a
                        href="{{ route('guru.student-profile.index', ['classroom_id' => $classroom->id]) }}"
                        class="students-class-item {{ $selectedClassroom?->id === $classroom->id ? 'active' : '' }}"
                    >
                        <span class="students-class-item-icon">
                            <i class="bi bi-door-open-fill"></i>
                        </span>

                        <span>{{ $classroom->name }}</span>

                        @if ($selectedClassroom?->id === $classroom->id)
                            <i class="bi bi-check2 students-class-check"></i>
                        @endif
                    </a>
                @endforeach
            </div>

        </div>


        {{-- Tabel Siswa --}}
        @if ($selectedClassroom)

            <div class="students-table-card">

                <div class="students-table-header">

                    <div class="students-table-title">
                        <div class="students-section-icon blue">
                            <i class="bi bi-person-lines-fill"></i>
                        </div>

                        <div>
                            <div class="students-table-eyebrow">
                                DAFTAR SISWA
                            </div>

                            <h2>
                                Kelas {{ $selectedClassroom->name }}
                            </h2>

                            <p>
                                Daftar siswa yang terdaftar pada kelas ini.
                            </p>
                        </div>
                    </div>

                    <div class="students-total">
                        <strong>{{ $students->count() }}</strong>
                        <span>Siswa</span>
                    </div>

                </div>


                @if ($students->isEmpty())

                    <div class="students-no-data">
                        <div class="students-no-data-icon">
                            <i class="bi bi-clipboard-x"></i>
                        </div>

                        <h3>Belum ada siswa</h3>

                        <p>
                            Belum ada siswa yang terdaftar di kelas ini.
                        </p>
                    </div>

                @else

                    <div class="students-table-wrapper">
                        <table class="students-table">

                            <thead>
                                <tr>
                                    <th class="students-number">#</th>
                                    <th>Nama Siswa</th>
                                    <th>NISN</th>
                                    <th>Jenis Kelamin</th>
                                    <th class="students-action-heading">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($students as $i => $student)

                                    <tr>

                                        <td class="students-number">
                                            {{ $i + 1 }}
                                        </td>

                                        <td>
                                            <div class="student-identity">

                                                <div class="student-avatar">
                                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                                </div>

                                                <div class="student-information">
                                                    <div class="student-name">
                                                        {{ $student->name }}
                                                    </div>

                                                    <div class="student-email">
                                                        {{ $student->email }}
                                                    </div>
                                                </div>

                                            </div>
                                        </td>

                                        <td>
                                            <span class="student-nisn">
                                                {{ $student->studentProfile?->nisn ?? '-' }}
                                            </span>
                                        </td>

                                        <td>
                                            @if ($student->studentProfile?->gender)

                                                <span class="student-gender {{ $student->studentProfile->gender === 'L' ? 'male' : 'female' }}">
                                                    <i class="bi {{ $student->studentProfile->gender === 'L' ? 'bi-gender-male' : 'bi-gender-female' }}"></i>
                                                    {{ $student->studentProfile->genderLabel() }}
                                                </span>

                                            @else

                                                <span class="student-empty-value">-</span>

                                            @endif
                                        </td>

                                        <td class="students-action">

                                            <a
                                                href="{{ route('guru.student-profile.show', $student) }}"
                                                class="students-view-button"
                                            >
                                                <span>Lihat Biodata</span>
                                                <i class="bi bi-arrow-right"></i>
                                            </a>

                                        </td>

                                    </tr>

                                @endforeach
                            </tbody>

                        </table>
                    </div>

                @endif

            </div>

        @endif

    @endif

</div>
@endsection