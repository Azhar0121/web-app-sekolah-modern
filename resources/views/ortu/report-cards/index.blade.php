@extends('layouts.app')

@section('content')
<div class="container">
    @include('layouts.partials.announcements-banner')

    <h2>Rapor Digital: {{ $student->name }}</h2>

    <div class="row mt-4">
        @forelse($reportCards as $report)
        <div class="col-md-4 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body text-center d-flex flex-column">
                    <div class="display-4 text-primary mb-3">
                        📄
                    </div>
                    <h5 class="card-title">{{ $report->semester->name }}</h5>
                    <h6 class="card-subtitle mb-2 text-muted">{{ $report->semester->academicYear->name }}</h6>
                    @if($report->label)
                        <p class="card-text text-info fw-bold">{{ $report->label }}</p>
                    @endif
                    <p class="text-muted small mt-auto mb-3">Diupload: {{ $report->created_at->format('d/m/Y') }}</p>
                    
                    <a href="{{ route('ortu.report-cards.download', $report->id) }}" class="btn btn-primary w-100 mt-auto">Download PDF</a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12 text-center py-5">
            <h4 class="text-muted">Belum ada rapor yang tersedia untuk anak ini.</h4>
            <p>Silakan hubungi pihak sekolah jika Anda merasa ini adalah kesalahan.</p>
        </div>
        @endforelse
    </div>
</div>
@endsection
