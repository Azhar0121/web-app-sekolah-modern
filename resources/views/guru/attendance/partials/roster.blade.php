<div class="card border-0 shadow-sm h-100">
    <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
        <span>Daftar Siswa</span>
        <span class="text-muted small">{{ $students->count() }} siswa</span>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Siswa</th>
                    <th style="width: 130px;">Status</th>
                    @if ($attendanceSession->isOpen())
                        <th style="width: 170px;">Tandai Manual</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    @php($attendance = $attendances->get($student->id))
                    <tr data-student-name="{{ $student->name }}">
                        <td>{{ $student->name }}</td>
                        <td class="status-cell">
                            @if ($attendance)
                                <span class="badge {{ $attendance->statusBadgeClass() }}">{{ $attendance->statusLabel() }}</span>
                                @if ($attendance->scanned_at)
                                    <div class="text-muted small">{{ $attendance->scanned_at->format('H:i') }}</div>
                                @endif
                            @else
                                <span class="badge text-bg-light text-dark border">Belum Absen</span>
                            @endif
                        </td>
                        @if ($attendanceSession->isOpen())
                            <td>
                                <form method="POST"
                                      action="{{ route('guru.attendance.update-status', [$attendanceSession, $student]) }}">
                                    @csrf
                                    @method('PUT')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="">-- Pilih --</option>
                                        <option value="hadir" @selected($attendance?->status === 'hadir')>Hadir</option>
                                        <option value="izin" @selected($attendance?->status === 'izin')>Izin</option>
                                        <option value="sakit" @selected($attendance?->status === 'sakit')>Sakit</option>
                                        <option value="alpha" @selected($attendance?->status === 'alpha')>Alpha</option>
                                    </select>
                                </form>
                            </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center text-muted py-4">Belum ada siswa terdaftar di kelas ini.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
