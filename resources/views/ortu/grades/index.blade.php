 @extends('layouts.app')

@section('title', 'Nilai Rapor — ' . $student->name)

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/grades/index.css') }}">

<div class="ortu-grades-page">

    {{-- HERO NILAI RAPOR --}}
    <section class="gr-hero">
        <div class="gr-hero-content">
            <span class="gr-eyebrow">
                <i class="bi bi-mortarboard-fill"></i>
                PORTAL ORANG TUA / WALI
            </span>

            <h1>Nilai Rapor</h1>

            <p>
                Pantau perkembangan akademik
                <strong>{{ $student->name }}</strong>
                melalui rekap nilai setiap mata pelajaran.
            </p>

            <div class="gr-hero-meta">
                <span>
                    <i class="bi bi-person-check"></i>
                    {{ $student->name }}
                </span>

                @if ($classroom)
                    <span>
                        <i class="bi bi-people"></i>
                        Kelas {{ $classroom->name }}
                    </span>
                @else
                    <span>
                        <i class="bi bi-info-circle"></i>
                        Belum terdaftar di kelas
                    </span>
                @endif
            </div>
        </div>

        <div class="gr-hero-icon">
            <i class="bi bi-journal-bookmark-fill"></i>
        </div>
    </section>

    {{-- PILIH SEMESTER --}}
    <section class="gr-semester-panel">
        <div class="gr-panel-heading">
            <div class="gr-panel-icon">
                <i class="bi bi-calendar3"></i>
            </div>

            <div class="gr-panel-copy">
                <span class="gr-section-label">PERIODE AKADEMIK</span>
                <h2>Ringkasan Nilai Siswa</h2>
                <p>Pilih semester untuk melihat hasil belajar.</p>
            </div>
        </div>

        @if ($semesters->isNotEmpty())
            <form method="GET"
                  action="{{ route('ortu.grades.index', $student) }}"
                  class="gr-semester-form">

                <label for="semester_id">
                    <i class="bi bi-funnel"></i>
                    Semester
                </label>

                <select
                    name="semester_id"
                    id="semester_id"
                    class="form-select gr-semester-select"
                    onchange="this.form.submit()"
                >
                    @foreach ($semesters as $s)
                        <option value="{{ $s->id }}" @selected($semester?->id === $s->id)>
                            Semester {{ $s->name }} @if ($s->is_active) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
            </form>
        @endif
    </section>

    @if ($recaps->isEmpty())

        {{-- EMPTY STATE --}}
        <section class="gr-empty-state">
            <div class="gr-empty-icon">
                <i class="bi bi-journal-x"></i>
            </div>

            <h3>Belum Ada Data Nilai</h3>

            <p>
                Belum ada data mata pelajaran atau nilai untuk ditampilkan.
                Nilai akan muncul di sini setelah data tersedia.
            </p>
        </section>

    @else

        {{-- GRAFIK NILAI --}}
        <section class="gr-chart-card">
            <div class="gr-card-heading">
                <div class="gr-card-heading-main">
                    <div class="gr-card-icon">
                        <i class="bi bi-bar-chart-line-fill"></i>
                    </div>

                    <div>
                        <span class="gr-section-label">PERFORMA AKADEMIK</span>
                        <h2>Grafik Nilai Akhir</h2>
                        <p>Perbandingan hasil akhir setiap mata pelajaran.</p>
                    </div>
                </div>

                <span class="gr-chart-tag">
                    <i class="bi bi-graph-up"></i>
                    Skala 0–100
                </span>
            </div>

            <div class="gr-chart-body">
                <canvas id="gradeChart" height="100"></canvas>
            </div>
        </section>

        {{-- TABEL NILAI --}}
        <section class="gr-table-card">
            <div class="gr-card-heading gr-table-heading">
                <div class="gr-card-heading-main">
                    <div class="gr-card-icon gr-table-icon">
                        <i class="bi bi-table"></i>
                    </div>

                    <div>
                        <span class="gr-section-label">REKAP PEMBELAJARAN</span>
                        <h2>Rincian Nilai Mata Pelajaran</h2>
                        <p>Nilai tugas, ulangan, ujian tengah semester, dan ujian akhir semester.</p>
                    </div>
                </div>

                <span class="gr-subject-count">
                    <i class="bi bi-journal-text"></i>
                    {{ $recaps->count() }} mata pelajaran
                </span>
            </div>

            <div class="table-responsive gr-table-responsive">
                <table class="table gr-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="gr-number-column">NO.</th>
                            <th>MATA PELAJARAN</th>
                            <th class="text-center">TUGAS</th>
                            <th class="text-center">UH</th>
                            <th class="text-center">UTS</th>
                            <th class="text-center">UAS</th>
                            <th class="text-center">NILAI AKHIR</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($recaps as $r)
                            <tr>
                                <td class="gr-number-column">
                                    <span class="gr-row-number">{{ $loop->iteration }}</span>
                                </td>

                                <td>
                                    <div class="gr-subject">
                                        <span class="gr-subject-icon">
                                            <i class="bi bi-book-half"></i>
                                        </span>
                                        <span class="gr-subject-name">
                                            {{ $r['subject']->name }}
                                        </span>
                                    </div>
                                </td>

                                <td class="text-center gr-score-cell">
                                    {{ $r['calc']['categoryAverages']['tugas'] ?? '-' }}
                                </td>

                                <td class="text-center gr-score-cell">
                                    {{ $r['calc']['categoryAverages']['uh'] ?? '-' }}
                                </td>

                                <td class="text-center gr-score-cell">
                                    {{ $r['calc']['categoryAverages']['uts'] ?? '-' }}
                                </td>

                                <td class="text-center gr-score-cell">
                                    {{ $r['calc']['categoryAverages']['uas'] ?? '-' }}
                                </td>

                                <td class="text-center">
                                    @if ($r['calc']['final'] !== null)
                                        <div class="gr-final-score">
                                            <strong>{{ $r['calc']['final'] }}</strong>

                                            @unless ($r['calc']['isComplete'])
                                                <span class="gr-temporary-badge">Sementara</span>
                                            @endunless
                                        </div>
                                    @else
                                        <span class="gr-no-score">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="gr-table-footer">
                <span>
                    <i class="bi bi-info-circle"></i>
                    Nilai sementara menandakan komponen penilaian belum lengkap.
                </span>
            </div>
        </section>

        @push('scripts')
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const canvas = document.getElementById('gradeChart');

                    if (!canvas || typeof Chart === 'undefined') {
                        return;
                    }

                    const labels = @json($recaps->pluck('subject.name'));
                    const scores = @json($recaps->map(fn ($r) => $r['calc']['final']));

                    new Chart(canvas, {
                        type: 'bar',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Nilai Akhir',
                                data: scores,
                                backgroundColor: '#3986e8',
                                hoverBackgroundColor: '#1769d5',
                                borderColor: '#1769d5',
                                borderWidth: 1,
                                borderRadius: 5,
                                maxBarThickness: 42,
                            }],
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: true,
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    max: 100,
                                    ticks: {
                                        stepSize: 20,
                                        color: '#718198',
                                        font: {
                                            family: 'Plus Jakarta Sans',
                                            size: 10,
                                        },
                                    },
                                    grid: {
                                        color: '#edf1f7',
                                        drawBorder: false,
                                    },
                                },
                                x: {
                                    ticks: {
                                        color: '#536780',
                                        font: {
                                            family: 'Plus Jakarta Sans',
                                            size: 10,
                                        },
                                    },
                                    grid: {
                                        display: false,
                                    },
                                },
                            },
                            plugins: {
                                legend: {
                                    display: false,
                                },
                                tooltip: {
                                    backgroundColor: '#102c4d',
                                    padding: 12,
                                    titleFont: {
                                        family: 'Plus Jakarta Sans',
                                    },
                                    bodyFont: {
                                        family: 'Plus Jakarta Sans',
                                    },
                                    callbacks: {
                                        label: function (context) {
                                            const value = context.raw;
                                            return 'Nilai: ' + (value ?? '-');
                                        },
                                    },
                                },
                            },
                        },
                    });
                });
            </script>
        @endpush

    @endif

    {{-- KEMBALI --}}
    <div class="gr-footer">
        <a href="{{ route('ortu.dashboard') }}" class="gr-back-button">
            <i class="bi bi-arrow-left"></i>
            Kembali ke Dashboard
        </a>

        <span class="gr-footer-note">
            <i class="bi bi-shield-check"></i>
            Informasi akademik siswa
        </span>
    </div>

</div>
@endsection