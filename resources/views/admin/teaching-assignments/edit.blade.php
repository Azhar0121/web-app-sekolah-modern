@extends('layouts.admin')

@section('title', 'Edit Penugasan Mengajar')

@section('content')
<h4 class="fw-bold mb-4">Edit Penugasan Mengajar</h4>

<div class="card border-0 shadow-sm" style="max-width: 600px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.teaching-assignments.update', $assignment) }}">
            @csrf
            @method('PUT')
            @include('admin.teaching-assignments.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.teaching-assignments.index') }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
