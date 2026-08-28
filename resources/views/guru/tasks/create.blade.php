@extends('layouts.app')

@section('title', 'Buat Tugas')

@section('content')
<h4 class="fw-bold mb-1">Buat Tugas</h4>
<p class="text-muted mb-4">{{ $teachingAssignment->classroom->name }} &middot; {{ $teachingAssignment->subject->name }}</p>

<div class="card border-0 shadow-sm" style="max-width: 650px;">
    <div class="card-body">
        <form method="POST" action="{{ route('guru.teaching-assignments.tasks.store', $teachingAssignment) }}" enctype="multipart/form-data">
            @csrf
            @php($task = null)
            @include('guru.tasks.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Tugas</button>
                <a href="{{ route('guru.teaching-assignments.tasks.index', $teachingAssignment) }}" class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
