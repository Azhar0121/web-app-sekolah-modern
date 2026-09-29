@if ($financialSummary['isEmpty'])
    <div class="text-center py-5 text-muted">
        <i class="bi bi-receipt fs-1 d-block mb-3"></i>
        <h6 class="fw-bold">Belum Ada Data Finansial</h6>
        <p class="small mb-0">Data keuangan akan muncul setelah TU menginput tagihan siswa.</p>
    </div>
@else
    <div class="row g-3 mb-4">
        @php $finKpis = [
            ['label'=>'Total Tagihan',  'value'=>'Rp '.number_format($financialSummary['totalBilling'],0,',','.'),  'color'=>'primary'],
            ['label'=>'Total Lunas',    'value'=>'Rp '.number_format($financialSummary['totalPaid'],0,',','.'),     'color'=>'success'],
            ['label'=>'Belum Bayar',    'value'=>'Rp '.number_format($financialSummary['totalUnpaid'],0,',','.'),   'color'=>'warning'],
            ['label'=>'Jatuh Tempo',    'value'=>'Rp '.number_format($financialSummary['totalOverdue'],0,',','.'),  'color'=>'danger'],
        ]; @endphp
        @foreach ($finKpis as $k)
        <div class="col-6 col-lg-3">
            <div class="p-3 rounded-3 bg-{{ $k['color'] }}-subtle border border-{{ $k['color'] }}-subtle">
                <small class="text-muted d-block mb-1">{{ $k['label'] }}</small>
                <div class="fw-bold text-{{ $k['color'] }}" style="font-size:.95rem;">{{ $k['value'] }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="row g-4">
        <div class="col-lg-8">
            <h6 class="fw-semibold small text-muted text-uppercase mb-2">Pemasukan Bulanan</h6>
            <div style="height:220px;"><canvas id="financialMonthlyChart"></canvas></div>
        </div>
        <div class="col-lg-4 text-center">
            <h6 class="fw-semibold small text-muted text-uppercase mb-2">Status Tagihan</h6>
            <div style="height:180px;"><canvas id="financialStatusChart"></canvas></div>
            <div class="mt-2 d-flex flex-wrap justify-content-center gap-2 small text-muted">
                <span><i class="bi bi-circle-fill text-success me-1"></i>Lunas ({{ $financialSummary['countPaid'] }})</span>
                <span><i class="bi bi-circle-fill text-warning me-1"></i>Belum ({{ $financialSummary['countUnpaid'] }})</span>
                <span><i class="bi bi-circle-fill text-danger me-1"></i>Jatuh ({{ $financialSummary['countOverdue'] }})</span>
            </div>
        </div>
    </div>
@endif
