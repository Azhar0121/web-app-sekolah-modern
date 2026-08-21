@extends('layouts.admin')

@section('title', 'Edit User')

@section('content')
<h4 class="fw-bold mb-4">Edit User: {{ $user->name }}</h4>

<div class="card border-0 shadow-sm" style="max-width: 560px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')
            @include('admin.users._form', ['user' => $user, 'roles' => $roles])

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
