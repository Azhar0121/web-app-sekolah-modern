@php
    $hasGradeData = $subjectAverages->isNotEmpty() || $classroomAverages->isNotEmpty() || array_sum($gradeDistribution) > 0;
@endphp

@if (! $hasGradeData)
    <div class="text-center py-5 text-muted border rounded-3">
        <i class="bi bi-journal-x fs-1 d-block mb-2 text-secondary"></i>
        <div class="fw-semibold text-dark">Belum Ada Data Nilai Akademik</div>
        <small>Data nilai akan muncul setelah guru menginput nilai tugas dan ujian siswa pada sistem.</small>
    </div>
@else
    <div class="row g-4">
        <div class="col-lg-8">
            <h6 class="fw-semibold small text-muted text-uppercase mb-2">Rata-Rata Nilai per Mata Pelajaran</h6>
            <div style="height:240px;"><canvas id="subjectChart"></canvas></div>
        </div>
        <div class="col-lg-4">
            <h6 class="fw-semibold small text-muted text-uppercase mb-2">Distribusi Predikat Nilai</h6>
            <div style="height:180px;"><canvas id="gradeDistChart"></canvas></div>
            <div class="mt-2 d-flex flex-wrap gap-2 justify-content-center small text-muted">
                <span><i class="bi bi-circle-fill text-success me-1"></i>A (≥85)</span>
                <span><i class="bi bi-circle-fill text-primary me-1"></i>B (75-84)</span>
                <span><i class="bi bi-circle-fill text-warning me-1"></i>C (65-74)</span>
                <span><i class="bi bi-circle-fill text-danger me-1"></i>D (&lt;65)</span>
            </div>
        </div>
        <div class="col-12">
            <h6 class="fw-semibold small text-muted text-uppercase mb-2">Perbandingan Nilai per Kelas</h6>
            <div style="height:200px;"><canvas id="classAvgChart"></canvas></div>
        </div>
    </div>
@endif
