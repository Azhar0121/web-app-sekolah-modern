
@extends('layouts.admin')

@section('title', 'Laporan Keuangan ' . $year)

@section('content')
@use('App\Models\FinanceTransaction', 'Tx')

<link rel="stylesheet" href="{{ asset('css/admin/finance/report.css') }}">

<div class="container-fluid px-0 finance-report">

    {{-- HEADER --}}
    <section class="fr-hero">
        <div class="fr-hero__content">
            <span class="fr-eyebrow">
                <i class="bi bi-bar-chart-line-fill"></i>
                PORTAL TATA USAHA
            </span>

            <h3 class="fr-hero__title">Laporan Keuangan Sekolah</h3>

            <p class="fr-hero__description">
                Pantau pemasukan, pengeluaran, dan saldo sekolah berdasarkan tahun.
            </p>

            <div class="fr-hero__meta">
                <span><i class="bi bi-calendar3"></i> Tahun laporan {{ $year }}</span>
                <span><i class="bi bi-graph-up-arrow"></i> Rekap keuangan tahunan</span>
            </div>
        </div>

        <div class="fr-hero__icon">
            <i class="bi bi-clipboard-data-fill"></i>
        </div>
    </section>

    {{-- FILTER DAN CETAK --}}
    <section class="fr-toolbar">
        <div class="fr-toolbar__info">
            <span class="fr-toolbar__label">Periode Laporan</span>
            <strong>Laporan Tahun {{ $year }}</strong>
            <span class="fr-toolbar__hint">Pilih tahun untuk melihat rekap keuangan.</span>
        </div>

        <form method="GET" action="{{ route('admin.finance.report') }}" class="fr-filter">
            <div class="fr-year-field">
                <label for="year" class="form-label">Tahun</label>
                <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                    @foreach ($years as $y)
                        <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                    @endforeach
                </select>
            </div>

            <a href="{{ route('admin.finance.report.print', ['year' => $year]) }}"
               target="_blank"
               rel="noopener"
               class="btn fr-print-btn">
                <i class="bi bi-printer-fill"></i>
                <span>Cetak / PDF</span>
            </a>
        </form>
    </section>

    {{-- NAVIGASI KEUANGAN --}}
    @include('admin.finance._nav', ['active' => 'laporan'])

    {{-- RINGKASAN TAHUNAN --}}
    <section class="fr-section">
        <div class="fr-section-heading">
            <div>
                <span class="fr-section-eyebrow">RINGKASAN TAHUNAN</span>
                <h4>Ikhtisar Keuangan</h4>
                <p>Gambaran umum arus kas sekolah selama {{ $year }}.</p>
            </div>
            <span class="fr-section-badge">
                <i class="bi bi-calendar-check"></i>
                {{ $year }}
            </span>
        </div>

        <div class="row g-3 fr-summary-grid">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="fr-summary-card fr-summary-card--opening">
                    <div class="fr-summary-card__top">
                        <span class="fr-summary-card__label">Saldo Awal</span>
                        <span class="fr-summary-card__icon">
                            <i class="bi bi-wallet2"></i>
                        </span>
                    </div>
                    <div class="fr-summary-card__value">{{ Tx::rupiah($openingBalance) }}</div>
                    <div class="fr-summary-card__note">Saldo sebelum periode {{ $year }}</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="fr-summary-card fr-summary-card--income">
                    <div class="fr-summary-card__top">
                        <span class="fr-summary-card__label">Total Pemasukan</span>
                        <span class="fr-summary-card__icon">
                            <i class="bi bi-arrow-down-left-circle-fill"></i>
                        </span>
                    </div>
                    <div class="fr-summary-card__value">{{ Tx::rupiah($totalIncome) }}</div>
                    <div class="fr-summary-card__note">Akumulasi seluruh kas masuk</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="fr-summary-card fr-summary-card--expense">
                    <div class="fr-summary-card__top">
                        <span class="fr-summary-card__label">Total Pengeluaran</span>
                        <span class="fr-summary-card__icon">
                            <i class="bi bi-arrow-up-right-circle-fill"></i>
                        </span>
                    </div>
                    <div class="fr-summary-card__value">{{ Tx::rupiah($totalExpense) }}</div>
                    <div class="fr-summary-card__note">Akumulasi seluruh kas keluar</div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="fr-summary-card fr-summary-card--closing">
                    <div class="fr-summary-card__top">
                        <span class="fr-summary-card__label">Saldo Akhir</span>
                        <span class="fr-summary-card__icon">
                            <i class="bi bi-piggy-bank-fill"></i>
                        </span>
                    </div>
                    <div class="fr-summary-card__value">{{ Tx::rupiah($closingBalance) }}</div>
                    <div class="fr-summary-card__note">Saldo akhir periode {{ $year }}</div>
                </div>
            </div>
        </div>
    </section>

    {{-- GRAFIK --}}
    <section class="fr-panel fr-chart-panel">
        <div class="fr-panel__header">
            <div class="fr-panel__heading">
                <span class="fr-panel__icon fr-panel__icon--blue">
                    <i class="bi bi-bar-chart-fill"></i>
                </span>
                <div>
                    <h5>Pemasukan vs Pengeluaran</h5>
                    <p>Perbandingan arus kas setiap bulan pada tahun {{ $year }}.</p>
                </div>
            </div>
            <span class="fr-panel__tag">Grafik Bulanan</span>
        </div>

        <div class="fr-chart-body">
            <div class="fr-chart-legend">
                <span><i class="fr-legend-dot fr-legend-dot--income"></i> Pemasukan</span>
                <span><i class="fr-legend-dot fr-legend-dot--expense"></i> Pengeluaran</span>
            </div>

            <div class="fr-chart-wrap">
                <canvas id="financeChart" aria-label="Grafik pemasukan dan pengeluaran per bulan" role="img"></canvas>
            </div>
        </div>
    </section>

    {{-- REKAP BULANAN --}}
    <section class="fr-panel fr-table-panel">
        <div class="fr-panel__header">
            <div class="fr-panel__heading">
                <span class="fr-panel__icon fr-panel__icon--blue">
                    <i class="bi bi-calendar3"></i>
                </span>
                <div>
                    <h5>Rekap Bulanan</h5>
                    <p>Rincian arus kas dan saldo akhir setiap bulan.</p>
                </div>
            </div>
            <span class="fr-panel__tag">{{ count($months) }} Periode</span>
        </div>

        <div class="table-responsive fr-table-wrap">
            <table class="table fr-table align-middle mb-0">
                <thead>
                    <tr>
                        <th>Bulan</th>
                        <th class="text-end">Pemasukan</th>
                        <th class="text-end">Pengeluaran</th>
                        <th class="text-end">Selisih</th>
                        <th class="text-end">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($months as $m)
                        <tr>
                            <td>
                                <span class="fr-month-name">{{ $m['label'] }}</span>
                            </td>
                            <td class="text-end fr-amount fr-amount--income">
                                {{ $m['income'] > 0 ? Tx::rupiah($m['income']) : '—' }}
                            </td>
                            <td class="text-end fr-amount fr-amount--expense">
                                {{ $m['expense'] > 0 ? Tx::rupiah($m['expense']) : '—' }}
                            </td>
                            <td class="text-end fr-amount {{ $m['net'] < 0 ? 'fr-amount--negative' : ($m['net'] > 0 ? 'fr-amount--positive' : '') }}">
                                {{ $m['net'] != 0 ? Tx::rupiah($m['net']) : '—' }}
                            </td>
                            <td class="text-end fr-amount fr-amount--balance">
                                {{ Tx::rupiah($m['balance']) }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Total {{ $year }}</th>
                        <th class="text-end fr-amount--income">{{ Tx::rupiah($totalIncome) }}</th>
                        <th class="text-end fr-amount--expense">{{ Tx::rupiah($totalExpense) }}</th>
                        <th class="text-end {{ ($totalIncome - $totalExpense) < 0 ? 'fr-amount--negative' : 'fr-amount--positive' }}">
                            {{ Tx::rupiah($totalIncome - $totalExpense) }}
                        </th>
                        <th class="text-end">{{ Tx::rupiah($closingBalance) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="fr-table-note">
            <i class="bi bi-info-circle"></i>
            Selisih merupakan total pemasukan dikurangi total pengeluaran pada bulan tersebut.
        </div>
    </section>

    {{-- REKAP PER KATEGORI --}}
    <section class="fr-section fr-category-section">
        <div class="fr-section-heading">
            <div>
                <span class="fr-section-eyebrow">ANALISIS KATEGORI</span>
                <h4>Rincian per Kategori</h4>
                <p>Distribusi pemasukan dan pengeluaran berdasarkan kategori transaksi.</p>
            </div>
            <span class="fr-section-badge">
                <i class="bi bi-tags-fill"></i>
                Per Kategori
            </span>
        </div>

        <div class="row g-3">
            @foreach ([
                ['Pemasukan per Kategori', 'income', 'success', $incomeByCategory, $totalIncome, 'bi-arrow-down-left-circle-fill'],
                ['Pengeluaran per Kategori', 'expense', 'danger', $expenseByCategory, $totalExpense, 'bi-arrow-up-right-circle-fill']
            ] as [$title, $kind, $color, $rows, $grand, $icon])
                <div class="col-12 col-xl-6">
                    <div class="fr-panel fr-category-panel h-100 fr-category-panel--{{ $kind }}">
                        <div class="fr-panel__header">
                            <div class="fr-panel__heading">
                                <span class="fr-panel__icon fr-panel__icon--{{ $kind }}">
                                    <i class="bi {{ $icon }}"></i>
                                </span>
                                <div>
                                    <h5>{{ $title }}</h5>
                                    <p>{{ $rows->count() }} kategori tercatat</p>
                                </div>
                            </div>
                            <span class="fr-category-total fr-category-total--{{ $kind }}">
                                {{ Tx::rupiah($grand) }}
                            </span>
                        </div>

                        <ul class="list-group list-group-flush fr-category-list">
                            @forelse ($rows as $row)
                                @php($pct = $grand > 0 ? round(($row->total / $grand) * 100, 1) : 0)

                                <li class="list-group-item fr-category-item">
                                    <div class="fr-category-item__top">
                                        <div class="fr-category-item__info">
                                            <span class="fr-category-item__name">
                                                {{ $row->category?->name ?? 'Tanpa kategori' }}
                                            </span>
                                            <span class="fr-category-item__count">
                                                <i class="bi bi-receipt"></i>
                                                {{ $row->count }} transaksi
                                            </span>
                                        </div>
                                        <strong class="fr-category-item__amount fr-category-item__amount--{{ $kind }}">
                                            {{ Tx::rupiah($row->total) }}
                                        </strong>
                                    </div>

                                    <div class="fr-progress">
                                        <div class="fr-progress__bar fr-progress__bar--{{ $kind }}"
                                             role="progressbar"
                                             style="width: {{ min(100, max(0, $pct)) }}%"
                                             aria-valuenow="{{ $pct }}"
                                             aria-valuemin="0"
                                             aria-valuemax="100"></div>
                                    </div>

                                    <div class="fr-category-item__percentage">{{ $pct }}% dari total</div>
                                </li>
                            @empty
                                <li class="list-group-item fr-category-empty">
                                    <span class="fr-category-empty__icon">
                                        <i class="bi bi-inbox"></i>
                                    </span>
                                    <strong>Belum ada data</strong>
                                    <span>Belum ada transaksi kategori ini pada tahun {{ $year }}.</span>
                                </li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const canvas = document.getElementById('financeChart');
        if (!canvas || typeof Chart === 'undefined') return;

        const months = @json($chart);
        const fmt = (value) => 'Rp ' + Math.round(value).toLocaleString('id-ID');

        new Chart(canvas, {
            type: 'bar',
            data: {
                labels: months.map(month => month.label),
                datasets: [
                    {
                        label: 'Pemasukan',
                        data: months.map(month => month.income),
                        backgroundColor: '#23966b',
                        hoverBackgroundColor: '#187a56',
                        borderRadius: 5,
                        maxBarThickness: 24
                    },
                    {
                        label: 'Pengeluaran',
                        data: months.map(month => month.expense),
                        backgroundColor: '#e35d6a',
                        hoverBackgroundColor: '#c94452',
                        borderRadius: 5,
                        maxBarThickness: 24
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#102a49',
                        padding: 12,
                        cornerRadius: 7,
                        callbacks: {
                            label: (context) => context.dataset.label + ': ' + fmt(context.parsed.y)
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: '#68788d',
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 11
                            }
                        }
                    },
                    y: {
                        beginAtZero: true,
                        border: {
                            display: false,
                            dash: [4, 4]
                        },
                        grid: {
                            color: '#edf1f6'
                        },
                        ticks: {
                            color: '#68788d',
                            padding: 10,
                            callback: (value) => {
                                if (value >= 1000000000) return (value / 1000000000) + ' M';
                                if (value >= 1000000) return (value / 1000000) + ' jt';
                                if (value >= 1000) return (value / 1000) + ' rb';
                                return value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
@endsection
