@extends('layouts.admin')

@section('title', 'Ruang Komunikasi Orang Tua')

@section('content')
<div class="container-fluid py-4">

    {{-- HEADER PAGE --}}
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h3 class="fw-bold text-dark mb-1">
                <i class="bi bi-chat-dots-fill text-primary me-2"></i> Ruang Komunikasi & Konsultasi Ortu
            </h3>
            <p class="text-secondary mb-0 small">
                Sarana komunikasi terarah antara Guru/Wali Kelas dengan Orang Tua siswa.
            </p>
        </div>
        <button type="button" class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#newCommunicationModal">
            <i class="bi bi-plus-circle me-1"></i> Mulai Konsultasi Baru
        </button>
    </div>

    {{-- FLASH MESSAGES --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- MAIN CONTENT --}}
    <div class="row g-4">

        {{-- LEFT COLUMN: LIST UTAMA KONSULTASI --}}
        <div class="col-lg-4 col-md-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-light py-3 px-3 border-bottom">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold mb-0 text-dark">Daftar Percakapan</h6>
                        <span class="badge bg-secondary rounded-pill">{{ $threads->count() }}</span>
                    </div>

                    {{-- FILTER STATUS --}}
                    <div class="btn-group w-100 btn-group-sm" role="group">
                        <a href="{{ route('guru.communication.index', ['status' => 'all']) }}" 
                           class="btn {{ $status === 'all' ? 'btn-primary' : 'btn-outline-secondary' }}">Semua</a>
                        <a href="{{ route('guru.communication.index', ['status' => 'open']) }}" 
                           class="btn {{ $status === 'open' ? 'btn-primary' : 'btn-outline-secondary' }}">Aktif</a>
                        <a href="{{ route('guru.communication.index', ['status' => 'resolved']) }}" 
                           class="btn {{ $status === 'resolved' ? 'btn-primary' : 'btn-outline-secondary' }}">Selesai</a>
                    </div>
                </div>

                <div class="list-group list-group-flush overflow-auto" style="max-height: 600px;">
                    @forelse ($threads as $t)
                        @php
                            $isActive = $activeThread && $activeThread->id === $t->id;
                            $unread   = $t->unreadCountForUser(auth()->id());
                        @endphp
                        <a href="{{ route('guru.communication.index', ['thread' => $t->id, 'status' => $status]) }}" 
                           class="list-group-item list-group-item-action p-3 border-bottom {{ $isActive ? 'bg-primary-subtle border-start border-4 border-primary' : '' }}">
                            
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="badge {{ $t->categoryBadgeClass() }} rounded-pill font-monospace" style="font-size:0.7rem;">
                                    {{ $t->categoryLabel() }}
                                </span>
                                <small class="text-secondary" style="font-size:0.75rem;">
                                    {{ $t->last_message_at?->diffForHumans() ?? $t->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <h6 class="fw-bold text-dark mb-1 text-truncate">{{ $t->subject }}</h6>

                            <div class="small text-secondary mb-1">
                                <i class="bi bi-person-fill text-muted me-1"></i> Siswa: <strong class="text-dark">{{ $t->student->name }}</strong>
                            </div>
                            <div class="small text-secondary mb-2">
                                <i class="bi bi-people-fill text-muted me-1"></i> Ortu: {{ $t->parent->name }}
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                @if ($t->status === 'resolved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill small" style="font-size:0.65rem;">
                                        <i class="bi bi-check-circle me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill small" style="font-size:0.65rem;">
                                        <i class="bi bi-hourglass-split me-1"></i> Aktif
                                    </span>
                                @endif

                                @if ($unread > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $unread }} baru</span>
                                @endif
                            </div>

                        </a>
                    @empty
                        <div class="text-center p-4 text-secondary">
                            <i class="bi bi-chat-left-dots display-6 d-block mb-2 text-muted"></i>
                            <p class="small mb-0">Belum ada daftar percakapan.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- RIGHT COLUMN: DETAIL RUANG PERCAKAPAN --}}
        <div class="col-lg-8 col-md-7">
            @if ($activeThread)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                    
                    {{-- THREAD HEADER --}}
                    <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <span class="badge {{ $activeThread->categoryBadgeClass() }} rounded-pill">
                                    {{ $activeThread->categoryLabel() }}
                                </span>
                                @if ($activeThread->status === 'resolved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill">
                                        <i class="bi bi-check-circle me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill">
                                        <i class="bi bi-hourglass-split me-1"></i> Dalam Diskusi
                                    </span>
                                @endif
                            </div>
                            <h5 class="fw-bold text-dark mb-1">{{ $activeThread->subject }}</h5>
                            <div class="small text-secondary">
                                Siswa: <strong class="text-dark">{{ $activeThread->student->name }}</strong>
                                &bull; Orang Tua: <strong class="text-dark">{{ $activeThread->parent->name }}</strong>
                            </div>
                        </div>

                        {{-- TOMBOL TOGGLE STATUS --}}
                        <form action="{{ route('guru.communication.toggle-status', $activeThread) }}" method="POST">
                            @csrf
                            @if ($activeThread->status === 'open')
                                <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">
                                    <i class="bi bi-check2-circle me-1"></i> Tandai Selesai
                                </button>
                            @else
                                <button type="submit" class="btn btn-sm btn-outline-warning rounded-pill px-3">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Buka Kembali
                                </button>
                            @endif
                        </form>
                    </div>

                    {{-- MESSAGES STREAM --}}
                    <div class="card-body bg-light p-4 overflow-auto" style="height: 440px;">
                        @foreach ($activeThread->messages as $msg)
                            @php
                                $isMe = $msg->sender_id === auth()->id();
                            @endphp
                            <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="card border-0 shadow-sm rounded-3 {{ $isMe ? 'bg-primary text-white' : 'bg-white text-dark' }}" style="max-width: 80%;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center gap-3 mb-1">
                                            <span class="fw-bold small {{ $isMe ? 'text-white-50' : 'text-primary' }}">
                                                {{ $isMe ? 'Anda (Guru)' : $msg->sender->name . ' (Ortu)' }}
                                            </span>
                                            <span class="small {{ $isMe ? 'text-white-50' : 'text-muted' }}" style="font-size:0.7rem;">
                                                {{ $msg->created_at->format('H:i, d M Y') }}
                                            </span>
                                        </div>

                                        <p class="mb-0 small" style="white-space: pre-wrap;">{{ $msg->message }}</p>

                                        @if ($msg->attachment_path)
                                            <div class="mt-2 pt-2 border-top {{ $isMe ? 'border-white-50' : 'border-light' }}">
                                                <a href="{{ \Illuminate\Support\Facades\Storage::url($msg->attachment_path) }}" 
                                                   target="_blank" 
                                                   class="btn btn-xs {{ $isMe ? 'btn-light text-primary' : 'btn-outline-primary' }} rounded-pill py-1 px-2.5 small"
                                                   style="font-size:0.75rem;">
                                                    <i class="bi bi-paperclip me-1"></i> {{ $msg->attachment_name ?: 'Lampiran' }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- REPLY FORM --}}
                    <div class="card-footer bg-white p-3 border-top">
                        <form action="{{ route('guru.communication.reply', $activeThread) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="Tulis balasan pesan untuk Orang Tua..." required></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="input-group input-group-sm style-file-input" style="max-width: 320px;">
                                    <label class="input-group-text bg-light text-secondary" for="replyAttachment">
                                        <i class="bi bi-paperclip"></i>
                                    </label>
                                    <input type="file" name="attachment" class="form-control" id="replyAttachment">
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">
                                    <i class="bi bi-send-fill me-1"></i> Kirim Balasan
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            @else
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center my-4">
                    <div class="display-4 text-muted mb-3"><i class="bi bi-chat-square-text"></i></div>
                    <h5 class="fw-bold text-dark mb-1">Pilih Ruang Komunikasi</h5>
                    <p class="text-secondary small mb-3">Pilih percakapan di sebelah kiri atau buat konsultasi baru dengan Orang Tua siswa.</p>
                    <div>
                        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newCommunicationModal">
                            <i class="bi bi-plus-circle me-1"></i> Buat Konsultasi Baru
                        </button>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>

{{-- MODAL BUAT KONSULTASI BARU --}}
<div class="modal fade" id="newCommunicationModal" tabindex="-1" aria-labelledby="newCommunicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-primary text-white py-3">
                <h5 class="modal-header-title modal-title fw-bold fs-6" id="newCommunicationModalLabel">
                    <i class="bi bi-chat-plus-fill me-2"></i> Mulai Konsultasi Baru dengan Ortu
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('guru.communication.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">
                    
                    {{-- PILIH SISWA --}}
                    <div class="mb-3">
                        <label for="student_id" class="form-label small fw-semibold text-dark">Pilih Siswa & Ortu</label>
                        <select name="student_id" id="student_id" class="form-select form-select-sm" required>
                            <option value="">-- Pilih Siswa --</option>
                            @foreach ($students as $st)
                                @php
                                    $ortuName = $st->parents->first()?->name ?? 'Belum ada akun Ortu';
                                    $class    = $st->classroomStudents->first()?->classroom?->name ?? '-';
                                @endphp
                                <option value="{{ $st->id }}">
                                    {{ $st->name }} (Kelas {{ $class }}) &mdash; Ortu: {{ $ortuName }}
                                </option>
                            @endforeach
                        </select>
                        <div class="form-text small">Pesan akan dikirimkan ke akun Orang Tua yang tertaut dengan siswa ini.</div>
                    </div>

                    {{-- KATEGORI --}}
                    <div class="mb-3">
                        <label for="category" class="form-label small fw-semibold text-dark">Kategori Topik</label>
                        <select name="category" id="category" class="form-select form-select-sm" required>
                            <option value="akademik">Akademik & Perkembangan Belajar</option>
                            <option value="kedisiplinan">Kedisiplinan & Perilaku</option>
                            <option value="kehadiran">Kehadiran & Absensi</option>
                            <option value="lainnya">Lainnya / Konsultasi Umum</option>
                        </select>
                    </div>

                    {{-- JUDUL / TOPIK --}}
                    <div class="mb-3">
                        <label for="subject" class="form-label small fw-semibold text-dark">Judul Topik / Subjek</label>
                        <input type="text" name="subject" id="subject" class="form-control form-control-sm" placeholder="Contoh: Evaluasi Belajar Matematika Semester 1" required>
                    </div>

                    {{-- ISI PESAN --}}
                    <div class="mb-3">
                        <label for="message" class="form-label small fw-semibold text-dark">Isi Pesan Pertama</label>
                        <textarea name="message" id="message" class="form-control form-control-sm" rows="4" placeholder="Tuliskan pesan atau poin konsultasi kepada Orang Tua..." required></textarea>
                    </div>

                    {{-- LAMPIRAN FILE --}}
                    <div class="mb-2">
                        <label for="attachment" class="form-label small fw-semibold text-dark">Lampiran Berkas (Opsional)</label>
                        <input type="file" name="attachment" id="attachment" class="form-control form-control-sm">
                        <div class="form-text small">PDF, DOC, DOCX, JPG, PNG, ZIP (Maks 10MB)</div>
                    </div>

                </div>
                <div class="modal-footer bg-light py-2 px-4">
                    <button type="button" class="btn btn-sm btn-secondary rounded-pill px-3" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary rounded-pill px-4">Kirim Pesan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
