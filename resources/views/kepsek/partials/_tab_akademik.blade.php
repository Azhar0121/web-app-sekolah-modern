@php
    $totalGrades = array_sum($gradeDistribution);
    $hasGradeData = $subjectAverages->isNotEmpty() || $classroomAverages->isNotEmpty() || $totalGrades > 0;

    $gradeACount = $gradeDistribution['A'] ?? 0;
    $gradeBCount = $gradeDistribution['B'] ?? 0;
    $gradeCCount = $gradeDistribution['C'] ?? 0;
    $gradeDCount = $gradeDistribution['D'] ?? 0;

    $pctA = $totalGrades > 0 ? round(($gradeACount / $totalGrades) * 100, 1) : 0;
    $pctB = $totalGrades > 0 ? round(($gradeBCount / $totalGrades) * 100, 1) : 0;
    $pctC = $totalGrades > 0 ? round(($gradeCCount / $totalGrades) * 100, 1) : 0;
    $pctD = $totalGrades > 0 ? round(($gradeDCount / $totalGrades) * 100, 1) : 0;

    $kpiAkademik = [
        [
            'title'   => 'Rata-Rata Nilai',
            'value'   => $overallAverageScore > 0 ? number_format($overallAverageScore, 1) : '0.0',
            'sub'     => 'Skala rerata seluruh tugas',
            'icon'    => 'bi-award-fill',
            'color'   => 'primary',
            'bg_tint' => '#eff6ff',
            'border'  => '#dbeafe',
            'text_c'  => '#1d4ed8'
        ],
        [
            'title'   => 'Pemeriksaan Tugas',
            'value'   => ($teacherGradingRate ?? 0) . '%',
            'sub'     => 'Tingkat koreksi tugas oleh guru',
            'icon'    => 'bi-clipboard-check-fill',
            'color'   => 'success',
            'bg_tint' => '#f0fdf4',
            'border'  => '#bbf7d0',
            'text_c'  => '#15803d'
        ],
        [
            'title'   => 'Mapel Terekam',
            'value'   => $subjectAverages->count() . ' Mapel',
            'sub'     => 'Memiliki rekaman penilaian',
            'icon'    => 'bi-journal-bookmark-fill',
            'color'   => 'indigo',
            'bg_tint' => '#eef2ff',
            'border'  => '#c7d2fe',
            'text_c'  => '#4338ca'
        ],
        [
            'title'   => 'Predikat Unggul',
            'value'   => ($pctA + $pctB) . '%',
            'sub'     => ($gradeACount + $gradeBCount) . ' siswa predikat A & B',
            'icon'    => 'bi-stars',
            'color'   => 'warning',
            'bg_tint' => '#fffbeb',
            'border'  => '#fde68a',
            'text_c'  => '#b45309'
        ],
    ];
@endphp

@if (! $hasGradeData)
    <div class="text-center py-5 text-muted border rounded-3 bg-light">
        <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
        <div class="fw-semibold text-dark">Belum Ada Data Nilai Akademik</div>
        <small>Data nilai akan muncul setelah guru menginput nilai tugas dan ujian siswa pada sistem.</small>
    </div>
@else
    {{-- RINGKASAN KPI AKADEMIK --}}
    <div class="row g-3 mb-4">
        @foreach ($kpiAkademik as $kpi)
            <div class="col-6 col-lg-3">
                <div class="p-3 rounded-3" style="background: {{ $kpi['bg_tint'] }}; border: 1px solid {{ $kpi['border'] }};">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold text-uppercase" style="font-size: 10px; color: {{ $kpi['text_c'] }}; letter-spacing: 0.5px;">
                            {{ $kpi['title'] }}
                        </span>
                        <div class="d-flex align-items-center justify-content-center rounded-2" style="width: 28px; height: 28px; background: #ffffff; color: {{ $kpi['text_c'] }}; border: 1px solid {{ $kpi['border'] }}; font-size: 13px;">
                            <i class="bi {{ $kpi['icon'] }}"></i>
                        </div>
                    </div>
                    <div class="fw-bold mb-1" style="font-size: 1.35rem; color: #0f2747; line-height: 1.1;">
                        {{ $kpi['value'] }}
                    </div>
                    <div class="text-muted text-truncate" style="font-size: 10.5px;">
                        {{ $kpi['sub'] }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- GRAFIK BARIS 1: MAPEL & DISTRIBUSI PREDIKAT --}}
    <div class="row g-4 mb-4">
        {{-- RATA-RATA NILAI PER MATA PELAJARAN --}}
        <div class="col-lg-7">
            <div class="academic-card h-100">
                <div class="academic-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="chart-header-icon bg-primary-subtle text-primary">
                            <i class="bi bi-bar-chart-line-fill"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: #0f2747; font-size: 12.5px;">Rata-Rata Nilai per Mata Pelajaran</h6>
                            <small class="text-muted" style="font-size: 10.5px;">Skor rerata capaian siswa per mata pelajaran</small>
                        </div>
                    </div>
                    <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1" style="font-size: 10px;">
                        <i class="bi bi-graph-up-arrow me-1"></i>Skor 0-100
                    </span>
                </div>
                <div class="academic-card-body">
                    <div style="height: 250px; position: relative;">
                        <canvas id="subjectChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- DISTRIBUSI PREDIKAT NILAI --}}
        <div class="col-lg-5">
            <div class="academic-card h-100">
                <div class="academic-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="chart-header-icon bg-info-subtle text-info">
                            <i class="bi bi-pie-chart-fill"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: #0f2747; font-size: 12.5px;">Distribusi Predikat Nilai</h6>
                            <small class="text-muted" style="font-size: 10.5px;">Komposisi perolehan predikat siswa</small>
                        </div>
                    </div>
                    <span class="badge bg-light text-dark border px-2 py-1" style="font-size: 10px;">
                        <i class="bi bi-people-fill me-1"></i>{{ $totalGrades }} Nilai
                    </span>
                </div>
                <div class="academic-card-body">
                    {{-- Donut Chart dengan Center Text Metric --}}
                    <div class="position-relative mx-auto mb-3" style="height: 160px; max-width: 220px;">
                        <canvas id="gradeDistChart"></canvas>
                        <div class="position-absolute top-50 start-50 translate-middle text-center" style="pointer-events: none; margin-top: -2px;">
                            <div class="fw-bold" style="font-size: 20px; color: #0f2747; line-height: 1;">
                                {{ $totalGrades }}
                            </div>
                            <span class="text-muted fw-semibold" style="font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.5px;">
                                Total Nilai
                            </span>
                        </div>
                    </div>

                    {{-- Legend & Rincian Predikat Terstruktur (Bebas Tabrakan) --}}
                    <div class="row g-2">
                        {{-- Predikat A --}}
                        <div class="col-6">
                            <div class="grade-predicate-card" style="border-left: 3px solid #10b981;">
                                <div>
                                    <div class="d-flex align-items-center gap-1 mb-0.5">
                                        <span class="badge" style="background: #d1fae5; color: #065f46; font-size: 9px; padding: 2px 5px;">A</span>
                                        <span style="font-size: 10px; color: #475569; font-weight: 600;">&ge; 85</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 12px; color: #0f2747;">
                                        {{ $gradeACount }} <span class="fw-normal text-muted" style="font-size: 10px;">nilai</span>
                                    </div>
                                </div>
                                <span class="fw-bold" style="font-size: 11px; color: #10b981;">
                                    {{ $pctA }}%
                                </span>
                            </div>
                        </div>

                        {{-- Predikat B --}}
                        <div class="col-6">
                            <div class="grade-predicate-card" style="border-left: 3px solid #3b82f6;">
                                <div>
                                    <div class="d-flex align-items-center gap-1 mb-0.5">
                                        <span class="badge" style="background: #dbeafe; color: #1e40af; font-size: 9px; padding: 2px 5px;">B</span>
                                        <span style="font-size: 10px; color: #475569; font-weight: 600;">75 - 84</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 12px; color: #0f2747;">
                                        {{ $gradeBCount }} <span class="fw-normal text-muted" style="font-size: 10px;">nilai</span>
                                    </div>
                                </div>
                                <span class="fw-bold" style="font-size: 11px; color: #3b82f6;">
                                    {{ $pctB }}%
                                </span>
                            </div>
                        </div>

                        {{-- Predikat C --}}
                        <div class="col-6">
                            <div class="grade-predicate-card" style="border-left: 3px solid #f59e0b;">
                                <div>
                                    <div class="d-flex align-items-center gap-1 mb-0.5">
                                        <span class="badge" style="background: #fef3c7; color: #92400e; font-size: 9px; padding: 2px 5px;">C</span>
                                        <span style="font-size: 10px; color: #475569; font-weight: 600;">65 - 74</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 12px; color: #0f2747;">
                                        {{ $gradeCCount }} <span class="fw-normal text-muted" style="font-size: 10px;">nilai</span>
                                    </div>
                                </div>
                                <span class="fw-bold" style="font-size: 11px; color: #f59e0b;">
                                    {{ $pctC }}%
                                </span>
                            </div>
                        </div>

                        {{-- Predikat D --}}
                        <div class="col-6">
                            <div class="grade-predicate-card" style="border-left: 3px solid #ef4444;">
                                <div>
                                    <div class="d-flex align-items-center gap-1 mb-0.5">
                                        <span class="badge" style="background: #fee2e2; color: #991b1b; font-size: 9px; padding: 2px 5px;">D</span>
                                        <span style="font-size: 10px; color: #475569; font-weight: 600;">&lt; 65</span>
                                    </div>
                                    <div class="fw-bold" style="font-size: 12px; color: #0f2747;">
                                        {{ $gradeDCount }} <span class="fw-normal text-muted" style="font-size: 10px;">nilai</span>
                                    </div>
                                </div>
                                <span class="fw-bold" style="font-size: 11px; color: #ef4444;">
                                    {{ $pctD }}%
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- GRAFIK BARIS 2: PERBANDINGAN NILAI PER KELAS --}}
    <div class="row g-4">
        <div class="col-12">
            <div class="academic-card">
                <div class="academic-card-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="chart-header-icon bg-success-subtle text-success">
                            <i class="bi bi-building-fill"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold" style="color: #0f2747; font-size: 12.5px;">Perbandingan Nilai Rata-Rata per Kelas</h6>
                            <small class="text-muted" style="font-size: 10.5px;">Komparasi performa capaian akademik antar rombongan belajar</small>
                        </div>
                    </div>
                    <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 10px;">
                        <i class="bi bi-bar-chart-steps me-1"></i>{{ $classroomAverages->count() }} Rombel Kelas
                    </span>
                </div>
                <div class="academic-card-body">
                    <div style="height: 230px; position: relative;">
                        <canvas id="classAvgChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
