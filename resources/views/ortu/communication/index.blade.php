@extends('layouts.app')

@section('title', 'Ruang Konsultasi Guru')

@section('content')
<div class="container py-4" style="max-width: 1000px;">

    <div class="card bg-dark text-white border-0 shadow-sm rounded-4 p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <span class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-1 mb-2">
                    <i class="bi bi-chat-dots-fill me-1"></i> RUANG KONSULTASI
                </span>
                <h3 class="fw-bold mb-1">Konsultasi dengan Guru / Wali Kelas</h3>
                <p class="text-secondary mb-0 small">
                    Komunikasi langsung dengan pihak sekolah mengenai perkembangan belajar dan aktivitas anak Anda.
                </p>
            </div>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-primary rounded-pill px-3 btn-sm" data-bs-toggle="modal" data-bs-target="#newOrtuCommunicationModal">
                    <i class="bi bi-plus-circle me-1"></i> Kirim Pesan Baru
                </button>
                <a href="{{ route('ortu.dashboard') }}" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="bi bi-arrow-left me-1"></i> Dasbor
                </a>
            </div>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm mb-4 rounded-3" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4">

        <div class="col-lg-4 col-md-5">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-light py-3 px-3 border-bottom d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold mb-0 text-dark">Daftar Konsultasi</h6>
                    <span class="badge bg-primary rounded-pill">{{ $threads->count() }}</span>
                </div>

                <div class="list-group list-group-flush overflow-auto" style="max-height: 550px;">
                    @forelse ($threads as $t)
                        @php
                            $isActive = $activeThread && $activeThread->id === $t->id;
                            $unread   = $t->unreadCountForUser(auth()->id());
                        @endphp
                        <a href="{{ route('ortu.communication.index', ['thread' => $t->id]) }}" 
                           class="list-group-item list-group-item-action p-3 border-bottom {{ $isActive ? 'bg-primary-subtle border-start border-4 border-primary' : '' }}">
                            
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <span class="badge {{ $t->categoryBadgeClass() }} rounded-pill font-monospace" style="font-size:0.65rem;">
                                    {{ $t->categoryLabel() }}
                                </span>
                                <small class="text-secondary" style="font-size:0.75rem;">
                                    {{ $t->last_message_at?->diffForHumans() ?? $t->created_at->diffForHumans() }}
                                </small>
                            </div>

                            <h6 class="fw-bold text-dark mb-1 text-truncate" style="font-size:0.9rem;">{{ $t->subject }}</h6>

                            <div class="small text-secondary mb-1">
                                <i class="bi bi-person-fill text-muted me-1"></i> Guru: <strong class="text-dark">{{ $t->teacher->name }}</strong>
                            </div>
                            <div class="small text-secondary mb-2">
                                <i class="bi bi-person-heart text-muted me-1"></i> Anak: {{ $t->student->name }}
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                @if ($t->status === 'resolved')
                                    <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill" style="font-size:0.65rem;">
                                        <i class="bi bi-check-circle me-1"></i> Selesai
                                    </span>
                                @else
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle rounded-pill" style="font-size:0.65rem;">
                                        <i class="bi bi-hourglass-split me-1"></i> Berlangsung
                                    </span>
                                @endif

                                @if ($unread > 0)
                                    <span class="badge bg-danger rounded-pill">{{ $unread }} pesan baru</span>
                                @endif
                            </div>

                        </a>
                    @empty
                        <div class="text-center p-4 text-secondary">
                            <i class="bi bi-chat-dots display-6 d-block mb-2 text-muted"></i>
                            <p class="small mb-0">Belum ada riwayat konsultasi.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="col-lg-8 col-md-7">
            @if ($activeThread)
                <div class="card border-0 shadow-sm rounded-4 overflow-hidden">

                    <div class="card-header bg-white py-3 px-4 border-bottom">
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
                                    <i class="bi bi-hourglass-split me-1"></i> Berlangsung
                                </span>
                            @endif
                        </div>
                        <h5 class="fw-bold text-dark mb-1">{{ $activeThread->subject }}</h5>
                        <div class="small text-secondary">
                            Guru: <strong class="text-dark">{{ $activeThread->teacher->name }}</strong>
                            &bull; Perihal Anak: <strong class="text-dark">{{ $activeThread->student->name }}</strong>
                        </div>
                    </div>

                    <div class="card-body bg-light p-4 overflow-auto" style="height: 420px;">
                        @foreach ($activeThread->messages as $msg)
                            @php
                                $isMe = $msg->sender_id === auth()->id();
                            @endphp
                            <div class="d-flex mb-3 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
                                <div class="card border-0 shadow-sm rounded-3 {{ $isMe ? 'bg-primary text-white' : 'bg-white text-dark' }}" style="max-width: 80%;">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center gap-3 mb-1">
                                            <span class="fw-bold small {{ $isMe ? 'text-white-50' : 'text-primary' }}">
                                                {{ $isMe ? 'Anda (Orang Tua)' : $msg->sender->name . ' (Guru)' }}
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
                                                    <i class="bi bi-paperclip me-1"></i> {{ $msg->attachment_name ?: 'Lampiran Berkas' }}
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="card-footer bg-white p-3 border-top">
                        <form action="{{ route('ortu.communication.reply', $activeThread) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="mb-2">
                                <textarea name="message" class="form-control form-control-sm" rows="2" placeholder="Tuliskan pesan balasan untuk Guru..." required></textarea>
                            </div>
                            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                                <div class="input-group input-group-sm" style="max-width: 300px;">
                                    <label class="input-group-text bg-light text-secondary" for="ortuReplyAttachment">
                                        <i class="bi bi-paperclip"></i>
                                    </label>
                                    <input type="file" name="attachment" class="form-control" id="ortuReplyAttachment">
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
                    <div class="display-4 text-muted mb-3"><i class="bi bi-chat-square-dots"></i></div>
                    <h5 class="fw-bold text-dark mb-1">Ruang Konsultasi</h5>
                    <p class="text-secondary small mb-3">Pilih percakapan dari daftar di sebelah kiri atau buat pesan konsultasi baru ke Guru/Wali Kelas.</p>
                    <div>
                        <button type="button" class="btn btn-primary rounded-pill px-4" data-bs-toggle="modal" data-bs-target="#newOrtuCommunicationModal">
                            <i class="bi bi-plus-circle me-1"></i> Kirim Pesan Baru
                        </button>
                    </div>
                </div>
            @endif
        </div>

    </div>

</div>

<div class="modal fade" id="newOrtuCommunicationModal" tabindex="-1" aria-labelledby="newOrtuCommunicationModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-header bg-dark text-white py-3">
                <h5 class="modal-header-title modal-title fw-bold fs-6" id="newOrtuCommunicationModalLabel">
                    <i class="bi bi-envelope-plus-fill me-2"></i> Kirim Pesan Konsultasi ke Guru
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('ortu.communication.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body p-4">

                    <div class="mb-3">
                        <label for="student_teacher_select" class="form-label small fw-semibold text-dark">Pilih Anak & Guru Tujuan</label>
                        <select id="student_teacher_select" class="form-select form-select-sm" required onchange="updateStudentTeacher(this)">
                            <option value="">-- Pilih Guru Tujuan --</option>
                            @foreach ($teacherOptions as $opt)
                                <option value="{{ $opt['child_id'] }}|{{ $opt['teacher_id'] }}">
                                    Anak: {{ $opt['child_name'] }} &mdash; Guru: {{ $opt['teacher_name'] }} ({{ $opt['role_label'] }})
                                </option>
                            @endforeach
                        </select>
                        <input type="hidden" name="student_id" id="hidden_student_id">
                        <input type="hidden" name="teacher_id" id="hidden_teacher_id">
                    </div>

                    <div class="mb-3">
                        <label for="category" class="form-label small fw-semibold text-dark">Kategori Topik</label>
                        <select name="category" id="category" class="form-select form-select-sm" required>
                            <option value="akademik">Akademik & Nilai Belajar</option>
                            <option value="kehadiran">Kehadiran / Presensi</option>
                            <option value="kedisiplinan">Kedisiplinan & Perilaku</option>
                            <option value="lainnya">Lainnya / Pertanyaan Umum</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label small fw-semibold text-dark">Subjek / Topik Konsultasi</label>
                        <input type="text" name="subject" id="subject" class="form-control form-control-sm" placeholder="Contoh: Pertanyaan Perkembangan Tugas Matematika" required>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label small fw-semibold text-dark">Isi Pesan</label>
                        <textarea name="message" id="message" class="form-control form-control-sm" rows="4" placeholder="Tuliskan pesan atau konsultasi Anda secara lengkap..." required></textarea>
                    </div>

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

<script>
function updateStudentTeacher(selectEl) {
    const val = selectEl.value;
    if (val) {
        const parts = val.split('|');
        document.getElementById('hidden_student_id').value = parts[0];
        document.getElementById('hidden_teacher_id').value = parts[1];
    } else {
        document.getElementById('hidden_student_id').value = '';
        document.getElementById('hidden_teacher_id').value = '';
    }
}
</script>
@endsection
