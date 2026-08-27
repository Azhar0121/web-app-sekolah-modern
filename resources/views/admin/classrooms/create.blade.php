@extends('layouts.admin')

@section('title', 'Tambah Kelas')

@section('content')
<h4 class="fw-bold mb-4">Tambah Kelas</h4>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.classrooms.store') }}">
            @csrf
            @php($classroom = null)
            @include('admin.classrooms.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.classrooms.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
