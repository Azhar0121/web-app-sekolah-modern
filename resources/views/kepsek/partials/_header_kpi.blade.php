<div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
    <div>
        <h5 class="fw-bold text-dark mb-0">
            <i class="bi bi-speedometer2 text-primary me-2"></i>Dashboard Eksekutif
        </h5>
        <small class="text-muted">
            Tahun Ajaran <strong>{{ $activeYear?->name ?? '—' }}</strong>
            &nbsp;·&nbsp; {{ now()->translatedFormat('l, d F Y') }}
        </small>
    </div>
</div>

<div class="row g-2 mb-3">
    @php
    $kpiCards = [
        ['label' => 'Rata-Rata Nilai',    'value' => number_format($overallAverageScore, 1), 'icon' => 'bi-award-fill',          'color' => 'primary'],
        ['label' => 'Kehadiran Hari Ini', 'value' => $todayAttendanceRate . '%',             'icon' => 'bi-calendar-check-fill', 'color' => 'success'],
        ['label' => 'Respon Koreksi',     'value' => $teacherGradingRate . '%',              'icon' => 'bi-check-circle-fill',   'color' => 'info'],
        ['label' => 'Total Guru',         'value' => $totalTeachers,                          'icon' => 'bi-person-badge-fill',   'color' => 'warning'],
        ['label' => 'Total Siswa',        'value' => $totalStudents,                          'icon' => 'bi-people-fill',         'color' => 'danger'],
    ];
    @endphp
    @foreach ($kpiCards as $card)
    <div class="col">
        <div class="card border-0 shadow-sm rounded-3 p-3 h-100">
            <div class="d-flex align-items-center gap-2">
                <div class="rounded-circle p-2 bg-{{ $card['color'] }}-subtle text-{{ $card['color'] }} flex-shrink-0">
                    <i class="bi {{ $card['icon'] }} fs-5"></i>
                </div>
                <div class="min-width-0">
                    <div class="fw-bold fs-5 lh-1">{{ $card['value'] }}</div>
                    <small class="text-muted">{{ $card['label'] }}</small>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
