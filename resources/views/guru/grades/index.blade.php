@extends('layouts.app')

@section('title', 'Nilai — ' . $teachingAssignment->subject->name)

@section('content')
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4 d-flex justify-content-between align-items-start flex-wrap gap-3">
        <div>
            <h4 class="fw-bold mb-1">{{ $teachingAssignment->subject->name }}</h4>
            <p class="text-muted mb-0">Kelas {{ $teachingAssignment->classroom->name }}</p>
        </div>

        <form method="GET" action="{{ route('guru.teaching-assignments.grades.index', $teachingAssignment) }}">
            <select name="semester_id" class="form-select form-select-sm" onchange="this.form.submit()">
                @foreach ($semesters as $s)
                    <option value="{{ $s->id }}" @selected($semester?->id === $s->id)>
                        Semester {{ $s->name }} @if ($s->is_active) (Aktif) @endif
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

@if (! $semester)
    <div class="alert alert-warning">Belum ada semester untuk tahun ajaran ini.</div>
@else

    <div class="row g-3 mb-4">
        {{-- BOBOT PENILAIAN --}}
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Bobot Penilaian</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('guru.teaching-assignments.grades.update-weight', $teachingAssignment) }}">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="semester_id" value="{{ $semester->id }}">

                        @if ($errors->any())
                            <div class="alert alert-danger py-2">
                                @foreach ($errors->all() as $error)
                                    <div class="small">{{ $error }}</div>
                                @endforeach
                            </div>
                        @endif

                        <div class="row g-2 mb-2">
                            <div class="col-6">
                                <label class="form-label small">Tugas (%)</label>
                                <input type="number" name="tugas_weight" class="form-control weight-input" min="0" max="100"
                                       value="{{ old('tugas_weight', $weight?->tugas_weight ?? 20) }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">Ulangan Harian (%)</label>
                                <input type="number" name="uh_weight" class="form-control weight-input" min="0" max="100"
                                       value="{{ old('uh_weight', $weight?->uh_weight ?? 30) }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">UTS/PTS (%)</label>
                                <input type="number" name="uts_weight" class="form-control weight-input" min="0" max="100"
                                       value="{{ old('uts_weight', $weight?->uts_weight ?? 20) }}" required>
                            </div>
                            <div class="col-6">
                                <label class="form-label small">UAS/PAS (%)</label>
                                <input type="number" name="uas_weight" class="form-control weight-input" min="0" max="100"
                                       value="{{ old('uas_weight', $weight?->uas_weight ?? 30) }}" required>
                            </div>
                        </div>

                        <div class="small text-muted mb-2">
                            Total saat ini: <span id="weight-total">100</span>% (harus 100%)
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary">Simpan Bobot</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- TAMBAH NILAI --}}
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-white fw-semibold">Tambah Nilai</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('guru.teaching-assignments.grades.store-batch', $teachingAssignment) }}">
                        @csrf
                        <input type="hidden" name="semester_id" value="{{ $semester->id }}">

                        <div class="row g-2 mb-3">
                            <div class="col-md-5">
                                <label class="form-label small">Kategori</label>
                                <select name="category" class="form-select" required>
                                    <option value="uh">Ulangan Harian</option>
                                    <option value="uts">UTS/PTS</option>
                                    <option value="uas">UAS/PAS</option>
                                </select>
                            </div>
                            <div class="col-md-7">
                                <label class="form-label small">Label (mis. "UH Bab 3", "UAS Semester Ganjil")</label>
                                <input type="text" name="label" class="form-control" required placeholder="UH Bab 3">
                            </div>
                        </div>

                        <div class="alert alert-info py-2 small mb-3">
                            Nilai <strong>Tugas</strong> tidak diinput di sini — otomatis diambil dari nilai
                            tugas yang sudah Anda koreksi di menu <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}">Tugas</a>.
                        </div>

                        <div class="table-responsive" style="max-height: 260px;">
                            <table class="table table-sm align-middle mb-0">
                                <thead class="table-light">
                                    <tr><th>Nama Siswa</th><th style="width: 110px;">Nilai</th></tr>
                                </thead>
                                <tbody>
                                    @forelse ($students as $student)
                                        <tr>
                                            <td>{{ $student->name }}</td>
                                            <td>
                                                <input type="number" name="scores[{{ $student->id }}]"
                                                       class="form-control form-control-sm" min="0" max="100" step="0.01">
                                            </td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="2" class="text-center text-muted py-3">Belum ada siswa di kelas ini.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <button type="submit" class="btn btn-primary btn-sm mt-3">Simpan Nilai</button>
                        <span class="text-muted small ms-2">Kosongkan kolom siswa yang belum dinilai.</span>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- REKAP NILAI --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-semibold">Rekap Nilai Siswa</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Nama Siswa</th>
                        <th class="text-center">Tugas <span class="text-muted small">(otomatis)</span></th>
                        <th class="text-center">UH</th>
                        <th class="text-center">UTS</th>
                        <th class="text-center">UAS</th>
                        <th class="text-center">Nilai Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($students as $student)
                        @php($r = $recaps[$student->id])
                        <tr>
                            <td>{{ $student->name }}</td>
                            <td class="text-center">{{ $r['categoryAverages']['tugas'] ?? '-' }}</td>
                            <td class="text-center">{{ $r['categoryAverages']['uh'] ?? '-' }}</td>
                            <td class="text-center">{{ $r['categoryAverages']['uts'] ?? '-' }}</td>
                            <td class="text-center">{{ $r['categoryAverages']['uas'] ?? '-' }}</td>
                            <td class="text-center fw-bold">
                                @if ($r['final'] !== null)
                                    {{ $r['final'] }}
                                    @unless ($r['isComplete'])
                                        <span class="badge text-bg-secondary ms-1" title="Belum semua kategori terisi">Sementara</span>
                                    @endunless
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- RIWAYAT ENTRI --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">Riwayat Entri Nilai</div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Label</th>
                        <th>Kategori</th>
                        <th>Siswa</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($grades->flatten() as $grade)
                        <tr>
                            <td>{{ $grade->label }}</td>
                            <td>{{ $grade->categoryLabel() }}</td>
                            <td>{{ $students->firstWhere('id', $grade->student_id)?->name }}</td>
                            <td class="text-center">{{ $grade->score }}</td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('guru.teaching-assignments.grades.destroy', [$teachingAssignment, $grade]) }}"
                                      onsubmit="return confirm('Hapus entri nilai ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada entri nilai.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endif

@push('scripts')
<script>
    function recalcWeightTotal() {
        const inputs = document.querySelectorAll('.weight-input');
        let total = 0;
        inputs.forEach((i) => total += Number(i.value || 0));
        const label = document.getElementById('weight-total');
        label.textContent = total;
        label.className = total === 100 ? 'text-success fw-bold' : 'text-danger fw-bold';
    }
    document.querySelectorAll('.weight-input').forEach((i) => i.addEventListener('input', recalcWeightTotal));
    recalcWeightTotal();
</script>
@endpush
@endsection
