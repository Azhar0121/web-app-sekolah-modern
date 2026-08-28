@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div class="mb-3">
    <label for="academic_year_id" class="form-label">Tahun Ajaran</label>
    <select name="academic_year_id" id="academic_year_id" class="form-select" required>
        <option value="">-- Pilih Tahun Ajaran --</option>
        @foreach ($academicYears as $year)
            <option value="{{ $year->id }}" @selected(old('academic_year_id', $assignment?->academic_year_id) == $year->id)>
                {{ $year->name }} @if ($year->is_active) (Aktif) @endif
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="classroom_id" class="form-label">Kelas</label>
    <select name="classroom_id" id="classroom_id" class="form-select" required>
        <option value="">-- Pilih Kelas --</option>
        @foreach ($classrooms as $classroom)
            <option value="{{ $classroom->id }}" @selected(old('classroom_id', $assignment?->classroom_id) == $classroom->id)>
                {{ $classroom->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="subject_id" class="form-label">Mata Pelajaran</label>
    <select name="subject_id" id="subject_id" class="form-select" required>
        <option value="">-- Pilih Mata Pelajaran --</option>
        @foreach ($subjects as $subject)
            <option value="{{ $subject->id }}" @selected(old('subject_id', $assignment?->subject_id) == $subject->id)>
                {{ $subject->code }} - {{ $subject->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="teacher_id" class="form-label">Guru Pengampu</label>
    <select name="teacher_id" id="teacher_id" class="form-select" required>
        <option value="">-- Pilih Guru --</option>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}" @selected(old('teacher_id', $assignment?->teacher_id) == $teacher->id)>
                {{ $teacher->name }}
            </option>
        @endforeach
    </select>
    @if ($teachers->isEmpty())
        <div class="form-text text-warning">Belum ada user dengan role Guru.</div>
    @endif
</div>
