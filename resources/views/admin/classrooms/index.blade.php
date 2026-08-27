@extends('layouts.admin')

@section('title', 'Kelas')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kelas</h4>
    <a href="{{ route('admin.classrooms.create') }}" class="btn btn-primary">+ Tambah Kelas</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.classrooms.index') }}" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted">Cari nama kelas</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Ketik untuk mencari...">
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary w-100">Terapkan</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nama Kelas</th>
                    <th>Tingkat</th>
                    <th>Jurusan</th>
                    <th>Wali Kelas</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($classrooms as $classroom)
                    <tr>
                        <td class="fw-semibold">{{ $classroom->name }}</td>
                        <td>{{ $classroom->grade_level }}</td>
                        <td>{{ $classroom->major ?? '-' }}</td>
                        <td>{{ $classroom->homeroomTeacher?->name ?? '-' }}</td>
                        <td class="text-center">
                            @if ($classroom->is_active)
                                <span class="badge text-bg-success">Aktif</span>
                            @else
                                <span class="badge text-bg-light text-muted">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.classrooms.edit', $classroom) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.classrooms.destroy', $classroom) }}" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus kelas {{ $classroom->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Tidak ada kelas yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($classrooms->hasPages())
        <div class="card-body">
            {{ $classrooms->links() }}
        </div>
    @endif
</div>
@endsection
