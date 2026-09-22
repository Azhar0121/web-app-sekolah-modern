@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Rapor Siswa: {{ $student->name }}</h2>
    
    <div class="card mt-4 mb-4">
        <div class="card-header bg-white">
            <h5 class="mb-0">Upload Rapor Baru</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.report-cards.store', $student->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row align-items-end">
                    <div class="col-md-3 mb-3">
                        <label for="semester_id" class="form-label">Semester <span class="text-danger">*</span></label>
                        <select name="semester_id" id="semester_id" class="form-select" required>
                            <option value="">Pilih Semester</option>
                            @foreach($semesters as $sem)
                                <option value="{{ $sem->id }}">{{ $sem->name }} ({{ $sem->academicYear->name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="label" class="form-label">Label (Opsional, cth: UTS/UAS)</label>
                        <input type="text" name="label" id="label" class="form-control" placeholder="Rapor Akhir Semester">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label for="file" class="form-label">File Rapor (PDF) <span class="text-danger">*</span></label>
                        <input type="file" name="file" id="file" class="form-control" accept=".pdf" required>
                    </div>
                    <div class="col-md-1 mb-3">
                        <button type="submit" class="btn btn-primary w-100">Upload</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">Daftar Rapor</h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Semester</th>
                            <th>Label</th>
                            <th>Nama File</th>
                            <th>Diupload Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportCards as $report)
                        <tr>
                            <td>{{ $report->semester->name }} ({{ $report->semester->academicYear->name }})</td>
                            <td>{{ $report->label ?? '-' }}</td>
                            <td>{{ $report->file_original_name }}</td>
                            <td>{{ $report->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ Storage::url($report->file_path) }}" target="_blank" class="btn btn-sm btn-info text-white">Lihat PDF</a>
                                <form action="{{ route('admin.report-cards.destroy', $report->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus rapor ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center">Belum ada rapor yang diupload.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
