@extends('layouts.admin')

@section('title', 'Laporan Keuangan ' . $year)

@section('content')
@use('App\Models\FinanceTransaction', 'Tx')

<div class="container-fluid px-0">

    <div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
        <div>
            <span class="text-uppercase small fw-semibold text-primary">Portal Tata Usaha</span>
            <h3 class="fw-bold mb-1">Keuangan Sekolah</h3>
            <p class="text-muted mb-0">Rekap kas masuk dan kas keluar per bulan dan per kategori.</p>
        </div>

        <form method="GET" action="{{ route('admin.finance.report') }}" class="d-flex gap-2 align-items-center">
            <label for="year" class="form-label small mb-0">Tahun</label>
            <select name="year" id="year" class="form-select" onchange="this.form.submit()">
                @foreach ($years as $y)
                    <option value="{{ $y }}" @selected($y == $year)>{{ $y }}</option>
                @endforeach
            </select>
            <a href="{{ route('admin.finance.report.print', ['year' => $year]) }}" target="_blank" class="btn btn-outline-secondary text-nowrap">
                <i class="bi bi-printer me-1"></i> Cetak / PDF
            </a>
        </form>
    </div>

    @include('admin.finance._nav', ['active' => 'laporan'])

    {{-- RINGKASAN TAHUNAN --}}
    <div class="row g-3 mb-4">
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">Saldo awal {{ $year }}</div>
                <div class="fs-5 fw-bold">{{ Tx::rupiah($openingBalance) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">Total pemasukan</div>
                <div class="fs-5 fw-bold text-success">{{ Tx::rupiah($totalIncome) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100"><div class="card-body">
                <div class="text-muted small">Total pengeluaran</div>
                <div class="fs-5 fw-bold text-danger">{{ Tx::rupiah($totalExpense) }}</div>
            </div></div>
        </div>
        <div class="col-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100 bg-primary text-white"><div class="card-body">
                <div class="small opacity-75">Saldo akhir {{ $year }}</div>
                <div class="fs-5 fw-bold">{{ Tx::rupiah($closingBalance) }}</div>
            </div></div>
        </div>
    </div>

    {{-- GRAFIK --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Pemasukan vs Pengeluaran per Bulan</div>
        <div class="card-body">
            <div style="position: relative; height: 320px;">
                <canvas id="financeChart"></canvas>
            </div>
        </div>
    </div>

    {{-- REKAP BULANAN --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Rekap Bulanan</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
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
                            <td>{{ $m['label'] }}</td>
                            <td class="text-end text-success">{{ $m['income'] > 0 ? Tx::rupiah($m['income']) : '-' }}</td>
                            <td class="text-end text-danger">{{ $m['expense'] > 0 ? Tx::rupiah($m['expense']) : '-' }}</td>
                            <td class="text-end {{ $m['net'] < 0 ? 'text-danger' : '' }}">{{ $m['net'] != 0 ? Tx::rupiah($m['net']) : '-' }}</td>
                            <td class="text-end fw-semibold">{{ Tx::rupiah($m['balance']) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot class="table-light fw-bold">
                    <tr>
                        <td>Total {{ $year }}</td>
                        <td class="text-end text-success">{{ Tx::rupiah($totalIncome) }}</td>
                        <td class="text-end text-danger">{{ Tx::rupiah($totalExpense) }}</td>
                        <td class="text-end">{{ Tx::rupiah($totalIncome - $totalExpense) }}</td>
                        <td class="text-end">{{ Tx::rupiah($closingBalance) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- PER KATEGORI --}}
    <div class="row g-4">
        @foreach ([['Pemasukan per Kategori', 'success', $incomeByCategory, $totalIncome], ['Pengeluaran per Kategori', 'danger', $expenseByCategory, $totalExpense]] as [$title, $color, $rows, $grand])
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white fw-semibold text-{{ $color }}">{{ $title }}</div>
                    <ul class="list-group list-group-flush">
                        @forelse ($rows as $row)
                            @php($pct = $grand > 0 ? round(($row->total / $grand) * 100, 1) : 0)
                            <li class="list-group-item">
                                <div class="d-flex justify-content-between">
                                    <span>{{ $row->category?->name ?? 'Tanpa kategori' }} <span class="small text-muted">({{ $row->count }} transaksi)</span></span>
                                    <strong>{{ Tx::rupiah($row->total) }}</strong>
                                </div>
                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar bg-{{ $color }}" style="width: {{ $pct }}%"></div>
                                </div>
                                <div class="small text-muted text-end">{{ $pct }}%</div>
                            </li>
                        @empty
                            <li class="list-group-item text-muted text-center py-4">Belum ada data pada tahun {{ $year }}.</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        @endforeach
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const months = @json($chart);
        const fmt = (v) => 'Rp ' + Math.round(v).toLocaleString('id-ID');

        new Chart(document.getElementById('financeChart'), {
            type: 'bar',
            data: {
                labels: months.map(m => m.label),
                datasets: [
                    { label: 'Pemasukan', data: months.map(m => m.income), backgroundColor: 'rgba(25, 135, 84, 0.75)', borderRadius: 4 },
                    { label: 'Pengeluaran', data: months.map(m => m.expense), backgroundColor: 'rgba(220, 53, 69, 0.75)', borderRadius: 4 },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { tooltip: { callbacks: { label: (ctx) => ctx.dataset.label + ': ' + fmt(ctx.parsed.y) } } },
                scales: { y: { beginAtZero: true, ticks: { callback: (v) => v >= 1000000 ? (v / 1000000) + ' jt' : v.toLocaleString('id-ID') } } },
            },
        });
    });
</script>
@endpush
@endsection
