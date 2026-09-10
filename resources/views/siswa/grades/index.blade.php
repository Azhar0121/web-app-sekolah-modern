@extends('layouts.app')

@section('title', 'Nilai Rapor')

@section('content')

<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<link rel="stylesheet"
      href="{{ asset('css/siswa/grades/index.css') }}">

<div class="student-grades-page">

    {{-- =====================================================
        HEADER NILAI RAPOR
    ====================================================== --}}

    <div class="grades-header mb-4">

        <div class="grades-decoration grades-decoration-one"></div>
        <div class="grades-decoration grades-decoration-two"></div>

        <div class="grades-dot grades-dot-one"></div>
        <div class="grades-dot grades-dot-two"></div>

        <div class="grades-header-content">

            <div class="grades-header-icon">
                <i class="bi bi-journal-check"></i>
            </div>

            <div class="grades-header-text">

                <span class="grades-eyebrow">
                    AKADEMIK SISWA
                </span>

                <h4>NILAI RAPOR</h4>

                <p class="mb-0">
                    @if ($classroom)
                        Rekap nilai akademik kelas
                        <strong>{{ $classroom->name }}</strong>
                    @else
                        Anda belum terdaftar di kelas manapun.
                    @endif
                </p>

            </div>

        </div>

        {{-- SELECT SEMESTER --}}
        @if ($semesters->isNotEmpty())

            <div class="semester-selector">

                <div class="semester-icon">
                    <i class="bi bi-calendar3"></i>
                </div>

                <div class="semester-select-wrapper">

                    <span>SEMESTER</span>

                    <form method="GET"
                          action="{{ route('siswa.grades.index') }}">

                        <select name="semester_id"
                                class="semester-select"
                                onchange="this.form.submit()">

                            @foreach ($semesters as $s)

                                <option value="{{ $s->id }}"
                                    @selected($semester?->id === $s->id)>

                                    Semester {{ $s->name }}

                                    @if ($s->is_active)
                                        (Aktif)
                                    @endif

                                </option>

                            @endforeach

                        </select>

                    </form>

                </div>

            </div>

        @endif

    </div>


    {{-- =====================================================
        EMPTY STATE
    ====================================================== --}}

    @if ($recaps->isEmpty())

        <div class="grades-empty">

            <div class="empty-decoration empty-decoration-one"></div>
            <div class="empty-decoration empty-decoration-two"></div>

            <div class="empty-icon">
                <i class="bi bi-journal-x"></i>
            </div>

            <h5>Belum Ada Data Nilai</h5>

            <p>
                Belum ada data mata pelajaran atau nilai untuk ditampilkan.
            </p>

        </div>

    @else


        {{-- =================================================
            GRAFIK NILAI
        ================================================== --}}

        <div class="grade-chart-card mb-4">

            {{-- DEKORASI --}}
            <div class="chart-decoration chart-decoration-one"></div>
            <div class="chart-decoration chart-decoration-two"></div>
            <div class="chart-decoration chart-decoration-three"></div>

            <div class="chart-floating-icon chart-floating-one">
                <i class="bi bi-graph-up-arrow"></i>
            </div>

            <div class="chart-floating-icon chart-floating-two">
                <i class="bi bi-bar-chart-fill"></i>
            </div>

            {{-- HEADER GRAFIK --}}
            <div class="grade-chart-header">

                <div class="chart-title-wrapper">

                    <div class="chart-icon">
                        <i class="bi bi-bar-chart-fill"></i>
                    </div>

                    <div>

                        <span class="chart-eyebrow">
                            PERFORMA AKADEMIK
                        </span>

                        <h5>
                            Grafik Nilai Akhir
                        </h5>

                    </div>

                </div>

                <div class="chart-badge">
                    <i class="bi bi-graph-up-arrow"></i>
                    Nilai / Mapel
                </div>

            </div>


            {{-- AREA CHART --}}
            <div class="grade-chart-body">

                <div class="chart-top-line"></div>

                <canvas id="gradeChart"></canvas>

            </div>

        </div>


        {{-- =================================================
            TABEL NILAI
        ================================================== --}}

        <div class="grades-table-card">

            <div class="grades-table-header">

                <div class="grades-table-title">

                    <div class="grades-table-icon">
                        <i class="bi bi-table"></i>
                    </div>

                    <div>

                        <span>REKAP AKADEMIK</span>

                        <h5>
                            Nilai Mata Pelajaran
                        </h5>

                    </div>

                </div>

                <div class="subject-count">
                    {{ $recaps->count() }} Mata Pelajaran
                </div>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle grades-table mb-0">

                    <thead>
                        <tr>

                            <th>
                                Mata Pelajaran
                            </th>

                            <th class="text-center">
                                Tugas
                            </th>

                            <th class="text-center">
                                UH
                            </th>

                            <th class="text-center">
                                UTS
                            </th>

                            <th class="text-center">
                                UAS
                            </th>

                            <th class="text-center">
                                Nilai Akhir
                            </th>

                        </tr>
                    </thead>

                    <tbody>

                        @foreach ($recaps as $r)

                            <tr>

                                <td>

                                    <div class="subject-cell">

                                        <div class="subject-number">
                                            {{ sprintf('%02d', $loop->iteration) }}
                                        </div>

                                        <div class="subject-icon">
                                            <i class="bi bi-book-fill"></i>
                                        </div>

                                        <div class="subject-name">
                                            {{ $r['subject']->name }}
                                        </div>

                                    </div>

                                </td>

                                <td class="text-center">
                                    <span class="score-value">
                                        {{ $r['calc']['categoryAverages']['tugas'] ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="score-value">
                                        {{ $r['calc']['categoryAverages']['uh'] ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="score-value">
                                        {{ $r['calc']['categoryAverages']['uts'] ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    <span class="score-value">
                                        {{ $r['calc']['categoryAverages']['uas'] ?? '-' }}
                                    </span>
                                </td>

                                <td class="text-center">

                                    @if ($r['calc']['final'] !== null)

                                        <div class="final-score">

                                            <strong>
                                                {{ $r['calc']['final'] }}
                                            </strong>

                                            @unless ($r['calc']['isComplete'])

                                                <span class="temporary-badge">
                                                    Sementara
                                                </span>

                                            @endunless

                                        </div>

                                    @else

                                        <span class="score-empty">
                                            -
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>

            </div>

        </div>


        {{-- =================================================
            KEMBALI KE DASHBOARD
        ================================================== --}}

        <div class="grades-back-bottom">

            <a href="{{ route('siswa.dashboard') }}"
               class="back-dashboard">

                <i class="bi bi-arrow-left"></i>

                <span>
                    Kembali
                </span>

            </a>

        </div>


        {{-- =================================================
            CHART JS
        ================================================== --}}

        @push('scripts')

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>

        <script>

            const labels = @json($recaps->pluck('subject.name'));

            const scores = @json(
                $recaps->map(fn ($r) => $r['calc']['final'])
            );

            new Chart(document.getElementById('gradeChart'), {

                type: 'bar',

                data: {

                    labels: labels,

                    datasets: [{

                        label: 'Nilai Akhir',

                        data: scores,

                        backgroundColor: '#1769d5',

                        borderRadius: 7,

                        borderSkipped: false,

                        barThickness: 24,

                        maxBarThickness: 28

                    }]

                },

                options: {

                    responsive: true,

                    maintainAspectRatio: false,

                    scales: {

                        y: {

                            beginAtZero: true,

                            max: 100,

                            ticks: {
                                stepSize: 20,
                                color: '#7890a8',
                                font: {
                                    size: 10
                                }
                            },

                            grid: {
                                color: '#dcecff',
                                drawBorder: false
                            }

                        },

                        x: {

                            ticks: {
                                color: '#5e7894',
                                font: {
                                    size: 10,
                                    weight: '600'
                                }
                            },

                            grid: {
                                display: false
                            }

                        }

                    },

                    plugins: {

                        legend: {
                            display: false
                        },

                        tooltip: {

                            backgroundColor: '#123f70',

                            titleFont: {
                                size: 11
                            },

                            bodyFont: {
                                size: 12,
                                weight: '600'
                            },

                            padding: 10,

                            cornerRadius: 8,

                            displayColors: false

                        }

                    }

                }

            });

        </script>

        @endpush

    @endif

</div>

@endsection

