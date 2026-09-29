<ul class="nav nav-pills mb-4" id="ppdbSubTabs">
    <li class="nav-item">
        <button class="nav-link active small fw-semibold" id="subtab-ppdb-trend"
                data-bs-toggle="pill" data-bs-target="#subpane-ppdb-trend" type="button">
            <i class="bi bi-graph-up-arrow me-1"></i>Tren PPDB
            @if(!$ppdbTrend['isEmpty'])
            <span class="badge bg-warning text-dark ms-1">{{ $ppdbTrend['acceptanceRate'] }}% Diterima</span>
            @endif
        </button>
    </li>
    <li class="nav-item ms-2">
        <button class="nav-link small fw-semibold" id="subtab-alumni"
                data-bs-toggle="pill" data-bs-target="#subpane-alumni" type="button">
            <i class="bi bi-mortarboard-fill me-1"></i>Siswa & Alumni
            <span class="badge bg-success ms-1">{{ $studentAlumniStats['activeStudents'] }} Aktif</span>
        </button>
    </li>
</ul>

<div class="tab-content">

    <div class="tab-pane fade show active" id="subpane-ppdb-trend">
        @if ($ppdbTrend['isEmpty'])
            <div class="text-center py-5 text-muted border rounded-3">
                <i class="bi bi-clipboard-x fs-1 d-block mb-2"></i>
                <div class="fw-semibold">Belum ada data PPDB</div>
                <small>Data akan muncul setelah periode pendaftaran dibuat dan ada peserta mendaftar.</small>
            </div>
        @else
            <div class="row g-2 mb-3">
                @php $ppdbKpis = [
                    ['label'=>'Total Pendaftar','value'=>$ppdbTrend['totalThisYear'],    'color'=>'primary'],
                    ['label'=>'Diterima',       'value'=>$ppdbTrend['acceptedThisYear'], 'color'=>'success'],
                    ['label'=>'Ditolak',        'value'=>$ppdbTrend['rejectedThisYear'], 'color'=>'danger'],
                    ['label'=>'Menunggu',       'value'=>$ppdbTrend['pendingThisYear'],  'color'=>'warning'],
                ]; @endphp
                @foreach ($ppdbKpis as $k)
                <div class="col-6 col-md-3">
                    <div class="p-2 rounded-3 bg-{{ $k['color'] }}-subtle border border-{{ $k['color'] }}-subtle text-center">
                        <div class="fw-bold fs-5 text-{{ $k['color'] }}">{{ $k['value'] }}</div>
                        <small class="text-muted" style="font-size:.7rem;">{{ $k['label'] }}</small>
                    </div>
                </div>
                @endforeach
            </div>
            <div style="height:200px;"><canvas id="ppdbTrendChart"></canvas></div>
        @endif
    </div>

    <div class="tab-pane fade" id="subpane-alumni">
        <div class="row g-3 mb-3">
            <div class="col-6">
                <div class="p-3 rounded-3 bg-success-subtle border border-success-subtle text-center">
                    <i class="bi bi-people-fill text-success fs-3 d-block mb-1"></i>
                    <div class="fw-bold fs-4 text-success">{{ $studentAlumniStats['activeStudents'] }}</div>
                    <small class="text-muted">Siswa Aktif</small>
                </div>
            </div>
            <div class="col-6">
                <div class="p-3 rounded-3 bg-secondary-subtle border text-center">
                    <i class="bi bi-mortarboard-fill text-secondary fs-3 d-block mb-1"></i>
                    <div class="fw-bold fs-4 text-secondary">{{ $studentAlumniStats['estimatedAlumni'] }}</div>
                    <small class="text-muted">Est. Alumni</small>
                </div>
            </div>
        </div>
        @if ($studentAlumniStats['isEmpty'])
            <div class="text-center py-4 text-muted border rounded-3">
                <i class="bi bi-bar-chart fs-2 d-block mb-2"></i>
                <div class="fw-semibold">Belum ada data per tahun ajaran</div>
                <small>Data akan muncul setelah siswa terdaftar di kelas.</small>
            </div>
        @else
            <div style="height:200px;"><canvas id="studentTrendChart"></canvas></div>
        @endif
    </div>

</div>
