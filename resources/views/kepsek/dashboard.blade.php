@extends('layouts.admin')

@section('title', 'Dashboard Eksekutif Kepala Sekolah')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER PAGE --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-speedometer2 text-primary me-2"></i> Dashboard Eksekutif Kepala Sekolah
            </h3>
            <p class="text-secondary mb-0 small">
                Analitik performa akademik siswa, keaktifan pengajaran guru, dan tingkat kehadiran harian sekolah.
            </p>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                <i class="bi bi-calendar-event me-1"></i> Tahun Ajaran {{ $activeYear?->name ?? 'Aktif' }}
            </span>
        </div>
    </div>

    {{-- KPI EXECUTIVE CARDS --}}
    <div class="row g-3 mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small fw-medium d-block mb-1">Rata-Rata Nilai Sekolah</span>
                        <h3 class="fw-bold text-dark mb-0">{{ number_format($overallAverageScore, 1) }}</h3>
                    </div>
                    <div class="rounded-circle p-3 bg-primary-subtle text-primary">
                        <i class="bi bi-award-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small fw-medium d-block mb-1">Tingkat Kehadiran Sekolah</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $todayAttendanceRate }}%</h3>
                    </div>
                    <div class="rounded-circle p-3 bg-success-subtle text-success">
                        <i class="bi bi-calendar-check-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small fw-medium d-block mb-1">Respon Koreksi Guru</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $teacherGradingRate }}%</h3>
                    </div>
                    <div class="rounded-circle p-3 bg-info-subtle text-info">
                        <i class="bi bi-check-circle-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-secondary small fw-medium d-block mb-1">Total Guru Pengampu</span>
                        <h3 class="fw-bold text-dark mb-0">{{ $totalTeachers }}</h3>
                    </div>
                    <div class="rounded-circle p-3 bg-warning-subtle text-warning-emphasis">
                        <i class="bi bi-person-badge-fill fs-4"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODUL 1: TINGKAT KEHADIRAN HARIAN --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h6 class="fw-bold text-dark mb-0">
                    <i class="bi bi-calendar-check-fill text-success me-2"></i> Tingkat Kehadiran Harian Sekolah
                </h6>
                <small class="text-secondary">Statistik & distribusi presensi harian siswa/guru tanggal {{ now()->translatedFormat('d F Y') }}</small>
            </div>
            <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                Tingkat Kehadiran: <strong>{{ $todayAttendanceRate }}%</strong>
            </span>
        </div>
        <div class="card-body p-4">
            <div class="row g-4 align-items-center">
                
                {{-- GRAFIK DONUT PRESENSI HARIAN --}}
                <div class="col-lg-5 col-md-6 text-center border-end-lg">
                    <div class="mx-auto" style="width: 220px; height: 220px;">
                        <canvas id="todayAttendanceChart"></canvas>
                    </div>
                    <div class="mt-3 small text-secondary">
                        Total Rekaman Presensi Hari Ini: <strong class="text-dark">{{ $todayTotal }}</strong>
                    </div>
                </div>

                {{-- KARTU STATUS PRESENSI HARIAN --}}
                <div class="col-lg-7 col-md-6">
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-check-circle-fill text-success"></i>
                                    <span class="text-success fw-semibold small">HADIR</span>
                                </div>
                                <h3 class="fw-bold text-success mb-0">{{ $todayAttendanceDistribution['hadir'] }}</h3>
                                <small class="text-success-emphasis">Siswa / Guru</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-warning-subtle border border-warning-subtle">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-clock-history text-warning-emphasis"></i>
                                    <span class="text-warning-emphasis fw-semibold small">IZIN</span>
                                </div>
                                <h3 class="fw-bold text-warning-emphasis mb-0">{{ $todayAttendanceDistribution['izin'] }}</h3>
                                <small class="text-warning-emphasis">Siswa / Guru</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-info-subtle border border-info-subtle">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-hospital-fill text-info"></i>
                                    <span class="text-info-emphasis fw-semibold small">SAKIT</span>
                                </div>
                                <h3 class="fw-bold text-info-emphasis mb-0">{{ $todayAttendanceDistribution['sakit'] }}</h3>
                                <small class="text-info-emphasis">Siswa / Guru</small>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="p-3 rounded-3 bg-danger-subtle border border-danger-subtle">
                                <div class="d-flex align-items-center gap-2 mb-1">
                                    <i class="bi bi-x-circle-fill text-danger"></i>
                                    <span class="text-danger fw-semibold small">ALPHA / TANPA KETERANGAN</span>
                                </div>
                                <h3 class="fw-bold text-danger mb-0">{{ $todayAttendanceDistribution['alpha'] }}</h3>
                                <small class="text-danger-emphasis">Siswa / Guru</small>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    {{-- ROW 1: GRAFIK NILAI MAPEL & DISTRIBUSI PREDIKAT --}}
    <div class="row g-4 mb-4">
        
        {{-- GRAFIK RATA-RATA NILAI PER MAPEL --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-bar-chart-line-fill text-primary me-2"></i> Performa Akademik Siswa per Mata Pelajaran
                    </h6>
                    <span class="badge bg-light text-secondary border">Rata-Rata Nilai</span>
                </div>
                <div class="card-body p-4">
                    <canvas id="subjectChart" height="260"></canvas>
                </div>
            </div>
        </div>

        {{-- DISTRIBUSI PREDIKAT SISWA --}}
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-pie-chart-fill text-primary me-2"></i> Distribusi Predikat Nilai Siswa
                    </h6>
                </div>
                <div class="card-body p-4 d-flex flex-column align-items-center justify-content-center">
                    <div style="width: 230px; height: 230px;">
                        <canvas id="gradeDistChart"></canvas>
                    </div>
                    <div class="mt-3 text-center small text-secondary">
                        <span class="me-2"><i class="bi bi-circle-fill text-success me-1"></i> A (&ge;85)</span>
                        <span class="me-2"><i class="bi bi-circle-fill text-primary me-1"></i> B (75-84)</span>
                        <span class="me-2"><i class="bi bi-circle-fill text-warning me-1"></i> C (65-74)</span>
                        <span><i class="bi bi-circle-fill text-danger me-1"></i> D (&lt;65)</span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    {{-- ROW 2: GRAFIK PERFORMA GURU & NILA PER KELAS --}}
    <div class="row g-4 mb-4">

        {{-- GRAFIK RATA-RATA NILAI PER KELAS --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-building-fill text-primary me-2"></i> Perbandingan Rata-Rata Nilai per Kelas
                    </h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="classAvgChart" height="220"></canvas>
                </div>
            </div>
        </div>

        {{-- GRAFIK KEAKTIFAN PENGAJARAN GURU --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-header bg-white py-3 px-4 border-bottom">
                    <h6 class="fw-bold text-dark mb-0">
                        <i class="bi bi-person-workspace text-primary me-2"></i> Keaktifan Modul & Pengoreksian Tugas Guru
                    </h6>
                </div>
                <div class="card-body p-4">
                    <canvas id="teacherPerfChart" height="220"></canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- TABEL DETAIL PERFORMA & KEAKTIFAN GURU --}}
    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h6 class="fw-bold text-dark mb-0">
                <i class="bi bi-table text-primary me-2"></i> Laporan Keaktifan & Kinerja Pengajaran Guru
            </h6>
            <span class="badge bg-secondary-subtle text-secondary border">Total {{ $teacherPerformance->count() }} Guru</span>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th class="px-4 py-3 text-uppercase text-secondary small fw-semibold">Nama Guru</th>
                        <th class="py-3 text-uppercase text-secondary small fw-semibold">Kelas & Mapel Ajar</th>
                        <th class="py-3 text-uppercase text-secondary small fw-semibold text-center">Modul Diterbitkan</th>
                        <th class="py-3 text-uppercase text-secondary small fw-semibold text-center">Tugas Dibuat</th>
                        <th class="py-3 text-uppercase text-secondary small fw-semibold text-center">Tugas Dinilai (%)</th>
                        <th class="px-4 py-3 text-uppercase text-secondary small fw-semibold text-end">Rata-Rata Nilai Siswa</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($teacherPerformance as $tp)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="fw-bold text-dark">{{ $tp['name'] }}</div>
                            </td>
                            <td class="py-3">
                                <span class="badge bg-light text-dark border font-monospace">
                                    {{ $tp['assignments_count'] }} Penugasan
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2.5 py-1">
                                    {{ $tp['material_count'] }} Modul
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <span class="badge bg-info-subtle text-info border border-info-subtle px-2.5 py-1">
                                    {{ $tp['task_count'] }} Tugas
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 6px; max-width: 80px;">
                                        <div class="progress-bar {{ $tp['grading_rate'] >= 80 ? 'bg-success' : ($tp['grading_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}" 
                                             role="progressbar" 
                                             style="width: {{ $tp['grading_rate'] }}%;"></div>
                                    </div>
                                    <span class="small fw-semibold">{{ $tp['grading_rate'] }}%</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <span class="fw-bold text-dark fs-6">{{ number_format($tp['avg_student_score'], 1) }}</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-secondary">Belum ada data guru pengampu.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- SCRIPT CHART.JS --}}
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    // DATA DARI CONTROLLER
    const subjectData    = @json($subjectAverages);
    const gradeDistData  = @json($gradeDistribution);
    const classAvgData   = @json($classroomAverages);
    const teacherData    = @json($teacherPerformance);
    const todayAttData   = @json($todayAttendanceDistribution);

    // 0. CHART PRESENSI HARIAN (DOUGHNUT CHART)
    const ctxTodayAtt = document.getElementById('todayAttendanceChart').getContext('2d');
    new Chart(ctxTodayAtt, {
        type: 'doughnut',
        data: {
            labels: ['Hadir', 'Izin', 'Sakit', 'Alpha'],
            datasets: [{
                data: [todayAttData.hadir, todayAttData.izin, todayAttData.sakit, todayAttData.alpha],
                backgroundColor: ['#10b981', '#f59e0b', '#0ea5e9', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 1. CHART RATA-RATA MAPEL (BAR CHART)
    const ctxSubject = document.getElementById('subjectChart').getContext('2d');
    new Chart(ctxSubject, {
        type: 'bar',
        data: {
            labels: subjectData.map(item => item.name),
            datasets: [{
                label: 'Rata-Rata Nilai',
                data: subjectData.map(item => item.score),
                backgroundColor: 'rgba(37, 99, 235, 0.75)',
                borderColor: '#2563eb',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });

    // 2. CHART DISTRIBUSI PREDIKAT (DOUGHNUT CHART)
    const ctxDist = document.getElementById('gradeDistChart').getContext('2d');
    new Chart(ctxDist, {
        type: 'doughnut',
        data: {
            labels: ['Predikat A', 'Predikat B', 'Predikat C', 'Predikat D'],
            datasets: [{
                data: [gradeDistData.A, gradeDistData.B, gradeDistData.C, gradeDistData.D],
                backgroundColor: ['#10b981', '#2563eb', '#f59e0b', '#ef4444'],
                borderWidth: 2,
                borderColor: '#ffffff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });

    // 3. CHART RATA-RATA PER KELAS (BAR CHART)
    const ctxClass = document.getElementById('classAvgChart').getContext('2d');
    new Chart(ctxClass, {
        type: 'bar',
        data: {
            labels: classAvgData.map(item => item.name),
            datasets: [{
                label: 'Rata-Rata Nilai Kelas',
                data: classAvgData.map(item => item.score),
                backgroundColor: 'rgba(16, 185, 129, 0.75)',
                borderColor: '#10b981',
                borderWidth: 1,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, max: 100 }
            }
        }
    });

    // 4. CHART KEAKTIFAN GURU (BAR CHART)
    const ctxTeacher = document.getElementById('teacherPerfChart').getContext('2d');
    new Chart(ctxTeacher, {
        type: 'bar',
        data: {
            labels: teacherData.map(t => t.name),
            datasets: [
                {
                    label: 'Modul Diterbitkan',
                    data: teacherData.map(t => t.material_count),
                    backgroundColor: 'rgba(99, 102, 241, 0.75)',
                    borderRadius: 4
                },
                {
                    label: 'Tugas Dibuat',
                    data: teacherData.map(t => t.task_count),
                    backgroundColor: 'rgba(14, 165, 233, 0.75)',
                    borderRadius: 4
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true, precision: 0 }
            }
        }
    });

});
</script>
@endpush
@endsection
