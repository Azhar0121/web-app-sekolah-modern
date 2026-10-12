@extends('layouts.app')

@section('title', 'Ruang Konsultasi Guru')

@section('content')
<link rel="stylesheet" href="{{ asset('css/ortu/communication/index.css') }}">

<div class="ortu-consultation">

    <div class="oc-container">

        {{-- HERO --}}
        <section class="oc-hero">
            <div class="oc-hero-content">
                <span class="oc-eyebrow">
                    <i class="bi bi-chat-dots-fill"></i>
                    PORTAL KOMUNIKASI
                </span>

                <h1>Ruang Konsultasi Guru</h1>

                
                <p>
                    Sampaikan pertanyaan dan diskusikan perkembangan belajar
                    serta aktivitas anak bersama Guru atau Wali Kelas.
                </p>

                <a href="{{ route('ortu.dashboard') }}" class="oc-back-button">
                    <i class="bi bi-arrow-left"></i>
                    <span>Kembali</span>
                </a>
                

                <div class="oc-hero-meta">
                    <span>
                        <i class="bi bi-chat-square-text"></i>
                        {{ $threads->count() }} percakapan
                    </span>
                    <span>
                        <i class="bi bi-shield-check"></i>
                        Komunikasi dengan pihak sekolah
                    </span>
                </div>
            </div>

            <div class="oc-hero-decoration">
                <i class="bi bi-chat-square-heart"></i>
            </div>
        </section>

        {{-- NOTIFIKASI --}}
        @if (session('success'))
            <div class="oc-alert-success" role="alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
                <button type="button" class="oc-alert-close" data-bs-dismiss="alert" aria-label="Tutup">
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="oc-alert-error" role="alert">
                <i class="bi bi-exclamation-circle-fill"></i>
                <div>
                    <strong>Periksa kembali data yang kamu masukkan.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- KONTEN UTAMA --}}
        <div class="oc-layout">

            {{-- DAFTAR KONSULTASI --}}
            <aside class="oc-sidebar">
                <div class="oc-panel oc-thread-panel">

                    <div class="oc-panel-header">
                        <div class="oc-panel-heading">
                            <span class="oc-panel-icon">
                                <i class="bi bi-inboxes-fill"></i>
                            </span>
                            <div>
                                <h2>Daftar Konsultasi</h2>
                                <p>Riwayat percakapanmu</p>
                            </div>
                        </div>

                        <span class="oc-count">{{ $threads->count() }}</span>
                    </div>

                    <div class="oc-thread-list">
                        @forelse ($threads as $t)
                            @php
                                $isActive = $activeThread && $activeThread->id === $t->id;
                                $unread = $t->unreadCountForUser(auth()->id());
                            @endphp

                            <a
                                href="{{ route('ortu.communication.index', ['thread' => $t->id]) }}"
                                class="oc-thread-item {{ $isActive ? 'is-active' : '' }}"
                            >
                                <div class="oc-thread-top">
                                    <span class="oc-category-badge {{ $t->categoryBadgeClass() }}">
                                        {{ $t->categoryLabel() }}
                                    </span>

                                    <span class="oc-thread-time">
                                        {{ $t->last_message_at?->diffForHumans() ?? $t->created_at->diffForHumans() }}
                                    </span>
                                </div>

                                <h3>{{ $t->subject }}</h3>

                                <div class="oc-thread-person">
                                    <i class="bi bi-person-workspace"></i>
                                    <span>Guru: <strong>{{ $t->teacher->name }}</strong></span>
                                </div>

                                <div class="oc-thread-person">
                                    <i class="bi bi-person-heart"></i>
                                    <span>Anak: {{ $t->student->name }}</span>
                                </div>

                                <div class="oc-thread-bottom">
                                    @if ($t->status === 'resolved')
                                        <span class="oc-status oc-status-done">
                                            <i class="bi bi-check-circle-fill"></i>
                                            Selesai
                                        </span>
                                    @else
                                        <span class="oc-status oc-status-progress">
                                            <i class="bi bi-hourglass-split"></i>
                                            Berlangsung
                                        </span>
                                    @endif

                                    @if ($unread > 0)
                                        <span class="oc-unread">
                                            {{ $unread }} baru
                                        </span>
                                    @endif
                                </div>
                            </a>
                        @empty
                            <div class="oc-thread-empty">
                                <div class="oc-empty-icon">
                                    <i class="bi bi-chat-square-text"></i>
                                </div>
                                <h3>Belum Ada Konsultasi</h3>
                                <p>Mulai percakapan baru dengan Guru atau Wali Kelas untuk berdiskusi mengenai anak.</p>
                                <button
                                    type="button"
                                    class="oc-button oc-button-primary"
                                    data-bs-toggle="modal"
                                    data-bs-target="#newOrtuCommunicationModal"
                                >
                                    <i class="bi bi-plus-lg"></i>
                                    Buat Konsultasi
                                </button>
                            </div>
                        @endforelse
                    </div>
                </div>
            </aside>

            {{-- PANEL PERCAKAPAN --}}
            <main class="oc-main">
                @if ($activeThread)
                    <section class="oc-panel oc-conversation-panel">

                        {{-- HEADER PERCAKAPAN --}}
                        <div class="oc-conversation-header">
                            <div class="oc-conversation-heading">
                                <div class="oc-conversation-avatar">
                                    <i class="bi bi-chat-dots-fill"></i>
                                </div>

                                <div class="oc-conversation-title">
                                    <div class="oc-conversation-badges">
                                        <span class="oc-category-badge {{ $activeThread->categoryBadgeClass() }}">
                                            {{ $activeThread->categoryLabel() }}
                                        </span>

                                        @if ($activeThread->status === 'resolved')
                                            <span class="oc-status oc-status-done">
                                                <i class="bi bi-check-circle-fill"></i>
                                                Selesai
                                            </span>
                                        @else
                                            <span class="oc-status oc-status-progress">
                                                <i class="bi bi-hourglass-split"></i>
                                                Berlangsung
                                            </span>
                                        @endif
                                    </div>

                                    <h2>{{ $activeThread->subject }}</h2>

                                    <p>
                                        <i class="bi bi-person-workspace"></i>
                                        {{ $activeThread->teacher->name }}
                                        <span class="oc-meta-separator">•</span>
                                        <i class="bi bi-person-heart"></i>
                                        {{ $activeThread->student->name }}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- PESAN --}}
                        <div class="oc-messages">
                            @forelse ($activeThread->messages as $msg)
                                @php
                                    $isMe = $msg->sender_id === auth()->id();
                                @endphp

                                <div class="oc-message-row {{ $isMe ? 'is-mine' : 'is-theirs' }}">
                                    <div class="oc-message-avatar">
                                        <i class="bi {{ $isMe ? 'bi-person-fill' : 'bi-person-workspace' }}"></i>
                                    </div>

                                    <div class="oc-message-content">
                                        <div class="oc-message-sender">
                                            {{ $isMe ? 'Anda (Orang Tua)' : $msg->sender->name . ' (Guru)' }}
                                        </div>

                                        <div class="oc-message-bubble">
                                            <p>{{ $msg->message }}</p>

                                            @if ($msg->attachment_path)
                                                <a
                                                    href="{{ \Illuminate\Support\Facades\Storage::url($msg->attachment_path) }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="oc-attachment"
                                                >
                                                    <span class="oc-attachment-icon">
                                                        <i class="bi bi-paperclip"></i>
                                                    </span>
                                                    <span class="oc-attachment-name">
                                                        {{ $msg->attachment_name ?: 'Lampiran Berkas' }}
                                                    </span>
                                                    <i class="bi bi-box-arrow-up-right"></i>
                                                </a>
                                            @endif
                                        </div>

                                        <span class="oc-message-time">
                                            <i class="bi bi-clock"></i>
                                            {{ $msg->created_at->format('H:i, d M Y') }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="oc-no-messages">
                                    <i class="bi bi-chat-left-text"></i>
                                    <p>Belum ada pesan dalam percakapan ini.</p>
                                </div>
                            @endforelse
                        </div>

                        {{-- FORM BALASAN --}}
                        <div class="oc-reply-area">
                            <div class="oc-reply-title">
                                <span class="oc-reply-icon">
                                    <i class="bi bi-reply-fill"></i>
                                </span>
                                <div>
                                    <h3>Balas Pesan</h3>
                                    <p>Tuliskan pesan untuk Guru atau Wali Kelas.</p>
                                </div>
                            </div>

                            <form
                                action="{{ route('ortu.communication.reply', $activeThread) }}"
                                method="POST"
                                enctype="multipart/form-data"
                            >
                                @csrf

                                <div class="oc-form-group">
                                    <textarea
                                        name="message"
                                        class="oc-textarea"
                                        rows="3"
                                        placeholder="Tuliskan pesan balasan..."
                                        required
                                    ></textarea>
                                </div>

                                <div class="oc-reply-actions">
                                    <div class="oc-file-field">
                                        <label for="ortuReplyAttachment">
                                            <i class="bi bi-paperclip"></i>
                                            <span>Lampiran (opsional)</span>
                                        </label>
                                        <input
                                            type="file"
                                            name="attachment"
                                            id="ortuReplyAttachment"
                                        >
                                    </div>

                                    <button type="submit" class="oc-button oc-button-primary">
                                        <i class="bi bi-send-fill"></i>
                                        Kirim Balasan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </section>
                @else
                    <section class="oc-panel oc-welcome-panel">
                        <div class="oc-welcome-illustration">
                            <span class="oc-welcome-bubble oc-bubble-one">
                                <i class="bi bi-chat-left-text-fill"></i>
                            </span>
                            <span class="oc-welcome-bubble oc-bubble-two">
                                <i class="bi bi-chat-right-dots-fill"></i>
                            </span>
                            <span class="oc-welcome-main-icon">
                                <i class="bi bi-chat-square-heart-fill"></i>
                            </span>
                        </div>

                        <span class="oc-section-kicker">PUSAT KOMUNIKASI SEKOLAH</span>
                        <h2>Halo, selamat datang!</h2>
                        <p>
                            Pilih percakapan dari daftar konsultasi atau mulai pesan baru
                            untuk berkomunikasi dengan Guru atau Wali Kelas.
                        </p>

                        <button
                            type="button"
                            class="oc-button oc-button-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#newOrtuCommunicationModal"
                        >
                            <i class="bi bi-plus-circle"></i>
                            Kirim Pesan Baru
                        </button>
                    </section>
                @endif
            </main>
        </div>
    </div>
</div>

{{-- MODAL KONSULTASI BARU --}}
<div
    class="modal fade oc-modal"
    id="newOrtuCommunicationModal"
    tabindex="-1"
    aria-labelledby="newOrtuCommunicationModalLabel"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">

            <div class="oc-modal-header">
                <div class="oc-modal-title-icon">
                    <i class="bi bi-envelope-plus-fill"></i>
                </div>

                <div>
                    <h2 id="newOrtuCommunicationModalLabel">Konsultasi Baru</h2>
                    <p>Buat percakapan dengan Guru atau Wali Kelas.</p>
                </div>

                <button
                    type="button"
                    class="oc-modal-close"
                    data-bs-dismiss="modal"
                    aria-label="Tutup"
                >
                    <i class="bi bi-x-lg"></i>
                </button>
            </div>

            <form
                action="{{ route('ortu.communication.store') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="oc-modal-body">

                    <div class="oc-form-group">
                        <label for="student_teacher_select">
                            <i class="bi bi-people-fill"></i>
                            Pilih Anak dan Guru Tujuan
                        </label>

                        <select
                            id="student_teacher_select"
                            class="oc-input"
                            required
                            onchange="updateStudentTeacher(this)"
                        >
                            <option value="">-- Pilih Anak dan Guru --</option>

                            @foreach ($teacherOptions as $opt)
                                <option value="{{ $opt['child_id'] }}|{{ $opt['teacher_id'] }}">
                                    Anak: {{ $opt['child_name'] }} — Guru: {{ $opt['teacher_name'] }} ({{ $opt['role_label'] }})
                                </option>
                            @endforeach
                        </select>

                        <input type="hidden" name="student_id" id="hidden_student_id">
                        <input type="hidden" name="teacher_id" id="hidden_teacher_id">
                    </div>

                    <div class="oc-form-group">
                        <label for="category">
                            <i class="bi bi-tags-fill"></i>
                            Kategori Topik
                        </label>

                        <select name="category" id="category" class="oc-input" required>
                            <option value="akademik">Akademik & Nilai Belajar</option>
                            <option value="kehadiran">Kehadiran / Presensi</option>
                            <option value="kedisiplinan">Kedisiplinan & Perilaku</option>
                            <option value="lainnya">Lainnya / Pertanyaan Umum</option>
                        </select>
                    </div>

                    <div class="oc-form-group">
                        <label for="subject">
                            <i class="bi bi-card-heading"></i>
                            Subjek / Topik Konsultasi
                        </label>

                        <input
                            type="text"
                            name="subject"
                            id="subject"
                            class="oc-input"
                            placeholder="Contoh: Perkembangan tugas Matematika"
                            required
                        >
                    </div>

                    <div class="oc-form-group">
                        <label for="message">
                            <i class="bi bi-chat-left-text-fill"></i>
                            Isi Pesan
                        </label>

                        <textarea
                            name="message"
                            id="message"
                            class="oc-input oc-modal-textarea"
                            rows="4"
                            placeholder="Tuliskan pertanyaan atau konsultasi secara lengkap..."
                            required
                        ></textarea>
                    </div>

                    <div class="oc-form-group">
                        <label for="attachment">
                            <i class="bi bi-paperclip"></i>
                            Lampiran Berkas <span>(opsional)</span>
                        </label>

                        <input
                            type="file"
                            name="attachment"
                            id="attachment"
                            class="oc-input oc-file-input"
                        >

                        <small class="oc-form-help">
                            PDF, DOC, DOCX, JPG, PNG, ZIP. Maksimal 10 MB.
                        </small>
                    </div>
                </div>

                <div class="oc-modal-footer">
                    <button type="button" class="oc-button oc-button-light" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="oc-button oc-button-primary">
                        <i class="bi bi-send-fill"></i>
                        Kirim Pesan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function updateStudentTeacher(selectEl) {
        const val = selectEl.value;
        const studentInput = document.getElementById('hidden_student_id');
        const teacherInput = document.getElementById('hidden_teacher_id');

        if (val) {
            const parts = val.split('|');
            studentInput.value = parts[0] || '';
            teacherInput.value = parts[1] || '';
        } else {
            studentInput.value = '';
            teacherInput.value = '';
        }
    }
</script>
@endsection
