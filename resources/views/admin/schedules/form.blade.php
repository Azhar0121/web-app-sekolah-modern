@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

<div data-reload-base="{{ $reloadBaseUrl }}"></div>


<div class="row g-3 mb-3">
    <div class="col-md-6">
        <label for="filter_academic_year_id" class="form-label">Tahun Ajaran</label>
        <select id="filter_academic_year_id" class="form-select" onchange="reloadWithFilter()">
            @foreach ($academicYears as $year)
                <option value="{{ $year->id }}" @selected($selectedYearId == $year->id)>
                    {{ $year->name }} @if ($year->is_active) (Aktif) @endif
                </option>
            @endforeach
        </select>
        <div class="form-text">Ganti tahun ajaran akan memuat ulang pilihan kelas & penugasan di bawah.</div>
    </div>

    <div class="col-md-6">
        <label for="filter_classroom_id" class="form-label">Kelas</label>
        <select id="filter_classroom_id" class="form-select" onchange="reloadWithFilter()">
            <option value="">-- Pilih Kelas --</option>
            @foreach ($classrooms as $classroom)
                <option value="{{ $classroom->id }}" @selected($selectedClassroomId == $classroom->id)>
                    {{ $classroom->name }}
                </option>
            @endforeach
        </select>
    </div>
</div>

<div class="mb-3">
    <label for="teaching_assignment_id" class="form-label">Mata Pelajaran (Penugasan Mengajar)</label>
    <select name="teaching_assignment_id" id="teaching_assignment_id" class="form-select" required>
        <option value="">-- Pilih Mata Pelajaran --</option>
        @foreach ($teachingAssignments as $assignment)
            <option value="{{ $assignment->id }}"
                    @selected(old('teaching_assignment_id', $schedule?->teaching_assignment_id) == $assignment->id)>
                {{ $assignment->subject->code }} - {{ $assignment->subject->name }} ({{ $assignment->teacher->name }})
            </option>
        @endforeach
    </select>
    @if ($teachingAssignments->isEmpty())
        <div class="form-text text-warning">
            Kelas ini belum punya penugasan mengajar. Tambahkan dulu di menu Penugasan Mengajar.
        </div>
    @endif
</div>

<div class="row g-3 mb-3">
    <div class="col-md-4">
        <label for="day_of_week" class="form-label">Hari</label>
        <select name="day_of_week" id="day_of_week" class="form-select" required>
            <option value="">-- Pilih Hari --</option>
            @foreach (\App\Models\Schedule::DAY_ORDER as $day)
                <option value="{{ $day }}" @selected(old('day_of_week', $schedule?->day_of_week) === $day)>
                    {{ $day }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4">
        <label for="start_time" class="form-label">Jam Mulai</label>
        <input type="time" name="start_time" id="start_time" class="form-control"
               value="{{ old('start_time', $schedule?->start_time?->format('H:i')) }}" required>
    </div>

    <div class="col-md-4">
        <label for="end_time" class="form-label">Jam Selesai</label>
        <input type="time" name="end_time" id="end_time" class="form-control"
               value="{{ old('end_time', $schedule?->end_time?->format('H:i')) }}" required>
    </div>
</div>

<div class="mb-3">
    <label for="room" class="form-label">Ruangan <span class="text-muted">(opsional)</span></label>
    <input type="text" name="room" id="room" class="form-control"
           value="{{ old('room', $schedule?->room) }}" placeholder="mis. Lab Komputer, Ruang X MIPA 1">
</div>

<script>
    function reloadWithFilter() {
        const base = document.querySelector('[data-reload-base]').dataset.reloadBase;
        const yearId = document.getElementById('filter_academic_year_id').value;
        const classroomId = document.getElementById('filter_classroom_id').value;

        const url = new URL(base, window.location.origin);
        url.searchParams.set('academic_year_id', yearId);
        if (classroomId) {
            url.searchParams.set('classroom_id', classroomId);
        }
        window.location.href = url.toString();
    }
</script>
