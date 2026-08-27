@extends('layouts.admin')

@section('title', 'Mata Pelajaran')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Mata Pelajaran</h4>
    <a href="{{ route('admin.subjects.create') }}" class="btn btn-primary">+ Tambah Mata Pelajaran</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.subjects.index') }}" class="row g-2 align-items-end">
            <div class="col-md-8">
                <label class="form-label small text-muted">Cari nama / kode</label>
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
                    <th>Kode</th>
                    <th>Nama Mata Pelajaran</th>
                    <th class="text-center">Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($subjects as $subject)
                    <tr>
                        <td><span class="badge text-bg-secondary">{{ $subject->code }}</span></td>
                        <td>{{ $subject->name }}</td>
                        <td class="text-center">
                            @if ($subject->is_active)
                                <span class="badge text-bg-success">Aktif</span>
                            @else
                                <span class="badge text-bg-light text-muted">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.subjects.edit', $subject) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.subjects.destroy', $subject) }}" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus mata pelajaran {{ $subject->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Tidak ada mata pelajaran yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($subjects->hasPages())
        <div class="card-body">
            {{ $subjects->links() }}
        </div>
    @endif
</div>
@endsection
