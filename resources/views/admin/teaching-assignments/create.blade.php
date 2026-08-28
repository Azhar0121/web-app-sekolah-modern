@extends('layouts.admin')

@section('title', 'Tambah Penugasan Mengajar')

@section('content')
<h4 class="fw-bold mb-4">Tambah Penugasan Mengajar</h4>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.teaching-assignments.store') }}">
            @csrf
            @php($assignment = null)
            @include('admin.teaching-assignments.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.teaching-assignments.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
