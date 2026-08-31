@extends('layouts.admin')

@section('title', 'Edit Jadwal Pelajaran')

@section('content')
<h4 class="fw-bold mb-4">Edit Jadwal Pelajaran</h4>

<div class="card border-0 shadow-sm" style="max-width: 640px;">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.schedules.update', $schedule) }}">
            @csrf
            @method('PUT')
            @php($reloadBaseUrl = route('admin.schedules.edit', $schedule))
            @include('admin.schedules.form')
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.schedules.index', ['academic_year_id' => $selectedYearId, 'classroom_id' => $selectedClassroomId]) }}"
                   class="btn btn-outline-secondary">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
