@extends('layouts.app')

@section('title', 'Tugas - ' . $teachingAssignment->subject->name)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-1">Tugas</h4>
        <p class="text-muted mb-0">
            {{ $teachingAssignment->classroom->name }} &middot; {{ $teachingAssignment->subject->name }}
        </p>
    </div>
    <div>
        <a href="{{ route('guru.dashboard') }}" class="btn btn-outline-secondary btn-sm">&larr; Dashboard</a>
        <a href="{{ route('guru.teaching-assignments.tasks.create', $teachingAssignment) }}" class="btn btn-primary btn-sm">
            + Buat Tugas
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Judul</th>
                    <th>Batas Waktu</th>
                    <th class="text-center">Pengumpulan</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($tasks as $task)
                    <tr>
                        <td class="fw-semibold">{{ $task->title }}</td>
                        <td class="small">
                            {{ $task->deadline->format('d M Y, H:i') }}
                            @if ($task->isPastDeadline())
                                <span class="badge text-bg-danger ms-1">Lewat</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('guru.teaching-assignments.tasks.submissions', [$teachingAssignment, $task]) }}">
                                {{ $task->submissions_count }} siswa
                            </a>
                        </td>
                        <td class="text-center">
                            @if ($task->is_published)
                                <span class="badge text-bg-success">Terbit</span>
                            @else
                                <span class="badge text-bg-light text-muted">Draft</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('guru.teaching-assignments.tasks.submissions', [$teachingAssignment, $task]) }}"
                               class="btn btn-sm btn-outline-primary">Koreksi</a>
                            <a href="{{ route('guru.teaching-assignments.tasks.edit', [$teachingAssignment, $task]) }}"
                               class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('guru.teaching-assignments.tasks.destroy', [$teachingAssignment, $task]) }}"
                                  class="d-inline" onsubmit="return confirm('Hapus tugas {{ $task->title }}? Semua pengumpulan siswa ikut terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Belum ada tugas yang dibuat.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
