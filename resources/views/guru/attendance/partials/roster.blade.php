<div class="session-roster-wrapper">
    <table class="session-roster-table">
        <thead>
            <tr>
                <th class="roster-col-student">Nama Siswa</th>
                <th class="roster-col-status">Status</th>
                <th class="roster-col-action">Edit Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($students as $student)
                @php($attendance = $attendances->get($student->id))
                <tr data-student-name="{{ $student->name }}">
                    <td class="roster-cell-student">
                        <div class="session-student-info">
                            <div class="session-student-avatar">
                                {{ strtoupper(mb_substr($student->name, 0, 1)) }}
                            </div>
                            <div class="session-student-detail">
                                <span class="session-student-name">{{ $student->name }}</span>
                                <a href="{{ route('guru.student-profile.show', $student) }}"
                                   class="session-student-profile-link"
                                   title="Lihat biodata {{ $student->name }}">
                                    <i class="bi bi-person-vcard"></i>
                                    <span>Biodata</span>
                                </a>
                            </div>
                        </div>
                    </td>
                    <td class="roster-cell-status">
                        <div class="session-status-badge-wrap">
                            @if ($attendance)
                                <span class="session-badge session-badge-{{ $attendance->status }}">
                                    @if ($attendance->status === 'hadir')
                                        <i class="bi bi-check-circle-fill"></i>
                                    @elseif ($attendance->status === 'izin')
                                        <i class="bi bi-info-circle-fill"></i>
                                    @elseif ($attendance->status === 'sakit')
                                        <i class="bi bi-heart-pulse-fill"></i>
                                    @elseif ($attendance->status === 'alpha')
                                        <i class="bi bi-x-circle-fill"></i>
                                    @endif
                                    <span>{{ $attendance->statusLabel() }}</span>
                                </span>
                                @if ($attendance->scanned_at)
                                    <div class="session-scanned-time" title="Waktu Scan">
                                        <i class="bi bi-clock"></i>
                                        <span>{{ $attendance->scanned_at->format('H:i') }}</span>
                                    </div>
                                @endif
                            @else
                                <span class="session-badge session-badge-unrecorded">
                                    <i class="bi bi-dash-circle"></i>
                                    <span>Belum Absen</span>
                                </span>
                            @endif
                        </div>
                    </td>
                    <td class="roster-cell-action">
                        <form method="POST"
                              action="{{ route('guru.attendance.update-status', [$attendanceSession, $student]) }}"
                              class="session-status-form">
                            @csrf
                            @method('PUT')
                            <span class="session-select-label-mobile">Edit Status:</span>
                            <div class="session-select-wrapper">
                                <select name="status"
                                        class="session-status-select"
                                        onchange="this.form.submit()"
                                        aria-label="Ubah status kehadiran {{ $student->name }}">
                                    <option value="">-- Pilih --</option>
                                    <option value="hadir" @selected($attendance?->status === 'hadir')>Hadir</option>
                                    <option value="izin" @selected($attendance?->status === 'izin')>Izin</option>
                                    <option value="sakit" @selected($attendance?->status === 'sakit')>Sakit</option>
                                    <option value="alpha" @selected($attendance?->status === 'alpha')>Alpha</option>
                                </select>
                                <i class="bi bi-chevron-down session-select-arrow"></i>
                            </div>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="roster-cell-empty">
                        <div class="session-roster-empty">
                            <i class="bi bi-people"></i>
                            <p>Belum ada siswa terdaftar di kelas ini.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
