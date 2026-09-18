@extends('layouts.admin')

@section('title', 'Biodata Siswa')

@section('content')
<h4 class="fw-bold mb-4">Biodata Siswa</h4>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.student-profiles.index') }}" class="row g-2">
            <div class="col-md-6">
                <input type="text" name="search" value="{{ $search }}" class="form-control"
                       placeholder="Cari nama, email, atau NISN...">
            </div>
            <div class="col-md-4">
                <select name="classroom_id" class="form-select">
                    <option value="">Semua Kelas</option>
                    @foreach ($classrooms as $classroom)
                        <option value="{{ $classroom->id }}" @selected($classroomId == $classroom->id)>
                            {{ $classroom->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama</th>
                    <th>NISN</th>
                    <th>Kelas</th>
                    <th>No. HP</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($students as $student)
                    <tr>
                        <td>
                            {{ $student->name }}
                            <div class="text-muted small">{{ $student->email }}</div>
                        </td>
                        <td>{{ $student->studentProfile?->nisn ?? '-' }}</td>
                        <td>{{ $student->classroomForDisplay?->name ?? '-' }}</td>
                        <td>{{ $student->studentProfile?->phone ?? '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.student-profiles.edit', $student) }}" class="btn btn-sm btn-outline-secondary">
                                Kelola Biodata
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tidak ada siswa yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($students->hasPages())
        <div class="card-footer bg-white">{{ $students->links() }}</div>
    @endif
</div>
@endsection
