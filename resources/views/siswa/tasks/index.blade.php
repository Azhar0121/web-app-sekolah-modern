@extends('layouts.app')

@section('title', 'Tugas')

@section('content')
<h4 class="fw-bold mb-4">Tugas</h4>

@if (! $classroom)
    <div class="alert alert-warning">
        Anda belum terdaftar di kelas manapun pada tahun ajaran ini. Hubungi Tata Usaha / Wali Kelas.
    </div>
@else
    <div class="card border-0 shadow-sm">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Mata Pelajaran</th>
                        <th>Judul Tugas</th>
                        <th>Batas Waktu</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Nilai</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tasks as $task)
                        <tr>
                            <td>{{ $task->teachingAssignment->subject->name }}</td>
                            <td class="fw-semibold">{{ $task->title }}</td>
                            <td class="small">
                                {{ $task->deadline->format('d M Y, H:i') }}
                                @if (! $task->mySubmission && $task->isPastDeadline())
                                    <span class="badge text-bg-danger ms-1">Lewat</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if (! $task->mySubmission)
                                    <span class="badge text-bg-light text-muted">Belum Dikumpulkan</span>
                                @elseif ($task->mySubmission->isLate())
                                    <span class="badge text-bg-warning">Terlambat</span>
                                @else
                                    <span class="badge text-bg-success">Sudah Dikumpulkan</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $task->mySubmission?->grade ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('siswa.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary">Lihat</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Belum ada tugas untuk kelas Anda.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endif
@endsection
