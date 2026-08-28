@extends('layouts.app')

@section('title', 'Materi Pembelajaran')

@section('content')
<h4 class="fw-bold mb-4">Materi Pembelajaran</h4>

@if (! $classroom)
    <div class="alert alert-warning">
        Anda belum terdaftar di kelas manapun pada tahun ajaran ini. Hubungi Tata Usaha / Wali Kelas.
    </div>
@elseif ($materialsBySubject->every(fn ($materials) => $materials->isEmpty()))
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center text-muted py-5">
            Belum ada materi yang diunggah untuk kelas {{ $classroom->name }}.
        </div>
    </div>
@else
    @foreach ($materialsBySubject as $subjectName => $materials)
        @continue($materials->isEmpty())
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-header bg-white fw-semibold">{{ $subjectName }}</div>
            <ul class="list-group list-group-flush">
                @foreach ($materials as $material)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-semibold">{{ $material->title }}</div>
                                @if ($material->description)
                                    <div class="text-muted small">{{ $material->description }}</div>
                                @endif
                                <div class="text-muted small mt-1">{{ $material->created_at->format('d M Y') }}</div>
                            </div>
                            <div class="text-end text-nowrap ms-3">
                                @if ($material->hasFile())
                                    <a href="{{ route('siswa.materials.download', $material) }}" class="btn btn-sm btn-outline-primary">
                                        Unduh
                                    </a>
                                @endif
                                @if ($material->hasLink())
                                    <a href="{{ $material->link }}" target="_blank" class="btn btn-sm btn-outline-info">
                                        Buka Link
                                    </a>
                                @endif
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
@endif
@endsection
