@extends('layouts.app')

@section('title', 'Jadwal Pelajaran - ' . $student->name)

@section('content')
<div class="container py-4" style="max-width: 960px;">

    @php
        $parentChildren = auth()->user()->children()->get();
    @endphp

    @if ($parentChildren->count() > 1)
        <div class="d-flex align-items-center gap-2 mb-3 flex-wrap">
            <span class="text-secondary small fw-medium">Pilih Anak:</span>
            @foreach ($parentChildren as $c)
                <a href="{{ route('ortu.schedule.index', $c) }}" 
                   class="btn btn-sm rounded-pill px-3 {{ $c->id === $student->id ? 'btn-primary shadow-sm fw-semibold' : 'btn-outline-secondary' }}">
                    <i class="bi bi-person-circle me-1"></i> {{ $c->name }}
                </a>
            @endforeach
        </div>
    @endif

    <div class="card bg-dark text-white border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 mb-2">
                    <i class="bi bi-calendar3 me-1"></i> JADWAL PELAJARAN
                </span>
                <h3 class="fw-bold mb-1">Jadwal Pelajaran {{ $student->name }}</h3>
                <p class="text-secondary mb-0 small">
                    @if ($activeYear && $classroom)
                        Kelas <strong class="text-white">{{ $classroom->name }}</strong> &bull; Tahun Ajaran <strong class="text-white">{{ $activeYear->name }}</strong>
                    @elseif (!$classroom)
                        Siswa belum terdaftar di kelas manapun pada tahun ajaran aktif ini.
                    @else
                        Belum ada tahun ajaran aktif yang dikonfigurasi.
                    @endif
                </p>
            </div>
            <a href="{{ route('ortu.dashboard') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                <i class="bi bi-arrow-left me-1"></i> Kembali ke Dasbor
            </a>
        </div>
    </div>

    @php
        $today = [
            'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
        ][now()->dayOfWeek];
    @endphp

    @if ($schedules->isNotEmpty())
        @foreach (\App\Models\Schedule::DAY_ORDER as $day)
            @continue(($schedules[$day] ?? collect())->isEmpty())

            <div class="card border-0 shadow-sm rounded-3 mb-4 overflow-hidden {{ $day === $today ? 'border-start border-4 border-primary' : '' }}">
                <div class="card-header bg-light d-flex justify-content-between align-items-center py-3 px-4 border-bottom">
                    <h5 class="card-title fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                        <i class="bi bi-calendar-day text-primary"></i>
                        Hari {{ $day }}
                    </h5>
                    @if ($day === $today)
                        <span class="badge bg-primary rounded-pill px-3 py-1">
                            <i class="bi bi-clock-history me-1"></i> Hari Ini
                        </span>
                    @endif
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th class="text-uppercase text-secondary small fw-semibold px-4 py-3" style="width: 170px;">Jam Pelajaran</th>
                                <th class="text-uppercase text-secondary small fw-semibold py-3">Mata Pelajaran</th>
                                <th class="text-uppercase text-secondary small fw-semibold py-3">Guru Pengampu</th>
                                <th class="text-uppercase text-secondary small fw-semibold px-4 py-3">Ruangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($schedules[$day] as $schedule)
                                <tr>
                                    <td class="px-4 py-3">
                                        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-2 py-1 font-monospace">
                                            <i class="bi bi-clock me-1"></i>
                                            {{ $schedule->start_time->format('H:i') }} - {{ $schedule->end_time->format('H:i') }}
                                        </span>
                                    </td>
                                    <td class="py-3">
                                        <div class="fw-bold text-dark">
                                            {{ $schedule->teachingAssignment->subject->name }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="text-secondary small d-flex align-items-center gap-1">
                                            <i class="bi bi-person text-muted"></i>
                                            {{ $schedule->teachingAssignment->teacher->name }}
                                        </div>
                                    </td>
                                    <td class="px-4 py-3">
                                        @if ($schedule->room)
                                            <span class="badge bg-secondary-subtle text-secondary border px-2 py-1">
                                                <i class="bi bi-geo-alt-fill text-danger me-1"></i>
                                                {{ $schedule->room }}
                                            </span>
                                        @else
                                            <span class="text-muted small">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach
    @else
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
            <div class="display-5 mb-2">📅</div>
            <h5 class="fw-bold text-dark mb-1">Belum Ada Jadwal Pelajaran</h5>
            <p class="text-secondary mb-0 small">Jadwal pelajaran untuk kelas {{ $classroom->name ?? 'anak Anda' }} belum diatur oleh pihak sekolah.</p>
        </div>
    @endif

    <div class="text-center mt-4">
        <a href="{{ route('ortu.dashboard') }}" class="btn btn-secondary rounded-pill px-4">
            <i class="bi bi-arrow-left me-1"></i> Kembali ke Dasbor Utama
        </a>
    </div>

</div>
@endsection
