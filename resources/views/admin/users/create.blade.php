@extends('layouts.admin')

@section('title', 'Tambah User')

@section('content')
<h4 class="fw-bold mb-4">Tambah User Baru</h4>

<div class="card border-0 shadow-sm" style="max-width: 560px;">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf
            @include('admin.users._form', ['user' => null, 'roles' => $roles])

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
