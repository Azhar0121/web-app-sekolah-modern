@extends('layouts.app')

@section('title', 'Nilai Rapor')

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">Nilai Rapor</h4>
            <p class="text-muted mb-0">
                @if ($classroom)
                    Kelas <strong>{{ $classroom->name }}</strong>
                @else
                    Anda belum terdaftar di kelas manapun.
                @endif
            </p>
        </div>

        @if ($semesters->isNotEmpty())
            <form method="GET" action="{{ route('siswa.grades.index') }}">
                <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                    @foreach ($semesters as $s)
                        <option value="{{ $s->id }}" @selected($semester?->id === $s->id)>
                            Semester {{ $s->name }} @if ($s->is_active) (Aktif) @endif
                        </option>
                    @endforeach
                </select>
            </form>
        @endif
    </div>
</div>

@if ($recaps->isEmpty())
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            Belum ada data mata pelajaran/nilai untuk ditampilkan.
        </div>
    </div>
@else

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Grafik Nilai Akhir per Mata Pelajaran</div>
        <div class="card-body">
            <canvas id="gradeChart" height="90"></canvas>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th class="text-center">Tugas</th>
                        <th class="text-center">UH</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($recaps as $r)
                        <tr>
                            <td class="fw-semibold">{{ $r['subject']->name }}</td>
                            <td class="text-center">{{ $r['calc']['categoryAverages']['tugas'] ?? '-' }}</td>
                            <td class="text-center">{{ $r['calc']['categoryAverages']['uh'] ?? '-' }}</td>
                            <td class="text-center">{{ $r['calc']['categoryAverages']['uts'] ?? '-' }}</td>
                            <td class="text-center">{{ $r['calc']['categoryAverages']['uas'] ?? '-' }}</td>
                            <td class="text-center fw-bold">
                                @if ($r['calc']['final'] !== null)
                                    {{ $r['calc']['final'] }}
                                    @unless ($r['calc']['isComplete'])
                                        <span class="badge text-bg-secondary ms-1">Sementara</span>
                                    @endunless
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.4/chart.umd.min.js"></script>
    <script>
        const labels = @json($recaps->pluck('subject.name'));
        const scores = @json($recaps->map(fn ($r) => $r['calc']['final']));

        new Chart(document.getElementById('gradeChart'), {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Nilai Akhir',
                    data: scores,
                    backgroundColor: '#1769d5',
                    borderRadius: 6,
                }],
            },
            options: {
                scales: { y: { beginAtZero: true, max: 100 } },
                plugins: { legend: { display: false } },
            },
        });
    </script>
    @endpush

@endif
@endsection
