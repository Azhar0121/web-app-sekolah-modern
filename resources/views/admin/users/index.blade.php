@extends('layouts.admin')

@section('title', 'Kelola User')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">Kelola User</h4>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Tambah User</a>
</div>

<div class="card border-0 shadow-sm mb-3">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.users.index') }}" class="row g-2 align-items-end">
            <div class="col-md-5">
                <label class="form-label small text-muted">Cari nama / email</label>
                <input type="text" name="search" value="{{ $search }}" class="form-control" placeholder="Ketik untuk mencari...">
            </div>
            <div class="col-md-4">
                <label class="form-label small text-muted">Filter Role</label>
                <select name="role" class="form-select">
                    <option value="">Semua Role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->slug }}" @selected($roleFilter === $role->slug)>{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
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
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if ($user->role)
                                <span class="badge text-bg-secondary">{{ $user->role->name }}</span>
                            @else
                                <span class="badge text-bg-light text-muted">Belum ada role</span>
                            @endif
                        </td>
                        <td>
                            @if ($user->is_active)
                                <span class="badge text-bg-success">Aktif</span>
                            @else
                                <span class="badge text-bg-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="d-inline"
                                  onsubmit="return confirm('Yakin ingin menghapus user {{ $user->name }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" @disabled($user->id === auth()->id())>
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted py-4">Tidak ada user yang cocok.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if ($users->hasPages())
        <div class="card-body">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
