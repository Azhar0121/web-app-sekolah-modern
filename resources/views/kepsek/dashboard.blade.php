@extends('layouts.admin')

@section('title', 'Dashboard Kepala Sekolah')

@section('content')

<link rel="stylesheet" href="{{ asset('css/kepsek/dashboard.css') }}">

<div class="kepsek-dashboard">

    {{-- HEADER DASHBOARD --}}
    @include('kepsek.partials.header')


    {{-- TAB NAVIGATION --}}
    <section class="kepsek-monitoring">

        <div class="monitoring-heading">
            <div>
                <span class="section-kicker">DATA SEKOLAH</span>
                <h2>Monitoring & Analitik</h2>
            </div>

            <div class="monitoring-badge">
                <i class="bi bi-bar-chart-line-fill"></i>
                Dashboard Kepala Sekolah
            </div>
        </div>


        <div class="kepsek-tabs-wrapper">

            <ul class="nav nav-tabs kepsek-tabs"
                id="kepsekTabs"
                role="tablist">

                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link active"
                        id="tab-kehadiran"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-kehadiran"
                        type="button"
                        role="tab">

                        <i class="bi bi-calendar-check-fill"></i>
                        <span>Kehadiran</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link"
                        id="tab-akademik"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-akademik"
                        type="button"
                        role="tab">

                        <i class="bi bi-bar-chart-line-fill"></i>
                        <span>Akademik</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link"
                        id="tab-guru"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-guru"
                        type="button"
                        role="tab">

                        <i class="bi bi-person-workspace"></i>
                        <span>Guru</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link"
                        id="tab-ppdb"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-ppdb"
                        type="button"
                        role="tab">

                        <i class="bi bi-graph-up-arrow"></i>
                        <span>PPDB & Alumni</span>
                    </button>
                </li>

                <li class="nav-item" role="presentation">
                    <button
                        class="nav-link"
                        id="tab-finansial"
                        data-bs-toggle="tab"
                        data-bs-target="#pane-finansial"
                        type="button"
                        role="tab">

                        <i class="bi bi-cash-coin"></i>
                        <span>Finansial</span>
                    </button>
                </li>

            </ul>


            {{-- TAB CONTENT --}}
            <div
                class="tab-content kepsek-tab-content"
                id="kepsekTabContent">

                <div
                    class="tab-pane fade show active"
                    id="pane-kehadiran"
                    role="tabpanel">

                    @include('kepsek.partials._tab_kehadiran')

                </div>


                <div
                    class="tab-pane fade"
                    id="pane-akademik"
                    role="tabpanel">

                    @include('kepsek.partials._tab_akademik')

                </div>


                <div
                    class="tab-pane fade"
                    id="pane-guru"
                    role="tabpanel">

                    @include('kepsek.partials._tab_guru')

                </div>


                <div
                    class="tab-pane fade"
                    id="pane-ppdb"
                    role="tabpanel">

                    @include('kepsek.partials._tab_ppdb')

                </div>


                <div
                    class="tab-pane fade"
                    id="pane-finansial"
                    role="tabpanel">

                    @include('kepsek.partials._tab_finansial')

                </div>

            </div>

        </div>

    </section>

</div>


@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
(function () {

    'use strict';


    const subjectData   = @json($subjectAverages);
    const gradeDistData = @json($gradeDistribution);
    const classAvgData  = @json($classroomAverages);
    const teacherData   = @json($teacherPerformance);
    const todayAtt      = @json($todayAttendanceDistribution);
    const ppdbData      = @json($ppdbTrend);
    const studentData   = @json($studentAlumniStats);
    const finData       = @json($financialSummary);


    Chart.defaults.animation = false;
    Chart.defaults.responsive = true;
    Chart.defaults.maintainAspectRatio = false;

    Chart.defaults.plugins.legend.display = false;

    Chart.defaults.plugins.tooltip.mode = 'index';


    const gridColor = 'rgba(7,27,53,.08)';


    const scaleY = (max) => ({
        y: {
            beginAtZero: true,
            ...(max ? { max } : {}),
            grid: {
                color: gridColor
            },
            ticks: {
                precision: 0
            }
        },

        x: {
            grid: {
                display: false
            }
        }
    });


    const BR = 6;


    function mkChart(id, config) {

        const el = document.getElementById(id);

        if (!el) {
            return null;
        }

        return new Chart(el, config);
    }


    const rendered = {
        kehadiran: false,
        akademik: false,
        guru: false,
        'ppdb-trend': false,
        alumni: false,
        finansial: false
    };


    function renderTab(tabName) {

        if (rendered[tabName]) {
            return;
        }

        rendered[tabName] = true;


        /* KEHADIRAN */
        if (tabName === 'kehadiran') {

            mkChart('todayAttendanceChart', {

                type: 'doughnut',

                data: {

                    labels: [
                        'Hadir',
                        'Izin',
                        'Sakit',
                        'Alpha'
                    ],

                    datasets: [{
                        data: [
                            todayAtt.hadir,
                            todayAtt.izin,
                            todayAtt.sakit,
                            todayAtt.alpha
                        ],

                        backgroundColor: [
                            '#198754',
                            '#ffc107',
                            '#0dcaf0',
                            '#dc3545'
                        ],

                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                }

            });

        }


        /* AKADEMIK */
        if (tabName === 'akademik') {

            mkChart('subjectChart', {

                type: 'bar',

                data: {

                    labels: subjectData.map(
                        i => i.name
                    ),

                    datasets: [{
                        label: 'Rata-Rata Nilai',

                        data: subjectData.map(
                            i => i.score
                        ),

                        backgroundColor: '#1769d5',

                        borderRadius: BR
                    }]
                },

                options: {
                    scales: scaleY(100)
                }

            });


            mkChart('gradeDistChart', {

                type: 'doughnut',

                data: {

                    labels: [
                        'A',
                        'B',
                        'C',
                        'D'
                    ],

                    datasets: [{

                        data: [
                            gradeDistData.A,
                            gradeDistData.B,
                            gradeDistData.C,
                            gradeDistData.D
                        ],

                        backgroundColor: [
                            '#198754',
                            '#1769d5',
                            '#ffc107',
                            '#dc3545'
                        ],

                        borderWidth: 3,
                        borderColor: '#ffffff'
                    }]
                }

            });


            mkChart('classAvgChart', {

                type: 'bar',

                data: {

                    labels: classAvgData.map(
                        i => i.name
                    ),

                    datasets: [{

                        label: 'Rata-Rata',

                        data: classAvgData.map(
                            i => i.score
                        ),

                        backgroundColor: '#198754',

                        borderRadius: BR
                    }]
                },

                options: {
                    scales: scaleY(100)
                }

            });

        }


        /* GURU */
        if (tabName === 'guru') {

            mkChart('teacherPerfChart', {

                type: 'bar',

                data: {

                    labels: teacherData.map(
                        t => t.name
                    ),

                    datasets: [

                        {
                            label: 'Modul',

                            data: teacherData.map(
                                t => t.material_count
                            ),

                            backgroundColor: '#6366f1',

                            borderRadius: 5
                        },

                        {
                            label: 'Tugas',

                            data: teacherData.map(
                                t => t.task_count
                            ),

                            backgroundColor: '#0dcaf0',

                            borderRadius: 5
                        }

                    ]
                },

                options: {

                    plugins: {

                        legend: {
                            display: true,
                            position: 'bottom',

                            labels: {
                                boxWidth: 10,
                                font: {
                                    size: 11
                                }
                            }
                        }

                    },

                    scales: scaleY()

                }

            });

        }


        /* PPDB */
        if (tabName === 'ppdb-trend') {

            if (!ppdbData.isEmpty) {

                const pd = ppdbData.chartData;


                mkChart('ppdbTrendChart', {

                    type: 'line',

                    data: {

                        labels: pd.map(
                            d => d.label
                        ),

                        datasets: [

                            {
                                label: 'Pendaftar',

                                data: pd.map(
                                    d => d.total
                                ),

                                borderColor: '#1769d5',

                                backgroundColor: 'rgba(23,105,213,.10)',

                                fill: true,

                                tension: .35,

                                pointRadius: 4,

                                pointHoverRadius: 6
                            },

                            {
                                label: 'Diterima',

                                data: pd.map(
                                    d => d.accepted
                                ),

                                borderColor: '#198754',

                                backgroundColor: 'rgba(25,135,84,.08)',

                                fill: true,

                                tension: .35,

                                pointRadius: 4,

                                pointHoverRadius: 6
                            },

                            {
                                label: 'Ditolak',

                                data: pd.map(
                                    d => d.rejected
                                ),

                                borderColor: '#dc3545',

                                backgroundColor: 'rgba(220,53,69,.05)',

                                fill: true,

                                tension: .35,

                                pointRadius: 4,

                                pointHoverRadius: 6
                            }

                        ]
                    },

                    options: {

                        plugins: {

                            legend: {
                                display: true,
                                position: 'bottom',

                                labels: {
                                    boxWidth: 10,
                                    font: {
                                        size: 11
                                    }
                                }
                            }

                        },

                        scales: scaleY()

                    }

                });

            }

        }


        /* ALUMNI */
        if (tabName === 'alumni') {

            if (!studentData.isEmpty) {

                mkChart('studentTrendChart', {

                    type: 'bar',

                    data: {

                        labels: studentData.chartData.map(
                            d => d.label
                        ),

                        datasets: [{

                            label: 'Siswa Aktif',

                            data: studentData.chartData.map(
                                d => d.total
                            ),

                            backgroundColor: '#198754',

                            borderRadius: BR
                        }]
                    },

                    options: {
                        scales: scaleY()
                    }

                });

            }

        }


        /* FINANSIAL */
        if (
            tabName === 'finansial' &&
            !finData.isEmpty
        ) {

            mkChart('financialMonthlyChart', {

                type: 'bar',

                data: {

                    labels: finData.monthlyData.map(
                        d => d.label
                    ),

                    datasets: [{

                        label: 'Pemasukan',

                        data: finData.monthlyData.map(
                            d => d.total
                        ),

                        backgroundColor: '#198754',

                        borderRadius: BR
                    }]
                },

                options: {

                    scales: {

                        y: {

                            beginAtZero: true,

                            grid: {
                                color: gridColor
                            },

                            ticks: {

                                callback: function (v) {
                                    return 'Rp ' +
                                        (v / 1e6).toFixed(0) +
                                        'jt';
                                }

                            }

                        },

                        x: {
                            grid: {
                                display: false
                            }
                        }

                    }

                }

            });


            mkChart('financialStatusChart', {

                type: 'doughnut',

                data: {

                    labels: finData.statusChart.map(
                        d => d.label
                    ),

                    datasets: [{

                        data: finData.statusChart.map(
                            d => d.value
                        ),

                        backgroundColor:
                            finData.statusChart.map(
                                d => d.color
                            ),

                        borderWidth: 3,

                        borderColor: '#ffffff'
                    }]
                }

            });

        }

    }


    document.addEventListener(
        'DOMContentLoaded',
        function () {

            renderTab('kehadiran');


            const tabMap = {

                'tab-akademik': 'akademik',

                'tab-guru': 'guru',

                'tab-finansial': 'finansial'

            };


            Object.entries(tabMap).forEach(
                ([btnId, tabName]) => {

                    const btn =
                        document.getElementById(btnId);

                    if (btn) {

                        btn.addEventListener(
                            'shown.bs.tab',
                            () => renderTab(tabName)
                        );

                    }

                }
            );


            const ppdbMainTab =
                document.getElementById('tab-ppdb');


            if (ppdbMainTab) {

                ppdbMainTab.addEventListener(
                    'shown.bs.tab',
                    () => renderTab('ppdb-trend')
                );

            }


            const alumniSubTab =
                document.getElementById('subtab-alumni');


            if (alumniSubTab) {

                alumniSubTab.addEventListener(
                    'shown.bs.tab',
                    () => renderTab('alumni')
                );

            }

        }
    );

})();
</script>

@endpush

@endsection