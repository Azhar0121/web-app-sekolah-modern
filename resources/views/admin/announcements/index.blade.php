@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Pengumuman</h2>
        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">+ Buat Pengumuman</a>
    </div>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <ul class="nav nav-tabs card-header-tabs">
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === null || request('status') === 'all' ? 'active' : '' }}" href="{{ route('admin.announcements.index', ['status' => 'all']) }}">Semua</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'published' ? 'active' : '' }}" href="{{ route('admin.announcements.index', ['status' => 'published']) }}">Published</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request('status') === 'draft' ? 'active' : '' }}" href="{{ route('admin.announcements.index', ['status' => 'draft']) }}">Draft</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Judul</th>
                            <th>Target</th>
                            <th>Prioritas</th>
                            <th>Status</th>
                            <th>Dibuat</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $ann)
                        <tr>
                            <td>{{ $ann->title }}</td>
                            <td>
                                @php $roles = is_array($ann->target_roles) ? $ann->target_roles : json_decode($ann->target_roles, true); @endphp
                                @if($roles && in_array('all', $roles))
                                    <span class="badge bg-secondary">Semua</span>
                                @else
                                    @foreach($roles ?? [] as $role)
                                        <span class="badge bg-info text-dark">{{ ucfirst($role) }}</span>
                                    @endforeach
                                @endif
                            </td>
                            <td>
                                @if($ann->priority === 'urgent')
                                    <span class="badge bg-danger">Urgent</span>
                                @elseif($ann->priority === 'penting')
                                    <span class="badge bg-warning text-dark">Penting</span>
                                @else
                                    <span class="badge bg-primary">Normal</span>
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('admin.announcements.toggle', $ann->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm {{ $ann->is_published ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $ann->is_published ? 'Published' : 'Draft' }}
                                    </button>
                                </form>
                            </td>
                            <td>{{ $ann->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.announcements.edit', $ann->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.announcements.destroy', $ann->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengumuman ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada pengumuman</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
