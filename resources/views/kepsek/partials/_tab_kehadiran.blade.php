<div class="row g-4 align-items-center">
    <div class="col-md-4 text-center">
        <div class="mx-auto position-relative" style="height:200px;">
            <canvas id="todayAttendanceChart"></canvas>
        </div>
        <div class="mt-2 small text-muted">
            Total: <strong class="text-dark">{{ $todayTotal }}</strong> rekaman
        </div>
    </div>

    <div class="col-md-8">
        <div class="row g-3">
            @php
            $attCards = [
                ['label'=>'Hadir', 'value'=>$todayAttendanceDistribution['hadir'], 'color'=>'success', 'icon'=>'bi-check-circle-fill'],
                ['label'=>'Izin',  'value'=>$todayAttendanceDistribution['izin'],  'color'=>'warning', 'icon'=>'bi-clock-history'],
                ['label'=>'Sakit', 'value'=>$todayAttendanceDistribution['sakit'], 'color'=>'info',    'icon'=>'bi-hospital-fill'],
                ['label'=>'Alpha', 'value'=>$todayAttendanceDistribution['alpha'], 'color'=>'danger',  'icon'=>'bi-x-circle-fill'],
            ];
            @endphp
            @foreach ($attCards as $c)
            <div class="col-6">
                <div class="p-3 rounded-3 bg-{{ $c['color'] }}-subtle border border-{{ $c['color'] }}-subtle">
                    <div class="d-flex align-items-center gap-2 mb-1">
                        <i class="bi {{ $c['icon'] }} text-{{ $c['color'] }} small"></i>
                        <span class="text-{{ $c['color'] }} fw-semibold small">{{ strtoupper($c['label']) }}</span>
                    </div>
                    <h4 class="fw-bold text-{{ $c['color'] }} mb-0">{{ $c['value'] }}</h4>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
