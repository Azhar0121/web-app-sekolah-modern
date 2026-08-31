@extends('layouts.admin')

@section('title', 'Tambah Jadwal Pelajaran')

@section('content')
<h4 class="fw-bold mb-4">Tambah Jadwal Pelajaran</h4>

<div class="card border-0 shadow-sm" style="max-width: 640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.schedules.store') }}">
            @csrf
            @php($schedule = null)
            @php($reloadBaseUrl = route('admin.schedules.create'))
            @include('admin.schedules.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.schedules.index', ['academic_year_id' => $selectedYearId, 'classroom_id' => $selectedClassroomId]) }}"
                   class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
