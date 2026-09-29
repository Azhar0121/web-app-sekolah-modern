<div class="mb-4">
    <h6 class="fw-semibold small text-muted text-uppercase mb-2">Keaktifan Modul & Tugas Guru</h6>
    <div style="height:220px;"><canvas id="teacherPerfChart"></canvas></div>
</div>

<div class="table-responsive">
    <table class="table table-hover align-middle table-sm mb-0">
        <thead class="table-light">
            <tr>
                <th class="small text-muted text-uppercase fw-semibold">Nama Guru</th>
                <th class="small text-muted text-uppercase fw-semibold text-center">Modul</th>
                <th class="small text-muted text-uppercase fw-semibold text-center">Tugas</th>
                <th class="small text-muted text-uppercase fw-semibold text-center" style="width:170px;">Dinilai (%)</th>
                <th class="small text-muted text-uppercase fw-semibold text-end">Avg Nilai</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($teacherPerformance as $tp)
            <tr>
                <td class="fw-semibold small">{{ $tp['name'] }}</td>
                <td class="text-center"><span class="badge bg-primary-subtle text-primary border border-primary-subtle">{{ $tp['material_count'] }}</span></td>
                <td class="text-center"><span class="badge bg-info-subtle text-info border border-info-subtle">{{ $tp['task_count'] }}</span></td>
                <td class="text-center">
                    <div class="d-flex align-items-center gap-2">
                        <div class="progress flex-grow-1" style="height:5px;">
                            <div class="progress-bar {{ $tp['grading_rate'] >= 80 ? 'bg-success' : ($tp['grading_rate'] >= 50 ? 'bg-warning' : 'bg-danger') }}"
                                 style="width:{{ $tp['grading_rate'] }}%"></div>
                        </div>
                        <small class="fw-semibold text-muted">{{ $tp['grading_rate'] }}%</small>
                    </div>
                </td>
                <td class="text-end fw-bold">{{ number_format($tp['avg_student_score'], 1) }}</td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">Belum ada data guru pengampu.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
