@if ($errors->any())
    <div class="alert alert-danger py-2">
        @foreach ($errors->all() as $error)
            <div class="small">{{ $error }}</div>
        @endforeach
    </div>
@endif

@if (isset($existingClassrooms) && $classroom?->exists)
    <div class="mb-3">
        <label for="name_select" class="form-label">Nama Kelas</label>
        <select id="name_select" class="form-select mb-2" onchange="toggleCustomClassName(this.value)">
            <option value="">-- Pilih dari Kelas yang Sudah Ada --</option>
            @foreach ($existingClassrooms as $existing)
                <option value="{{ $existing->name }}" @selected(old('name', $classroom->name) === $existing->name)>
                    {{ $existing->name }} (Tingkat {{ $existing->grade_level }} {{ $existing->major ? '- ' . $existing->major : '' }})
                </option>
            @endforeach
            <option value="__CUSTOM__" @selected(old('name') && !$existingClassrooms->pluck('name')->contains(old('name')))>
                + Input / Ketik Nama Kelas Baru...
            </option>
        </select>

        <input type="text" name="name" id="name" class="form-control {{ old('name') && !$existingClassrooms->pluck('name')->contains(old('name')) ? '' : 'd-none' }}"
               placeholder="Ketik nama kelas kustom"
               value="{{ old('name', $classroom->name) }}" required>
    </div>

    <script>
        function toggleCustomClassName(val) {
            const input = document.getElementById('name');
            if (val === '__CUSTOM__') {
                input.classList.remove('d-none');
                input.value = '';
                input.focus();
            } else if (val) {
                input.classList.add('d-none');
                input.value = val;
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const select = document.getElementById('name_select');
            const input = document.getElementById('name');
            if (select.value && select.value !== '__CUSTOM__') {
                input.value = select.value;
            }
        });
    </script>
@else
    <div class="mb-3">
        <label for="name" class="form-label">Nama Kelas</label>
        <input type="text" name="name" id="name" class="form-control" placeholder="Contoh: X IPA 1"
               value="{{ old('name') }}" required autofocus autocomplete="off">
    </div>
@endif

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="grade_level" class="form-label">Tingkat</label>
        <select name="grade_level" id="grade_level" class="form-select" required>
            <option value="">-- Pilih Tingkat --</option>
            @foreach (['X', 'XI', 'XII'] as $level)
                <option value="{{ $level }}" @selected(old('grade_level', $classroom?->grade_level) === $level)>{{ $level }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6 mb-3">
        <label for="major" class="form-label">Jurusan / Peminatan (opsional)</label>
        <input type="text" name="major" id="major" class="form-control" placeholder="Contoh: IPA, IPS, Bahasa"
               value="{{ old('major', $classroom?->major) }}">
    </div>
</div>

<div class="mb-3">
    <label for="homeroom_teacher_id" class="form-label">Wali Kelas (opsional)</label>
    <select name="homeroom_teacher_id" id="homeroom_teacher_id" class="form-select">
        <option value="">-- Belum Ditentukan --</option>
        @foreach ($teachers as $teacher)
            <option value="{{ $teacher->id }}" @selected(old('homeroom_teacher_id', $classroom?->homeroom_teacher_id) == $teacher->id)>
                {{ $teacher->name }}
            </option>
        @endforeach
    </select>
    @if ($teachers->isEmpty())
        <div class="form-text text-warning">Belum ada user dengan role Guru. Tambahkan dulu di menu Kelola User.</div>
    @endif
</div>

<div class="mb-3">
    <label for="capacity" class="form-label">Kapasitas (opsional)</label>
    <input type="number" name="capacity" id="capacity" class="form-control" min="1" max="100"
           value="{{ old('capacity', $classroom?->capacity) }}">
</div>

<div class="form-check">
    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
           @checked(old('is_active', $classroom?->is_active ?? true))>
    <label for="is_active" class="form-check-label">Kelas aktif</label>
</div>
