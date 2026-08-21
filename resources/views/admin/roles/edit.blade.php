@extends('layouts.admin')

@section('title', 'Edit Role: ' . $role->name)

@section('content')
<h4 class="fw-bold mb-4">Edit Role: {{ $role->name }}</h4>

<form method="POST" action="{{ route('admin.roles.update', $role) }}">
    @csrf
    @method('PUT')

    @if ($errors->any())
        <div class="alert alert-danger py-2">
            @foreach ($errors->all() as $error)
                <div class="small">{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <div class="card border-0 shadow-sm mb-3">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="name" class="form-label">Nama Role</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name', $role->name) }}" required>
                </div>
                <div class="col-md-6">
                    <label for="description" class="form-label">Deskripsi</label>
                    <input type="text" name="description" id="description" class="form-control"
                           value="{{ old('description', $role->description) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <h6 class="fw-bold mb-3">Permission untuk role ini</h6>

            @foreach ($permissionsByModule as $module => $permissions)
                <div class="mb-3">
                    <div class="text-uppercase text-muted small fw-bold mb-2">{{ $module ?? 'Lainnya' }}</div>
                    <div class="row">
                        @foreach ($permissions as $permission)
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                           id="perm-{{ $permission->id }}" class="form-check-input"
                                           @checked(in_array($permission->id, old('permissions', $assignedPermissionIds)))>
                                    <label for="perm-{{ $permission->id }}" class="form-check-label">
                                        {{ $permission->name }}
                                        <code class="text-muted small">{{ $permission->slug }}</code>
                                    </label>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="d-flex gap-2 mt-3">
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Batal</a>
    </div>
</form>
@endsection
