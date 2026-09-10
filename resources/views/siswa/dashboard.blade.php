@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')

<link rel="stylesheet" href="{{ asset('css/siswa/dashboard.css') }}">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

@php
/*
|--------------------------------------------------------------------------
| DATA DASHBOARD
|--------------------------------------------------------------------------
| Karena dashboard tidak menggunakan controller khusus,
| data diambil langsung dari model.
*/


$student = auth()->user();
$classroom = $student->currentClassroom();

$today = now()->locale('id');
$dayName = ucfirst($today->translatedFormat('l'));

/*
| Jadwal hari ini
*/
$todaySchedules = collect();

try {
    if ($classroom && class_exists(\App\Models\Schedule::class)) {
        $todaySchedules = \App\Models\Schedule::query()
            ->where('classroom_id', $classroom->id)
            ->where(function ($query) use ($dayName) {
                $query->whereRaw('LOWER(day) = ?', [strtolower($dayName)])
                      ->orWhereRaw('LOWER(day) = ?', [strtolower($dayName)]);
            })
            ->orderBy('start_time')
            ->get();
    }
} catch (\Throwable $e) {
    $todaySchedules = collect();
}

/*
| Tugas
*/
$studentTasks = collect();

try {
    if (class_exists(\App\Models\Task::class)) {
        $studentTasks = \App\Models\Task::query()
            ->latest()
            ->take(4)
            ->get();
    }
} catch (\Throwable $e) {
    $studentTasks = collect();
}

/*
| Presensi
*/
$attendanceStats = [
    'hadir' => 0,
    'izin' => 0,
    'sakit' => 0,
    'alpha' => 0,
];

try {
    if (class_exists(\App\Models\Attendance::class)) {

        $attendances = \App\Models\Attendance::query()
            ->where('user_id', $student->id)
            ->get();

        foreach ($attendances as $attendance) {

            $status = strtolower($attendance->status ?? '');

            if (in_array($status, ['hadir', 'present'])) {
                $attendanceStats['hadir']++;
            } elseif ($status === 'izin') {
                $attendanceStats['izin']++;
            } elseif ($status === 'sakit') {
                $attendanceStats['sakit']++;
            } elseif (in_array($status, ['alpha', 'alpa'])) {
                $attendanceStats['alpha']++;
            }
        }
    }
} catch (\Throwable $e) {
    //
}


@endphp

<div class="student-dashboard">


{{-- =====================================================
    HERO
====================================================== --}}
<section class="student-hero">

    <div class="hero-content">

        <div class="hero-label">
            <span class="label-dot"></span>
            PORTAL SISWA
        </div>

        <h1>
            Selamat datang,
            <span>{{ $student->name }}</span>
        </h1>

        <p>
            Pantau jadwal, tugas, presensi, materi,
            dan aktivitas akademik Anda dalam satu dashboard.
        </p>

        <div class="hero-info">

            @if ($classroom)

                <div class="hero-info-item">
                    <div class="hero-info-icon">
                        <i class="bi bi-mortarboard-fill"></i>
                    </div>

                    <div>
                        <small>Kelas Saat Ini</small>
                        <strong>{{ $classroom->name }}</strong>
                    </div>
                </div>

            @else

                <div class="hero-info-item">
                    <div class="hero-info-icon warning">
                        <i class="bi bi-exclamation-circle-fill"></i>
                    </div>

                    <div>
                        <small>Status Kelas</small>
                        <strong>Belum Terdaftar</strong>
                    </div>
                </div>

            @endif

            <div class="hero-info-divider"></div>

            <div class="hero-info-item">
                <div class="hero-info-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div>
                    <small>Hari Ini</small>
                    <strong>{{ $dayName }}, {{ $today->format('d M Y') }}</strong>
                </div>
            </div>

        </div>

    </div>

    <div class="hero-decoration decoration-one"></div>
    <div class="hero-decoration decoration-two"></div>
    <div class="hero-grid"></div>

    <div class="hero-symbol">
        <i class="bi bi-mortarboard-fill"></i>
    </div>

</section>


{{-- =====================================================
    DASHBOARD MAIN
====================================================== --}}
<section class="student-main">

    {{-- =================================================
        LEFT : JADWAL + TUGAS
    ================================================== --}}
    <div class="student-main-left">

        {{-- JADWAL --}}
        <div class="schedule-panel">

            <div class="panel-heading">

                <div>
                    <span class="section-kicker">AKADEMIK</span>

                    <h2>
                        Jadwal Hari Ini
                    </h2>

                    <p>
                        {{ $dayName }}, {{ $today->format('d F Y') }}
                    </p>
                </div>

                <a href="{{ route('siswa.schedule.index') }}"
                   class="panel-link">
                    Lihat Semua
                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>


            <div class="schedule-list">

                @forelse ($todaySchedules as $schedule)

                    <div class="schedule-item">

                        <div class="schedule-time">

                            <strong>
                                {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                            </strong>

                            <span>
                                {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}
                            </span>

                        </div>


                        <div class="schedule-line">
                            <span></span>
                        </div>


                        <div class="schedule-info">

                            <strong>
                                {{ $schedule->subject->name ?? $schedule->subject_name ?? 'Mata Pelajaran' }}
                            </strong>

                            <div class="schedule-meta">

                                <span>
                                    <i class="bi bi-person"></i>
                                    {{ $schedule->teacher->name ?? $schedule->teacher_name ?? 'Guru' }}
                                </span>

                                @if (!empty($schedule->room))
                                    <span>
                                        <i class="bi bi-geo-alt"></i>
                                        {{ $schedule->room }}
                                    </span>
                                @endif

                            </div>

                        </div>


                        <div class="schedule-arrow">
                            <i class="bi bi-chevron-right"></i>
                        </div>

                    </div>

                @empty

                    <div class="empty-schedule">

                        <div class="empty-icon">
                            <i class="bi bi-calendar-check"></i>
                        </div>

                        <div>
                            <strong>Tidak ada jadwal hari ini</strong>
                            <span>
                                Nikmati waktu kosongmu atau cek jadwal hari berikutnya.
                            </span>
                        </div>

                    </div>

                @endforelse

            </div>

        </div>


        {{-- TUGAS --}}
        <div class="tasks-panel">

            <div class="panel-heading compact">

                <div>
                    <span class="section-kicker">PEMBELAJARAN</span>
                    <h2>Tugas Terbaru</h2>
                </div>

                <a href="{{ route('siswa.tasks.index') }}"
                   class="panel-link">
                    Semua Tugas
                    <i class="bi bi-arrow-up-right"></i>
                </a>

            </div>


            <div class="task-list">

                @forelse ($studentTasks as $task)

                    <a href="{{ route('siswa.tasks.index') }}"
                       class="task-item">

                        <div class="task-icon">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>

                        <div class="task-content">

                            <strong>
                                {{ $task->title ?? 'Tugas Pembelajaran' }}
                            </strong>

                            <span>
                                {{ $task->subject->name ?? $task->subject_name ?? 'Mata Pelajaran' }}
                            </span>

                        </div>

                        <div class="task-deadline">

                            <small>DEADLINE</small>

                            <strong>
                                @if (!empty($task->deadline))
                                    {{ \Carbon\Carbon::parse($task->deadline)->format('d M') }}
                                @elseif (!empty($task->due_date))
                                    {{ \Carbon\Carbon::parse($task->due_date)->format('d M') }}
                                @else
                                    -
                                @endif
                            </strong>

                        </div>

                        <i class="bi bi-chevron-right task-arrow"></i>

                    </a>

                @empty

                    <div class="empty-task">

                        <i class="bi bi-check2-circle"></i>

                        <div>
                            <strong>Belum ada tugas</strong>
                            <span>
                                Semua tugasmu sudah aman untuk sekarang.
                            </span>
                        </div>

                    </div>

                @endforelse

            </div>

        </div>

    </div>


    {{-- =================================================
        RIGHT SIDEBAR
    ================================================== --}}
    <aside class="student-main-right">


        {{-- QR CARD --}}
        <a href="{{ route('siswa.qr-code.show') }}"
           class="qr-panel">

            <div class="qr-top">

                <div>
                    <span>KARTU DIGITAL</span>
                    <h3>Kartu Pelajar</h3>
                </div>

                <div class="qr-mini-icon">
                    <i class="bi bi-qr-code"></i>
                </div>

            </div>

            <div class="qr-visual">

                <div class="qr-pattern">
                    <i class="bi bi-qr-code"></i>
                </div>

                <div class="qr-info">

                    <strong>{{ $student->name }}</strong>

                    <span>
                        {{ $classroom->name ?? 'Siswa' }}
                    </span>

                    <small>
                        Tampilkan QR untuk presensi
                    </small>

                </div>

            </div>

            <div class="qr-action">
                <span>Buka Kartu Digital</span>
                <i class="bi bi-arrow-up-right"></i>
            </div>

        </a>


        {{-- PRESENSI --}}
        <a href="{{ route('siswa.attendance.index') }}"
           class="attendance-panel">

            <div class="attendance-heading">

                <div>
                    <span>KEHADIRAN</span>
                    <h3>Riwayat Presensi</h3>
                </div>

                <i class="bi bi-clipboard2-check"></i>

            </div>


            <div class="attendance-highlight">

                <strong>
                    {{ $attendanceStats['hadir'] }}
                </strong>

                <span>
                    Kehadiran
                </span>

            </div>


            <div class="attendance-mini">

                <div>
                    <strong>{{ $attendanceStats['izin'] }}</strong>
                    <span>Izin</span>
                </div>

                <div>
                    <strong>{{ $attendanceStats['sakit'] }}</strong>
                    <span>Sakit</span>
                </div>

                <div>
                    <strong>{{ $attendanceStats['alpha'] }}</strong>
                    <span>Alpha</span>
                </div>

            </div>


            <div class="attendance-footer">
                Lihat riwayat lengkap
                <i class="bi bi-arrow-right"></i>
            </div>

        </a>


        {{-- MATERI --}}
        <a href="{{ route('siswa.materials.index') }}"
           class="material-panel">

            <div class="material-icon">
                <i class="bi bi-journal-bookmark-fill"></i>
            </div>

            <div>
                <span>MATERI</span>
                <strong>Materi Pembelajaran</strong>
                <small>Akses materi dari guru</small>
            </div>

            <i class="bi bi-arrow-up-right"></i>

        </a>

    </aside>

</section>


{{-- =====================================================
    BOTTOM : NILAI + SYSTEM
====================================================== --}}
<section class="student-bottom">

    {{-- NILAI --}}
    <a href="{{ route('siswa.grades.index') }}"
       class="grade-panel">

        <div class="grade-main">

            <span class="section-kicker">PERFORMA AKADEMIK</span>

            <h2>Nilai Rapor</h2>

            <p>
                Pantau perkembangan hasil belajar dan
                rekap nilai akademik Anda.
            </p>

            <div class="grade-action">
                Lihat Nilai Lengkap
                <i class="bi bi-arrow-right"></i>
            </div>

        </div>

        <div class="grade-visual">

            <div class="grade-circle">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <span>ACADEMIC<br>PERFORMANCE</span>

        </div>

    </a>


    {{-- STATUS --}}
    <div class="system-panel">

        <div class="system-icon">
            <i class="bi bi-shield-check"></i>
        </div>

        <div>
            <span>SYSTEM STATUS</span>
            <strong>Portal Siswa Aktif</strong>
            <small>Semua layanan dapat digunakan</small>
        </div>

        <span class="system-dot"></span>

    </div>

</section>


</div>

@endsection
