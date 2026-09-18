@extends('layouts.admin')

@section('title', 'Nilai — ' . $teachingAssignment->subject->name)

@section('content')

<link rel="stylesheet" href="{{ asset('css/guru/grades/index.css') }}">

<div class="grades-page">

    {{-- =====================================================
        HERO / HEADER
    ====================================================== --}}
    <section class="grades-header-card">

        <div class="grades-header-content">

            <div class="grades-header-info">

                <span class="grades-eyebrow">
                    PENILAIAN GURU
                </span>

                <h1>
                    {{ $teachingAssignment->subject->name }}
                </h1>

                <p>
                    Kelas {{ $teachingAssignment->classroom->name }}
                </p>

                <div class="grades-header-actions">

                    <a href="{{ route('guru.dashboard') }}"
                    class="grades-back-button">
                         ← Dashboard
                    </a>

                </div>

            </div>


            <div class="grades-semester-box">

                <span class="grades-semester-label">
                    SEMESTER
                </span>

                <form method="GET"
                      action="{{ route('guru.teaching-assignments.grades.index', $teachingAssignment) }}">

                    <select name="semester_id"
                            class="form-select form-select-sm"
                            onchange="this.form.submit()">

                        @foreach ($semesters as $s)

                            <option value="{{ $s->id }}"
                                @selected($semester?->id === $s->id)>

                                Semester {{ $s->name }}

                                @if ($s->is_active)
                                    (Aktif)
                                @endif

                            </option>

                        @endforeach

                    </select>

                </form>

            </div>

        </div>

    </section>


    {{-- =====================================================
        SEMESTER KOSONG
    ====================================================== --}}
    @if (! $semester)

        <div class="grades-alert grades-alert-warning">

            <span class="grades-alert-icon">
                !
            </span>

            <div>

                <strong>
                    Semester belum tersedia
                </strong>

                <p>
                    Belum ada semester untuk tahun ajaran ini.
                </p>

            </div>

        </div>

    @else


        {{-- =================================================
            BOBOT + TAMBAH NILAI
        ================================================== --}}
        <div class="grades-main-grid">


            {{-- =================================================
                BOBOT PENILAIAN
            ================================================== --}}
            <section class="grades-card grades-weight-card">

                <div class="grades-card-header">

                    <div>

                        <span class="grades-card-label">
                            PENGATURAN
                        </span>

                        <h2>
                            Bobot Penilaian
                        </h2>

                    </div>

                </div>


                <div class="grades-card-body">

                    <form method="POST"
                          action="{{ route('guru.teaching-assignments.grades.update-weight', $teachingAssignment) }}">

                        @csrf
                        @method('PUT')

                        <input type="hidden"
                               name="semester_id"
                               value="{{ $semester->id }}">


                        {{-- ERROR --}}
                        @if ($errors->any())

                            <div class="grades-alert grades-alert-danger">

                                <div>

                                    @foreach ($errors->all() as $error)

                                        <div>
                                            {{ $error }}
                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endif


                        {{-- WEIGHT GRID --}}
                        <div class="weight-grid">

                            <div class="weight-field">

                                <label class="form-label">
                                    Tugas (%)
                                </label>

                                <input type="number"
                                       name="tugas_weight"
                                       class="form-control weight-input"
                                       min="0"
                                       max="100"
                                       value="{{ old('tugas_weight', $weight?->tugas_weight ?? 20) }}"
                                       required>

                            </div>


                            <div class="weight-field">

                                <label class="form-label">
                                    Ulangan Harian (%)
                                </label>

                                <input type="number"
                                       name="uh_weight"
                                       class="form-control weight-input"
                                       min="0"
                                       max="100"
                                       value="{{ old('uh_weight', $weight?->uh_weight ?? 30) }}"
                                       required>

                            </div>


                            <div class="weight-field">

                                <label class="form-label">
                                    UTS/PTS (%)
                                </label>

                                <input type="number"
                                       name="uts_weight"
                                       class="form-control weight-input"
                                       min="0"
                                       max="100"
                                       value="{{ old('uts_weight', $weight?->uts_weight ?? 20) }}"
                                       required>

                            </div>


                            <div class="weight-field">

                                <label class="form-label">
                                    UAS/PAS (%)
                                </label>

                                <input type="number"
                                       name="uas_weight"
                                       class="form-control weight-input"
                                       min="0"
                                       max="100"
                                       value="{{ old('uas_weight', $weight?->uas_weight ?? 30) }}"
                                       required>

                            </div>

                        </div>


                        {{-- TOTAL --}}
                        <div class="weight-total-box">

                            <div>

                                <span>
                                    Total bobot saat ini
                                </span>

                                <strong>
                                    <span id="weight-total">
                                        100
                                    </span>%
                                </strong>

                            </div>

                            <small>
                                Total bobot harus 100%
                            </small>

                        </div>


                        <button type="submit"
                                class="btn btn-primary grades-save-button">

                            Simpan Bobot

                        </button>

                    </form>

                </div>

            </section>



            {{-- =================================================
                TAMBAH NILAI
            ================================================== --}}
            <section class="grades-card grades-add-card">

                <div class="grades-card-header">

                    <div>

                        <span class="grades-card-label">
                            INPUT NILAI
                        </span>

                        <h2>
                            Tambah Nilai
                        </h2>

                    </div>

                </div>


                <div class="grades-card-body">

                    <form method="POST"
                          action="{{ route('guru.teaching-assignments.grades.store-batch', $teachingAssignment) }}">

                        @csrf

                        <input type="hidden"
                               name="semester_id"
                               value="{{ $semester->id }}">


                        {{-- CATEGORY + LABEL --}}
                        <div class="grades-input-grid">

                            <div>

                                <label class="form-label">
                                    Kategori
                                </label>

                                <select name="category"
                                        class="form-select"
                                        required>

                                    <option value="tugas">
                                        Tugas
                                    </option>

                                    <option value="uh">
                                        Ulangan Harian
                                    </option>

                                    <option value="uts">
                                        UTS/PTS
                                    </option>

                                    <option value="uas">
                                        UAS/PAS
                                    </option>

                                </select>

                            </div>


                            <div>

                                <label class="form-label">
                                    Label
                                </label>

                                <input type="text"
                                       name="label"
                                       class="form-control"
                                       required
                                       placeholder="UH Bab 3">

                                <small class="grades-field-help">
                                    Contoh: Tugas Susulan atau UH Bab 3
                                </small>

                            </div>

                        </div>


                        {{-- INFO --}}
                        <div class="grades-info-box">

                            <div class="grades-info-icon">
                                i
                            </div>

                            <div>

                                Nilai tugas yang dikumpulkan &amp; dikoreksi lewat menu

                                <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}">
                                    Tugas
                                </a>

                                sudah otomatis masuk ke kategori Tugas.

                                Gunakan kategori <strong>Tugas</strong> di form ini hanya untuk kasus khusus:
                                tugas susulan dengan toleransi guru, atau tugas yang diberikan di luar sistem.

                            </div>

                        </div>


                        {{-- STUDENT TABLE --}}
                        <div class="grades-entry-table">

                            <div class="grades-table-title">

                                <div>

                                    <span>
                                        DAFTAR SISWA
                                    </span>

                                    <strong>
                                        Masukkan nilai siswa
                                    </strong>

                                </div>

                                <small>
                                    0–100
                                </small>

                            </div>


                            <div class="table-responsive grades-student-scroll">

                                <table class="table align-middle mb-0">

                                    <thead>

                                        <tr>

                                            <th>
                                                Nama Siswa
                                            </th>

                                            <th class="grades-score-heading">
                                                Nilai
                                            </th>

                                        </tr>

                                    </thead>


                                    <tbody>

                                        @forelse ($students as $student)

                                            <tr>

                                                <td>

                                                    <div class="student-name">
                                                        {{ $student->name }}
                                                    </div>

                                                </td>

                                                <td>

                                                    <input type="number"
                                                           name="scores[{{ $student->id }}]"
                                                           class="form-control form-control-sm"
                                                           min="0"
                                                           max="100"
                                                           step="0.01">

                                                </td>

                                            </tr>

                                        @empty

                                            <tr>

                                                <td colspan="2"
                                                    class="grades-empty-cell">

                                                    Belum ada siswa di kelas ini.

                                                </td>

                                            </tr>

                                        @endforelse

                                    </tbody>

                                </table>

                            </div>

                        </div>


                        {{-- ACTION --}}
                        <div class="grades-form-footer">

                            <button type="submit"
                                    class="btn btn-primary grades-save-button">

                                Simpan Nilai

                            </button>

                            <span>
                                Kosongkan kolom siswa yang belum dinilai.
                            </span>

                        </div>

                    </form>

                </div>

            </section>

        </div>



        {{-- =================================================
            REKAP NILAI
        ================================================== --}}
        <section class="grades-card grades-recap-card">

            <div class="grades-card-header grades-section-header">

                <div>

                    <span class="grades-card-label">
                        REKAPITULASI
                    </span>

                    <h2>
                        Rekap Nilai Siswa
                    </h2>

                </div>

                <span class="grades-header-note">
                    Semester {{ $semester->name }}
                </span>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0 grades-recap-table">

                    <thead>

                        <tr>

                            <th>
                                Nama Siswa
                            </th>

                            <th class="text-center">
                                Tugas
                            </th>

                            <th class="text-center">
                                UH
                            </th>

                            <th class="text-center">
                                UTS
                            </th>

                            <th class="text-center">
                                UAS
                            </th>

                            <th class="text-center">
                                Nilai Akhir
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($students as $student)

                            @php($r = $recaps[$student->id])

                            <tr>

                                <td>

                                    <div class="recap-student-name">
                                        {{ $student->name }}
                                    </div>

                                </td>

                                <td class="text-center">
                                    {{ $r['categoryAverages']['tugas'] ?? '-' }}
                                </td>

                                <td class="text-center">
                                    {{ $r['categoryAverages']['uh'] ?? '-' }}
                                </td>

                                <td class="text-center">
                                    {{ $r['categoryAverages']['uts'] ?? '-' }}
                                </td>

                                <td class="text-center">
                                    {{ $r['categoryAverages']['uas'] ?? '-' }}
                                </td>

                                <td class="text-center">

                                    @if ($r['final'] !== null)

                                        <div class="final-score">

                                            {{ $r['final'] }}

                                            @unless ($r['isComplete'])

                                                <span class="badge text-bg-secondary"
                                                      title="Belum semua kategori terisi">

                                                    Sementara

                                                </span>

                                            @endunless

                                        </div>

                                    @else

                                        <span class="score-empty">
                                            -
                                        </span>

                                    @endif

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="grades-empty-cell">

                                    Belum ada siswa.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>



        {{-- =================================================
            RIWAYAT ENTRI
        ================================================== --}}
        <section class="grades-card grades-history-card">

            <div class="grades-card-header grades-section-header">

                <div>

                    <span class="grades-card-label">
                        AKTIVITAS
                    </span>

                    <h2>
                        Riwayat Entri Nilai
                    </h2>

                </div>

                <span class="grades-header-note">
                    Data nilai
                </span>

            </div>


            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0 grades-history-table">

                    <thead>

                        <tr>

                            <th>
                                Label
                            </th>

                            <th>
                                Kategori
                            </th>

                            <th>
                                Siswa
                            </th>

                            <th class="text-center">
                                Nilai
                            </th>

                            <th class="text-end">
                                Aksi
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($grades->flatten() as $grade)

                            <tr>

                                <td>

                                    <strong class="history-label">
                                        {{ $grade->label }}
                                    </strong>

                                </td>

                                <td>

                                    <span class="category-badge">
                                        {{ $grade->categoryLabel() }}
                                    </span>

                                </td>

                                <td>
                                    {{ $students->firstWhere('id', $grade->student_id)?->name }}
                                </td>

                                <td class="text-center">

                                    <strong class="history-score">
                                        {{ $grade->score }}
                                    </strong>

                                </td>

                                <td class="text-end">

                                    <form method="POST"
                                          action="{{ route('guru.teaching-assignments.grades.destroy', [$teachingAssignment, $grade]) }}"
                                          onsubmit="return confirm('Hapus entri nilai ini?');">

                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="btn btn-sm btn-outline-danger grades-delete-button">

                                            Hapus

                                        </button>

                                    </form>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="5"
                                    class="grades-empty-cell">

                                    Belum ada entri nilai.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </section>

    @endif

</div>


@push('scripts')

<script>

    function recalcWeightTotal() {

        const inputs =
            document.querySelectorAll('.weight-input');

        let total = 0;

        inputs.forEach((i) => {

            total += Number(i.value || 0);

        });

        const label =
            document.getElementById('weight-total');

        if (!label) {
            return;
        }

        label.textContent = total;

        label.className =
            total === 100
                ? 'text-success fw-bold'
                : 'text-danger fw-bold';

    }


    document
        .querySelectorAll('.weight-input')
        .forEach((i) => {

            i.addEventListener(
                'input',
                recalcWeightTotal
            );

        });


    recalcWeightTotal();

</script>

@endpush

@endsection