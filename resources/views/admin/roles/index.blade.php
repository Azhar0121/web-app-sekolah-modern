@extends('layouts.admin')

@section('title', 'Kelola Role & Permission')

@section('content')
<h4 class="fw-bold mb-4">Kelola Role & Permission</h4>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>Role</th>
                    <th>Deskripsi</th>
                    <th class="text-center">Jumlah User</th>
                    <th class="text-center">Jumlah Permission</th>
                    <th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($roles as $role)
                    <tr>
                        <td><span class="badge text-bg-secondary">{{ $role->name }}</span></td>
                        <td class="text-muted small">{{ $role->description ?? '-' }}</td>
                        <td class="text-center">{{ $role->users_count }}</td>
                        <td class="text-center">{{ $role->permissions_count }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-secondary">
                                Kelola Permission
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<p class="text-muted small mt-3">
    Catatan: 6 role sudah baku sesuai rancangan sistem (tidak bisa tambah/hapus role baru dari sini).
    Yang bisa diubah adalah nama tampilan, deskripsi, dan permission yang di-assign ke tiap role.
</p>
@endsection
